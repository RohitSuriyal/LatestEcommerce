<?php

namespace App\Http\Controllers;

use App\Events\UserLoggedIn;
use App\Models\Admin\Banner;
use App\Models\Admin\Brand;
use App\Models\Admin\ProductCategory;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{

   public function home()
   {
      $banners = Banner::all();

      $latesproducts = Product::latest()->take(10)->get();

      return view("frontend.home", compact('banners', 'latesproducts'));
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


      $product = Product::where("id", $id)->first();


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
}
