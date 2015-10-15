<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getIndex()
	{
		return View::make('backend.login.index');
	}

	public function postIndex()
	{
		// return View::make('backend.login');
		$rules = array(
			'username' => 'required',
			'password' => 'required'
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			
			return Redirect::to('login')->withErrors($valid);

		}else{

			$um = UserModel::where('email',Input::get('username'))
				  ->where('password',md5(Input::get('password').'mhayamoto'))
				  ->where('deleted_at',null)
				  ->first();
			if(count($um) > 0)
			{
			  	if($um->is_active==1){
				  	$data = array(
						'id'         => $um->id,
						'name'       => $um->name,
						'level'      => $um->level,
						'group_name' => Permission::CheckGroupName($um->level),
						'created_at' => $um->created_at
				  		);
					Session::put('admin',$data);
					return Redirect::to('admin/home')->with('valid','Welcome to dashboard');
				}else{
				  	return Redirect::to('login')->with('error_login','your account is not active');
				}
			}else{
			  	return Redirect::to('login')->with('error_login','your account is not valid');
			}

		}
	}

}
