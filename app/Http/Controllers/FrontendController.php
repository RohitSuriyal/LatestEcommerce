<?php

namespace App\Http\Controllers;

use App\Events\UserLoggedIn;
use App\Models\Admin\Banner;
use App\Models\Admin\Brand;
use App\Models\Admin\ProductCategory;
use App\Models\Product;
use App\Models\User;
use App\Models\Usercart;
use App\Models\Wishlist;
use App\View\Components\frontend\Cartmodal;
use Exception;
use Illuminate\Cache\Repository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PHPUnit\Event\Test\PreparationFailed;
use Stripe\StripeClient;

class FrontendController extends Controller
{

   public function home()
   {
      $banners = Banner::all();

      $latesproducts = Product::latest()->take(10)->get();
      if (Auth::guard("web")->check()) {

         $wishlist = Wishlist::all()->pluck("product_id")->toArray();
      } else {

         $wishlist = [];
      }

      return view("frontend.home", compact('banners', 'latesproducts', 'wishlist'));
   }
   public function allproducts($id)
   {
      $current_product = Product::findOrFail($id);

      // Paginate other products
      $products = Product::where('id', '!=', $id)->paginate(4);
      $categories = ProductCategory::all();
      $brands = Brand::all();


      // Prepend current product to the first page only
      if ($products->currentPage() === 1) {
         $products->getCollection()->prepend($current_product);
      }

      return view('frontend.allproducts', compact('products', 'id', 'categories', 'brands'));
   }

   public function paginateproducts(Request $request)
   {

      if ($request->filter || $request->categories || $request->brands) {

         // Build query with filters
         $query = Product::query();

         // Apply filters conditionally
         if ($request->categories && $request->brands) {
            // Combined search: products that match BOTH category AND brand
            $query->where(function ($q) use ($request) {
               $q->whereIn('category', $request->categories)
                  ->whereIn('brand', $request->brands);
            });
         } elseif ($request->categories) {
            // Category filter only
            $query->whereIn('category', $request->categories);
         } elseif ($request->brands) {
            // Brand filter only
            $query->whereIn('brand', $request->brands);
         }
         // Paginate directly from query
         $perPage = 4;
         //filters stay applied across the pages
         $paginatedProducts = $query->paginate($perPage)->withQueryString();

         // Fetch brands and categories (consider caching these)
         $brands = Brand::all();
         $categories = ProductCategory::all();

         // Selected filters
         $selectedcategories = $request->categories;
         $selctedbrands = $request->brands;

         // Render view
         $html = view('components.frontend.allproductsnew', [
            'products' => $paginatedProducts,
            'brands' => $brands,
            'categories' => $categories,
            'selectedcategories' => $selectedcategories,
            'selctedbrands' => $selctedbrands,
         ])->render();

         // Return JSON response
         return response()->json([
            'html' => $html
         ]);
      } else {

         $current_product = Product::findOrFail($request->id);
         $id = $request->id;

         // Paginate other products
         $products = Product::where('id', '!=', $request->id)->paginate(4);
         $categories = ProductCategory::all();
         $brands = Brand::all();

         // Prepend current product to the first page only
         if ($products->currentPage() === 1) {
            $products->getCollection()->prepend($current_product);
         }

         $html = view('components.frontend.allproductsnew', compact('products', 'id', 'categories', 'brands'))->render();

         return response()->json([
            'html' => $html,
         ]);
      }
   }
   public function singleproduct(string  $id)
   {

      try {

         $product = Product::where("id", $id)->first();

         return view('frontend.singleproduct', compact('product'));
      } catch (Exception $e) {
         return back()->with([
            "error" => $e->getMessage()
         ]);
      }
   }

   public function userauthcheck(String $id)
   {

      $product = Product::where("id", operator: $id)->first();
      return view("frontend.buynow", compact('product'));
   }

   public function userbuynowlogin(Request $request)
   {


      if ($request->otp != null && $request->email) {
         try {


            $user = User::where("email", $request->email)->first();

            if ($user && $user->otp == $request->otp) {

               $user->update([
                  "status" => true,
               ]);

               Auth::guard("web")->login($user);

               return response()->json([
                  "status" => "success",
                  "message" => "otp verfied successfully",
                  "user" => $user,
                  "verified" => true,
               ]);
            } else {

               return  response()->json([
                  "error" => "otp does not match",

                  "message" => "Otp Does not match"
               ]);
            }
         } catch (Exception $e) {

            return response()->json([
               "error" => $e->getMessage(),
               "message" => "This is the error we are getting" . $e->getMessage(),
            ]);
         }
      } else {

         try {

            $input = $request->email;

            // Check if user exists by email or phone
            $user = User::where('email', $input)
               ->first();

            if ($user) {

               $otp = rand(1000, 9999);
               $user->update([
                  "otp" => $otp,
               ]);

               UserLoggedIn::dispatch($user);
               return response()->json([
                  "status" => "success",
                  "user" => $user,
                  "message" => "OTP Send Successfully",

               ]);
            } else {
               // Generate 4-digit OTP
               $otp = rand(1000, 9999);

               // Create new user
               $user = User::create([
                  "email" => $input,
                  "otp"   => $otp
               ]);

               // Dispatch the event
               UserLoggedIn::dispatch($user);

               return response()->json([
                  "status" => "success",
                  "message" => "User created successfully OTP Sent",
                  "otp" => $otp,
                  "user" => $user
               ]);
            }
         } catch (Exception $e) {
            return response()->json([
               "error" => "Something went wrong",
               "message" => $e->getMessage()
            ], 500);
         }
      }
   }
   public function userdetailsubmit(Request $request)
   {

      try {

         $user = Auth::guard('web')->user();

         $currentuser = User::findOrFail($user->id);

         $validate = $request->validate([

            "name" => "required",
            "phone" => "required",
            "address" => "required",
            "city" => "required",
            "state" => "required",
            "pincode" => "required",
            "landmark" => "required",

         ]);
         $currentuser->update($validate);

         $product = Product::where('id', $request->product_id)
            ->where('stock', '>', 1)
            ->first();

         session()->put('product', $product->toArray());



         return back()->with(["success" => "Addressed saved Correctly"]);
      } catch (Exception $e) {


         return back()->with(["error" => $e->getMessage()]);
      }
   }
   public function quantity_checker(Request $request)
   {

      $product = Product::findOrFail($request->id);

      if ($product->stock >= $request->value) {

         return response()->json([
            "status" => "success",
            "message" => "Quantity Updated",
            "price" => $product->sale_price,


         ]);
      } else {
         return response()->json([
            "status" => "failure",
            "message" => "Product is Out of stock"
         ]);
      }
   }

   public function userloginview()
   {


      return view("frontend.userloginview");
   }

   public function userlogin(Request $request)
   {

      try {

         $validate = $request->validate([
            "email" => "required"
         ]);

         $user = User::where("email", $request->email)->first();

         if ($request->otp && $request->email) {


            if ($request->otp == $user->otp) {

               Auth::guard("web")->login($user);
               return redirect()->route("frontend.home")->with(["success" => "Logged In successfully"]);
            } else {

               return back()->with(["success" => "Otp does not match"]);
            }
         }
         if ($user) {
            Auth::guard("web")->login($user, true);


            $otp = rand(1000, 2000);
            $user->update([
               "otp" => $otp,
            ]);

            UserLoggedIn::dispatch($user);

            return back()->with(["success" => "Otp Sended Successfully", "email" => $user->email]);
         } else {

            return back()->with([

               "failure" => "Please Sign up"
            ]);
         }
      } catch (ValidationException $e) {

         return back()->withErrors($e->errors())->withInput();
      } catch (Exception $e) {




         return back()->with(["failure" => $e->getMessage()]);
      }
   }





   public function usersignupview(Request $request)
   {

      return view('frontend.usersignupview');
   }

   public function usersignup(Request $request)
   {

      try {

         if ($request->email && $request->otp) {

            $user = User::where("email", $request->email)->first();

            if ($user) {

               if ($request->otp == $user->otp) {

                  return redirect()->route("frontend.userloginview");
               } else {

                  return back()->with(["otpfailure" => "Otp did not matched"]);
               }
            }
         } else {

            $validate = $request->validate([
               "email" => "required|unique:users,email"
            ]);

            $otp = rand(1000, 2000);

            $user = User::create([
               "email" => $request->email,
               "otp" => $otp,

            ]);
            UserLoggedIn::dispatch($user);

            return back()->with(["success" => "otp send successfull", "usersignup" => "usersignup success", "usersignupemail" => $user->email]);
         }
      } catch (ValidationException $e) {

         return back()->withErrors($e->errors())->withInput();
      }
   }

   public function usersignupotpverify(Request $request)
   {

      $user = User::where("email", $request->email)->first();

      if ($user) {

         if ($request->otp && $request->email) {

            if ($request->otp == $user->otp) {

               return redirect()->route("website.userloginview")->with(['success' => "User sign up successfull"]);
            }
         } else {

            return back()->with(["failure" => "Something Went wrong"]);
         }
      }
   }
   public function cartdata(Request $request)
   {


      if (!Auth::guard("web")->check()) {

         return response()->json([
            "status" => "failure",
            "message" => "Please Log in"
         ]);
      }
      try {

         $userId = Auth::guard('web')->user()->id;
         if ($request->id == null) {
            $products = Usercart::where('user_id', $userId)->get();

            $html = view("components.frontend.cartmodal", compact('products'))->render();

            return response()->json([
               "status" => "cart_loaded",
               "message" => "Product Added Successfully",
               "html" => $html,
            ]);
         } else {


            $productId = $request->id;

            // Check if product already exists in user's cart
            $usercartproduct = Usercart::where('user_id', $userId)
               ->where('product_id', $productId)
               ->first();

            if ($usercartproduct) {
               // Increment quantity if product exists
               $usercartproduct->increment('quantity');
            } else {
               // Otherwise, create new cart item
               Usercart::create([
                  'user_id' => $userId,
                  'product_id' => $productId,
                  'quantity' => 1,
               ]);
            }

            // Always fetch latest products in user's cart
            $products = Usercart::where('user_id', $userId)->get();

            $html = view("components.frontend.cartmodal", compact('products'))->render();
            return response()->json([
               "status" => "added",
               "message" => "Product Added Successfully",
               "html" => $html,
            ]);
         }
      } catch (Exception $e) {


         return response()->json([
            "status" => "failure",
            "message" => $e->getMessage(),
         ]);
      }
   }

   public function cartproductqty(Request $request)
   {


      try {

         $value = $request->value;
         $id = $request->id;


         $product = Product::where("id", $id)->first();
         $cart_product = Usercart::where("product_id", $id)->where("user_id", Auth::guard("web")->user()->id)->first();

         $cart_product->update([
            "quantity" => (int)$cart_product->quantity + (int) $value,
         ]);

         $cart_product->refresh();
         $total_amount = 0;
         if ($product->stock > $cart_product->quantity) {

            $user_cart_products = Usercart::where("user_id", Auth::guard("web")->user()->id)->get();


            foreach ($user_cart_products as $cartproduct) {

               $total_amount += (int)$cartproduct->quantity * (int)$cartproduct->product->sale_price;
            }

            return response()->json([
               "status" => "success",
               "total_amount" => $total_amount,
               "quantity" => $cart_product->quantity,

            ]);
         }
      } catch (Exception $e) {

         return response()->json(["error" => $e->getMessage()]);
      }
   }

   public function userprofileview()
   {

      return view('components.frontend.userprofile');
   }
   public function liked_products(Request $request)
   {

      $user = Auth::guard("web")->user();


      if (!$user) {

         return response()->json([
            "status" => "notloggedin",
            "message" => "Please Logged In",
         ]);
      }


      if ($request->filled == "empty") {

         try {

            $product_id = $request->id;

            Wishlist::create([
               "user_id" => $user->id,
               "product_id" => $product_id,
            ]);

            return response()->json([
               "status" => "success"
            ]);
         } catch (Exception $e) {
            return response()->json(["status" => "failure"]);
         }
      } else {
         try {

            $product_id = $request->id;



            $product = Wishlist::where("product_id", $product_id)->where("user_id", Auth::guard("web")->user()->id);

            $product->delete();


            return response()->json([

               "status" => "filled",
            ]);
         } catch (Exception $e) {
         }
      }
   }
   public function userprofilesubmit(Request $request)
   {

      try {

         $user = User::findOrFail($request->id);

         $validate = $request->validate([
            "name" => "",
            "email" => "",
            "phone" => "",
            "state" => "",
            "city" => "",
            "address" => "",

         ]);
         $user->update($validate);


         return back()->with(["success" => "User Updates successfully"]);
      } catch (ValidationException $e) {

         return back()->with(["error" => "Something went wrong...!!!"]);
      }
   }
   public function checkoutsession(Request $request)
   {
      $request->validate([
         'amount' => 'required|numeric|min:0.5'
      ]);


      // Use config() instead of env()
      $stripe = new StripeClient(config('services.stripe.secret'));
      $amount = round($request->amount * 100); // smallest currency unit

      try {
         $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
               'price_data' => [
                  'currency' => 'usd', // or 'inr'
                  'product_data' => [
                     'name' => 'Custom Payment',
                  ],
                  'unit_amount' => $amount,
               ],
               'quantity' => 1,
            ]],
            'customer_creation' => 'always',
            'success_url' => route('frontend.checkoutsuccess') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('frontend.checkoutcancel'),
         ]);

         return response()->json(['url' => $session->url]);
      } catch (Exception $e) {
         return response()->json(['error' => $e->getMessage()], 500);
      }
   }
   public function success(Request $request)
   {
      $session_id = $request->get('session_id');
      if (!$session_id) {
         return "No session ID provided.";
      }

      $stripe = new StripeClient(config('services.stripe.secret'));

      try {


         $session = $stripe->checkout->sessions->retrieve($session_id);

         $paymentIntent = $stripe->paymentIntents->retrieve($session->payment_intent);

         $lineItems = $stripe->checkout->sessions->allLineItems($session_id, ['limit' => 100]);

         $email = $session->customer_details->email;

         return view('checkout.success', compact('session', 'paymentIntent', 'lineItems', 'email'));
      } catch (Exception $e) {

         return "Error retrieving session: " . $e->getMessage();
      }
   }



   public function wishlistproducts(Request $request)
   {

      if (!Auth::guard("web")->check()) {

         return response()->json([
            "status" => "notloggedin",
            "message" => "Plese Logged In",
         ]);
      }



      try {

         $cartproductids = Usercart::where("user_id", Auth::guard("web")->user()->id)->pluck("product_id");

         $wishlistproducts = Wishlist::where('user_id', Auth::id())
            ->whereNotIn('product_id', $cartproductids)
            ->get();



         $html = view("components.frontend.wishlistmodal", compact('wishlistproducts'))->render();

         return response()->json([
            "status" => "success",
            "html" => $html
         ]);
      } catch (Exception $e) {

         return response()->json([
            "status" => "failure",
            "error" => $e->getMessage(),
         ]);
      }
   }

   public function removefromcart(Request $request)
   {
      try {
         $id = $request->id;

         // Delete all cart items for the user (optional: use first() if you only want one)
         $cart_product = Usercart::where('product_id', $id)->first();

         if ($cart_product) {

            $cart_product->delete(); // delete the first item


            $products = Usercart::where("user_id", Auth::guard('web')->user()->id)->get(); // or: Usercart::where('user_id', $id)->get();

            // Render the view to HTML string
            $html = view('components.frontend.Cartmodal', compact('products'))->render();

            // Return JSON response
            return response()->json([
               'status' => 'success',
               'html' => $html,
            ]);
         }

         // If no cart item found
         return response()->json([
            'status' => 'failure',
            'message' => 'No cart item found for this user.',
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'status' => 'failure',
            'message' => $e->getMessage(),
         ]);
      }
   }
}
