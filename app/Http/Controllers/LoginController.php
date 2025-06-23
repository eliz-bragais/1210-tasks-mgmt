<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        
        if (Auth::attempt($validated, $request->remember)) {
            $request->session()->regenerate();

            $redirectUrl = Session::get('url.intended', route('task-mgmt'));
            Session::forget('url.intended');

            $fetchedUser = User::where('id', auth()->user()->id)->first();

            return response()->json([
                'message' => 'You have successfully logged in.',
                'url' => $redirectUrl,
            ]);
        }
    }

    public function signupPage(Request $request)
    {
        return view('signup');
    }

    public function signUp(Request $request)
    {
        $user_id = User::create([
            'name' => $request->first_name.' '.$request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0,
            'created_at' => now(),
        ])->id;

        $response = [
            'code' => '200: success response',
            'message' => 'User Successfully Sign Up.',
            'user_id' => $user_id,
        ];
        $code = 200;

        return response($response, $code);
    }

    public function logout(Request $request)
    {
        $fetchedUser = User::where('id', auth()->user()->id)->first();
        
        Auth::guard('web')->logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'You have successfully logged out.'
        ]);
    }
}
