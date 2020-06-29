<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserEdit;
class UsersController extends Controller
{
	public function create()
    {
        return view('users.create');
    }
    
    public function show(User $user)
    {
    	return view('users.show', compact('user'));
    }
    
    
    public function store(UserRequest $request){
    	$user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    	session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
    	Auth::login($user);
    	return redirect()->route('users.show', [$user]);
    	
    }
    
    public function edit(User $user)
    {
    	return view('users.edit', compact('user'));
    }
    
    
    public function update(User $user,UserEdit $request){
    	$data = [];
    	$data['name'] = $request->name;
    	if ($request->password) {
    		$data['password'] = bcrypt($request->password);
    	}
    	$user->update($data);
    	session()->flash('success', '个人资料更新成功！');
    	 
    	return redirect()->route('users.show',$user);
    }
}
