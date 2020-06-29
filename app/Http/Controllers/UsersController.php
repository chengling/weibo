<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserEdit;
class UsersController extends Controller
{	
	public  function __construct(){
		$this->middleware('auth',['except' =>['create','show','store','index']]);
		$this->middleware('guest', [
				'only' => ['create']
				]);
	}
	
	public function index(){
		$user  = User::paginate(10);
		return view('users.index',['user' =>$user]);	
	}
	
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
    	$this->authorize('update',$user);
    	return view('users.edit', compact('user'));
    }
    
    
    public function update(User $user,UserEdit $request){
    	$this->authorize('update',$user);
    	 
    	$data = [];
    	$data['name'] = $request->name;
    	if ($request->password) {
    		$data['password'] = bcrypt($request->password);
    	}
    	$user->update($data);
    	session()->flash('success', '个人资料更新成功！');
    	 
    	return redirect()->route('users.show',$user);
    }
    
    
    public function destroy(User $user,Request $request){
    	$this->authorize('destroy',$user);
    	$user->delete();
    	session()->flash('success', '成功删除用户！');
    	//return back();
    	return redirect()->route('users.index');
    }
}
