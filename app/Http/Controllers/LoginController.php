<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserLogin;
use Auth;
class LoginController extends Controller
{
    
	public function create(){
		return view('login.create');
	}
	
	
	public function store(UserLogin $request){
		if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
			session()->flash('success', '欢迎回来！');
			return redirect()->route('users.show', [Auth::user()]);
		}else{
			session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
			return redirect()->back()->withInput();
		}
	}
}
