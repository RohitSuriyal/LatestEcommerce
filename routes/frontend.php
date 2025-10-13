<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;


Route::prefix("website")->name("frontend.")->group(function () {


   Route::get("/home", [FrontendController::class, "home"])->name("home");

   Route::get("/allproducts/{id}", [FrontendController::class, "allproducts"])->name('allproducts');

   Route::post("/paginateproducts",[FrontendController::class,"paginateproducts"])->name('paginateproducts');
   Route::get("/singleproduct/{id}",[FrontendController::class,"singleproduct"])->name('singleproduct');

   Route::get("/userauthcheck/{id}",[FrontendController::class,"userauthcheck"])->name('userauthcheck');

   Route::post("/userbuynowlogin",[FrontendController::class,"userbuynowlogin"])->name('userbuynowlogin');

   Route::post("/userdetailsubmit",[FrontendController::class,"userdetailsubmit"])->name("userdetailsubmit");
   Route::post("/quantity_checker",[FrontendController::class,"quantity_checker"])->name("quantity_checker");

});
