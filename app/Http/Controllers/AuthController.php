<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

    public function index(){
        return view('auth');
    }

    public function register(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required|email|max:50|unique:users',
            'name' => 'required|string|max:30',
            'password' => 'required|string|min:8',
            'password2' => 'required|string|min:8',
        ]);

        if($validatedData['password'] == $validatedData['password2']) {
            $user = User::create([
                'email' => $validatedData['email'],
                'name' => $validatedData['name'],
                'password' => Hash::make($validatedData['password']),
            ]);

            auth()->login($user);

            return redirect('/games/my');
        } else {
            return back()->withErrors(['msg' => 'Паролі різні']);
        }
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/games');
        }

        return back()->withErrors(['email' => 'Неправильні дані для входу']);
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
