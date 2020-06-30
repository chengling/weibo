<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StatusCreate;
use Auth;
use App\Models\Status;
class StatusesController extends Controller
{


	public function __construct(){
		$this->middleware('auth');
	}
	
	public function store(StatusCreate $request){
		Auth::user()->statuses()->create(['content' => $request->content]);
		session()->flash('success', '发布成功！');
		return redirect()->back();
	}
	
	public function destroy(Status $status){
		$this->authorize('destroy',$status);
		$status->delete();
		session()->flash('success', '删除成功！');
		return redirect()->back();
	}
}
