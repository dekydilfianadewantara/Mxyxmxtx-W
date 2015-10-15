<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// API Controller ------------ Created By Deky ----------------------------------------------

 // Login On Android 
Route::post('loginAPI', function() {
	$email = Request::input('email');
	$password = Request::input('password');

		$user= User::where('email',$email)
				  ->where('password',md5($password.'mhayamoto'))
				  ->where('deleted_at',NULL)
				  ->first();
  
 	$id = $user->id;
 	$userget = User::find($id);
	if (count($user) > 0)
	{
		return [
			'success' => true,
			'id' => (String) $userget->id,
			'email' => (String) $userget->email,
			'name' => (String) $userget->name
		];
	}
	else{
		return [
			'success' => false,
			'id' => null,
			'email' => null,
			'name' => null
		];
	}
	

});

 // Register Account On Android 
Route::post('/registerAPI', function(){


		$beforeRegister = count(User::all());
   		  $user = new User;
        $name = Request::input('username');
        $email = Request::input('email');
        $password = Request::input('password');
        $password = md5($password.'mhayamoto');
        $address = Request::input('address');	
        $longitude = Request::input('longitude');
        $latitude = Request::input('latitude');
        $phone = Request::input('phone');

        //----
        $vehicles = Request::input('vehicles');
        $charge = Request::input('charge');
        $payment = Request::input('payment');
        $time_start = Request::input('time_start');
        $time_end = Request::input('time_end');  

        $files = Input::file('photo');
       
		//$time = "14:11";

		$cekEmail= User::where('email',$email)->get();

		if(count($cekEmail) == 0){
        $user->name = $name;
       	$user->email = $email;
       	$user->password = $password;
       	$user->address = $address;
       	$user->longitude = $longitude;
       	$user->latitude = $latitude;
       	$user->phone = $phone;
       	$user->level = "3";
        $user->save();
        //Photo
        $destinationPath = public_path('images/users');
        $fileName = $user->id.".PNG";
        $files->move($destinationPath, $fileName);
        $user->image = $destinationPath."/".$fileName;
        $user->save();

        $userDetail = new UserDetail;
        $userDetail->id_user = $user->id;
        $userDetail->allocation = "disini";
        $userDetail->charge_per_kilometer = $charge;
        $userDetail->id_vehicles_size = $vehicles;
        $userDetail->start_time_available = $time_start;	
        $userDetail->end_time_available = $time_end;
        $userDetail->id_payment = $payment;
        $userDetail->longitude = $longitude;
       	$userDetail->latitude = $latitude;
       	$userDetail->save();

       		$user = array(
			'id' => $user->id,
			'name' => $name,
			'email' => $email,
			'password' => $password,
			'address' => $address,
			'longitude' => $longitude,
			'latitude' => $latitude,
			'phone' => $phone,
			'image' => $user->image ,
			'level' => $user->level ,
			);
       }
	    $afterRegister = count(User::all());

	
 		
		if ($afterRegister > $beforeRegister)
		{
			return [
				'success' => true,
				'user' => $user
			];
		}
		else{
			return [
				'success' => false,
				'user' => null,
			];
		}

		
});

/*Route::get('/getBid', function(){
   		$bids = DB::table('bid')->get();  

   		return [
				'success' => false,
				'user' => null,
			];	
		return $bids;
});

*/
//Check List Bidding
Route::get('/getBids', function(){
		# get all bid data
   		$requests = DB::table('request')->get();  
   		foreach ($requests as $key => $row) {
   			# get current bid
   			$bids = DB::table('bid')->where('id_request', $row->id)->max('price'); 
   			if($bids == 0){
   				$bids = 0;
   			}
   			# get current image request item
   			$image = DB::table('request_item')->where('id_request', $row->id)->pluck('image');
   			if($image == null){
   				$image = "default.png";
   			}	
   			$jsonData[$key] = array('id' 			=> (String) $row->id,
   									'id_user' 		=> (String) $row->id_user,
   									'title' 		=> (String) $row->title,
   									'open_date' 	=> (String) $row->open_date,
   									'close_date' 	=> (String) $row->close_date,
   									'open_price' 	=> (String) $row->open_price,
   									'longitude_from' => (String) $row->longitude_from,
   									'latitude_from' => (String) $row->latitude_from,
   									'desc_from' 	=> (String) $row->desc_from,
   									'longitude_to' 	=> (String) $row->longitude_to,
   									'latitude_to' 	=> (String) $row->latitude_to,
   									'desc_to' 		=> (String) $row->desc_to,
   									'distance' 		=> (String) $row->distance,
   									'duration' 		=> (String) $row->duration,
   									'status' 		=> (String) $row->status,
   									'expire' 		=> (String) $row->expire,
   									'created_at' 	=> (String) $row->created_at, 
   									'updated_at' 	=> (String) $row->updated_at,
   									'current_bid' 	=> (String) $bids, 
   									'image'			=> (String) $image   									
   									);
   		}

   		print json_encode($jsonData);
});
// Check User Order
Route::get('/getOrder/{idUser}', function($idUser){
		# get order data user
   		$requests = DB::table('request')->where('id_user',$idUser)->get();  
   		
   		$jsonData = array();
   		foreach ($requests as $key => $row) {

   			# get current bid
   			$bids = DB::table('bid')->where('id_request', $row->id)->max('price'); 
   			if($bids == 0){
   				$bids = 0;
   			}
   			# get current image request item
   			$image = DB::table('request_item')->where('id_request', $row->id)->pluck('image');
   			if($image == null){
   				$image = "default.png";
   			}
   			$jsonData[$key] = array('id' 			=> (String) $row->id,
   									'id_user' 		=> (String) $row->id_user,
   									'title' 		=> (String) $row->title,
   									'open_date' 	=> (String) $row->open_date,
   									'close_date' 	=> (String) $row->close_date,
   									'open_price' 	=> (String) $row->open_price,
   									'longitude_from' => (String) $row->longitude_from,
   									'latitude_from' => (String) $row->latitude_from,
   									'desc_from' 	=> (String) $row->desc_from,
   									'longitude_to' 	=> (String) $row->longitude_to,
   									'latitude_to' 	=> (String) $row->latitude_to,
   									'desc_to' 		=> (String) $row->desc_to,
   									'distance' 		=> (String) $row->distance,
   									'duration' 		=> (String) $row->duration,
   									'status' 		=> (String) $row->status,
   									'expire' 		=> (String) $row->expire,
   									'created_at' 	=> (String) $row->created_at, 
   									'updated_at' 	=> (String) $row->updated_at,
   									'current_bid' 	=> (String) $bids, 
   									'image'			=> (String) $image 									
   									);
   		}

   		print json_encode($jsonData);
});


//Test Upload Photo
Route::post('/photo', function(){
    # get all bid data
       $destinationPath = public_path('images/users');
       $fileName = "lol.PNG";
       $files = Input::file('photo');
       $files->move($destinationPath, $fileName);
    
});

//Select Payment on Register
Route::get('/getPayments', function(){
    # get all bid data
      $requests = DB::table('payments')->where('is_active','1')->get();
      foreach ($requests as $key => $row) {
        # get current bid
    
        $jsonData[$key] = array(
                    'id'     => (String) $row->id,
                    'name'     => (String) $row->name,
                    'is_active'   => (String) $row->is_active,
                                   
                    );
      }

      print json_encode($jsonData);
});

//Select Vehicles on Register
Route::get('/getVehicles', function(){
    $vehicles = DB::table('vehicles_size')->where('is_active','1')->get(); 
    return $vehicles;
});

/*//See all user
Route::get('/getUser', function(){
      $users =  User::all();
    return $users;
});

*/

// Set The Bid on Bidding
Route::post('/setBid', function(){


        $id_user = Request::input('id_user');
        $id_request = Request::input('id_request');
        $price = Request::input('price');

        $bid = new BidModel;
        $bid->id_user = $id_user;
        $bid->id_request = $id_request;
        $bid->price = $price;
        $bid->save();
    
});


// API Controller
Route::group(array('before'=>'apikey'),function(){
	Route::controller('/api/register/{key?}','ApiRegister');
});

Route::get('customers/{customer}/schedules', 'SchedulesController@index');


Route::get('/','Home@index');
Route::get('/register','Home@register');
Route::get('/detail-register','Home@nextregister');
Route::post('/complete','Home@storenextregister');
Route::post('/register','Home@postRegister');
Route::get('/success','Home@success');
Route::get('/forgot','Home@forgot');
Route::post('/forgot','Home@postForgot');

Route::get('/hanyamoto/callback','Home@callback');
Route::get('/hanyamoto/winner','Home@getWinner');

Route::controller('login','HomeController');
Route::group(array('before'=>'auth_admin'),function(){

	Route::group(array('prefix' => 'admin'), function(){

		Route::get('/',function(){
			return Redirect::to('/admin/home');
		});
		Route::get('/home','Dashboard@index');
		Route::get('/notifications','Dashboard@notification');
		Route::post('/home','Dashboard@postIndex');
		Route::controller('/profile','Profile');
		//ACL
		Route::group(array('before' => 'acl'), function(){
			Route::controller('/about','About');
			Route::controller('/find-work','Project');
			Route::controller('/request','Requests');
			Route::controller('/messages','Messages');
			Route::controller('/users','Users');
			
		});
		Route::group(array('before' => 'acl_admin'), function(){

			// Route::controller('/deleted-staff','DeletedUsers');
			Route::controller('/group','Groups');
			Route::controller('/permission','Permissions');

		});
	});
	Route::get('/logout','Dashboard@logout');
	
});
// App::error(function($exception, $code) { 
// 	if(Request::is('admin/*')){
// 		switch ($code) {
// 			case 404:
// 				$errors = 'Page not found';
// 				$note   = 'We could not find the page you were looking for';
// 				break;
// 			default:
// 				$errors = 'Something went wrong';
// 				$note   = 'We will work on fixing that right away';
// 				break;
// 		}
// 		View::share('title',$code);
// 		View::share('path',$errors);
// 		$data['code']  = $code;
// 		$data['error'] = $errors;
// 		$data['note']  = $note;
// 		return View::make('backend.errors',$data);
// 	}
// });

App::error(function($exception, $code) { 
	if(Request::is('api*')){		
		$data['success'] = FALSE;
		$data['message'] = 'URL not valid';
		return  Response::json($data);
	}
});