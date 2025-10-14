<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\Upload;
use App\Models\Admin\Adminuser;
use App\Models\Admin\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(["adminauth","mail_token_refresh"])->name("admin.")->group(function () {
    Route::get("/dashnoard", function () {
        $brandcount=Brand::count();
        return view("admin.dashboard.index",compact('brandcount'));
    })->name('dashboard');
    Route::resource('Productcategory', ProductCategoryController::class);
    Route::resource("brand", BrandController::class);
    Route::resource('subcategory', SubcategoryController::class);
    Route::resource("product", ProductController::class);
    Route::post("/Upload", [Upload::class, "upload"])->name('upload.process');
    Route::resource('banner', BannerController::class);
    Route::post("/bulkupload", [ProductController::class, "bulkupload"])->name('bulkupload');
});
