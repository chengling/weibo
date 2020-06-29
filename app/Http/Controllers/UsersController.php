<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
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
    	return [];
    }
}
