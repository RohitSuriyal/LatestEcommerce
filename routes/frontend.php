<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\FrontendController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;


Route::prefix("website")->name("frontend.")->group(function () {

   Route::get("/home", [FrontendController::class, "home"])->name("home");

   Route::get("/allproducts/{id}", [FrontendController::class, "allproducts"])->name('allproducts');

   Route::post("/paginateproducts", [FrontendController::class, "paginateproducts"])->name('paginateproducts');
   Route::get("/singleproduct/{id}", [FrontendController::class, "singleproduct"])->name('singleproduct');

   Route::get("/userauthcheck/{id}", [FrontendController::class, "userauthcheck"])->name('userauthcheck');

   Route::post("/userbuynowlogin", [FrontendController::class, "userbuynowlogin"])->name('userbuynowlogin');

   Route::post("/userdetailsubmit", [FrontendController::class, "userdetailsubmit"])->name("userdetailsubmit");
   Route::post("/quantity_checker", [FrontendController::class, "quantity_checker"])->name("quantity_checker");

   Route::post("/cartdata", [FrontendController::class, "cartdata"])->name('cartdata');

   Route::get("/userloginview", [FrontendController::class, "userloginview"])->name("userloginview");

   Route::get("/userlogin", [FrontendController::class, "userlogin"])->name("userlogin");

   Route::get("/usersignupview", [FrontendController::class, "usersignupview"])->name("signupview");
   Route::get("/usersignup", [FrontendController::class, "usersignup"])->name("usersignup");

   Route::get("/logout", function (Request $request) {

      FacadesAuth::guard("web")->logout();

      $request->session()->invalidate();
      $request->session()->regenerateToken();


      return redirect()->route("frontend.home")->with(["success" => "Logout Successfully"]);
   })->name("logout");

   Route::post("/cartproductqty", [FrontendController::class, "cartproductqty"])->name("cartproductqty");

   Route::get("userprofileview", [FrontendController::class, "userprofileview"])->name("userprofileview");

   Route::post("/liked_products", [FrontendController::class, "liked_products"])->name("liked_products");
   Route::get("/userprofilesubmit", [FrontendController::class, "userprofilesubmit"])->name("userprofilesubmit");

   Route::post("/checkoutsession", [FrontendController::class, "checkoutsession"])->name('checkoutsession');
   Route::get("/checkoutsuccess", [FrontendController::class,"success"])->name('checkoutsuccess');

   Route::get("/checkoutfailure", function () {
      return view('checkout.failure');
   })->name('checkoutcancel');

   Route::post("/wishlistproducts",[FrontendController::class,"wishlistproducts"])->name("wishlistproducts");

   Route::post("/removefromcart/{id}",[FrontendController::class,"removefromcart"])->name('removefromcart');


});
