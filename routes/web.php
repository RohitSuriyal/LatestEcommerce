<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/admin.php';
Route::get('/', function () {

    
    return view('admin.register');

})->name('admin.register');

Route::get("/admin/login", function () {

    return view("admin.login");
})->name("admin.loginview");

Route::get("/admin/logout", function () {

    Auth::guard('admin')->logout();

    // Optional: invalidate the session
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    // Redirect to admin login page
    return redirect()->route('admin.login'); // make sure this route exists


})->name('admin.logout');

Route::post("/admin/register", [AuthController::class, 'signup'])->name('admin.signup');
Route::post("/admin/login", [AuthController::class, 'login'])->name('admin.login');
