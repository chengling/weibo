<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserEdit;
use Mail;
use Auth;
class UsersController extends Controller
{	
	public  function __construct(){
		$this->middleware('auth',['except' =>['create','show','store','index','confirmEmail']]);
		$this->middleware('guest', [
				'only' => ['create']
				]);
	}
	
	public function index(){
		$user  = User::paginate(10);
		return view('users.index',['users' =>$user]);	
	}
	
	public function create()
    {	
    	
        return view('users.create');
    }
    
    public function show(User $user)
    {
    	$statuses = $user->statuses()->orderBy('created_at','desc')->paginate(10);
    	return view('users.show', ['statuses'=>$statuses,'user'=>$user]);
    }
    
    
    public function store(UserRequest $request){
    	$user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    	$this->sendEmailConfirmationTo($user);
    	session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
    	return redirect('/');
    	
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
    
    
    protected function sendEmailConfirmationTo($user)
    {
    	$view = 'emails.confirm';
    	$data = compact('user');
    	$to = $user->email;
    	$subject = "感谢注册 Weibo 应用！请确认你的邮箱。";
    
    	Mail::send($view, $data, function ($message) use ($to, $subject) {
    		$message->to($to)->subject($subject);
    	});
    }
    
    
    public function confirmEmail($token)
    {
    	$user = User::where('activation_token', $token)->firstOrFail();
    
    	$user->activated = true;
    	$user->activation_token = null;
    	$user->save();
    
    	Auth::login($user);
    	session()->flash('success', '恭喜你，激活成功！');
    	return redirect()->route('users.show', [$user]);
    }
    
 	public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
