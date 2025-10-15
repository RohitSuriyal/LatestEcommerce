<?php

use App\Events\AdminRegister;
use App\Http\Controllers\Admin\AuthController;
use App\Models\Admin\Adminuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/admin.php';
require __DIR__ . '/frontend.php';

Route::middleware(["mail_token_refresh"])->group(function () {

    Route::get('/admin/signup', function () {
        return view('admin.register');
    })->name('admin.register');

    Route::get("/", function () {

        return view("admin.login");
    })->name("admin.loginview");

    Route::get("/admin/logout", function () {


       
        Auth::guard('admin')->logout();

        // Optional: invalidate the session
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        // Redirect to admin login page
        return redirect()->route('admin.loginview'); // make sure this route exists


    })->name('admin.logout');

    Route::post("/admin/register", [AuthController::class, 'signup'])->name('admin.signup');
    Route::post("/admin/login", [AuthController::class, 'login'])->name('admin.login');
});


Route::get("/verificationnotice", [AuthController::class, "verificationnotice"])->name('admin.verificationnotice');

Route::get('/verify-user/{id}', function (Request $request, $id) {
    if (! $request->hasValidSignature()) {
        abort(401, 'Verification link expired or invalid.');
    }

    $user = Adminuser::findOrFail($id);
    $user->email_verified_at = now();
    $user->status = true;
    $user->save();

    Auth::guard("admin")->login($user);

    return redirect()->route('admin.dashboard');
})->name('verify.user');

Route::get("/reendwelcomeemail/{id}", function (string $id) {

    $user = Adminuser::where("id", $id)->first();
    AdminRegister::dispatch($user);

    return redirect()->route("admin.verificationnotice")->with(["user" => $user, "success" => "Email Resended Succesfully"]);
})->name('admin.resend_welcome_email');
