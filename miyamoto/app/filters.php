<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/
Route::filter('apikey',function(){
	$key = Request::segment(3);
	if(!empty($key)){
		$am = ApiModel::where('api_key',$key)->where('is_active',1)->count();
		if(empty($am)){
			$data['success'] = FALSE;
			$data['message'] = 'Api key not valid';
			return  Response::json($data);
		}
	}else{
		$data['success'] = FALSE;
		$data['message'] = 'Api key is required';
		return  Response::json($data);
	}
});

Route::filter('auth_admin',function(){
	$admin = Session::get('admin');
	if(empty($admin)){
		return Redirect::to('login')->withErrors(array('login'=>'Access denied'));
	}
});
Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

//ACL
Route::filter('acl', function(){

	$uri2  = Request::segment(2); //controller
	$login = Session::get('admin');

	if($login['level'] != 1){
		$access  = Permission::cekAccess($login['level'],$uri2);
		$control = Permission::cekController($uri2);
		if(!empty($access) || $control == "kosong"){
			return Redirect::to('admin/home');
		}
	}
});


Route::filter('acl_admin', function(){
	$login = Session::get('admin');
	
	if($login['level'] != 1){ 
		return Redirect::to('admin/home');
	}
});