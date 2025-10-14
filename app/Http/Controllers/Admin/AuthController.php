<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminRegister;
use App\Http\Controllers\Controller;
use App\Models\Admin\Adminuser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signup(Request $request)
    {

        try {

            $validated =  $request->validate([
                "email" => "required|unique:adminusers",
                "password" => "required|confirmed"
            ]);

            $validated["password"] = Hash::make($request->password);

            $user = Adminuser::create($validated);




            AdminRegister::dispatch($user);


            session()->put('user', $user);


            return redirect()->route("admin.verificationnotice");
        } catch (ValidationException $e) {

            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {

            return back()->with(["error" => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {

        $validated = $request->validate([
            "email" => "required",
            "password" => "required",
        ]);


        $admin = Adminuser::where("email", $request->email)->first();

        if (!$admin) {

            throw  ValidationException::withMessages([
                'email' => ['email not exist']
            ]);
        } else {

            if (!Hash::check($request->password, $admin->password)) {
                throw ValidationException::withMessages([
                    'password' => ['Password is not valid.'],
                ]);
            } else {

                Auth::guard("admin")->login($admin);
                if ($admin->status == true) {
                    return redirect()->route('admin.dashboard');
                } else {
                    AdminRegister::dispatch($admin);

                    session()->put('user', $admin);

                    return redirect()->route("admin.verificationnotice");
                }
            }
        }
    }
    public function verificationnotice()
    {

        return view("admin.verifynotice");
    }
}
