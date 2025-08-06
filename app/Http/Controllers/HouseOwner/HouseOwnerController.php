<?php

namespace App\Http\Controllers\HouseOwner;

use App\Http\Controllers\Controller;
use App\Models\HouseOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HouseOwnerController extends Controller
{
    public function showLoginForm()
    {
        // Check if user is already authenticated
        if (Auth::guard('house_owner')->check()) {
            return redirect()->route('owner.dashboard');
        }
        
        return view('owner.auth.loginForm');
    }

  
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('house_owner')->attempt($credentials)) {
            return redirect()->intended('/owner/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function registerForm()
    {
        // Check if user is already authenticated
        if (Auth::guard('house_owner')->check()) {
            return redirect()->route('owner.dashboard');
        }
        
        return view('owner.auth.registerForm');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:house_owners',
            'password' => 'required|string|min:8',
        ]);

        $houseOwner = new HouseOwner();
        $houseOwner->name = $request->name;
        $houseOwner->email = $request->email;
        $houseOwner->password = Hash::make($request->password);
        $houseOwner->save();

        return redirect()->route('owner.login')->with('success', 'House owner registered successfully');
    }

    public function logout()
    {
        Auth::guard('house_owner')->logout();
        return redirect()->route('owner.login');
    }
}
