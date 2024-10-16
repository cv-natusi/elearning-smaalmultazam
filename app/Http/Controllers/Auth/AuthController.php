<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

use Help;

class AuthController extends Controller
{
	public function login(Request $request){
		return Auth::user() ? redirect()->route('dashboard') : view('auth.login');
	}
	public function doLogin(Request $request){
		if(Users::where('email',$request->email)->first() && Auth::attempt($request->only('email','password'))){
			return Help::resHttp(['code'=>200,'message'=>'Login successful!']);
		}
		return Help::resHttp(['code'=>401,'message'=>'Username atau password tidak valid!']);
	}
	public function logout(Request $request){
		$request->session()->flush();
		Auth::logout();
		return redirect()->route('auth.login');
	}
}