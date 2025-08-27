<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Find user in database
        $user = DB::table('users')->where('email', $request->email)->first();

        if ($user && password_verify($request->password, $user->password)) {
            // Start a session manually (since we’re not using Laravel Breeze here)
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);

            return redirect('/dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // If login fails
        return back()->with('error', 'Invalid email or password.');
    }
}
