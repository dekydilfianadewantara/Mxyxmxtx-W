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
Route::get('image',function(){
return HTML::image(Captcha::img(),'image');
});


Route::any('kirim',function(){
$nomor = (Input::get('number')?Input::get('number'):'');
$text = (Input::get('text')?Input::get('text'):'');
/*$aa = new ReceiveMessageModel;
$aa->msisdn = ($nomor?$nomor:'');
$aa->text = ($text?$text:'');
$aa->save();*/
//if($nomor && $text){
Helper::CallbackGammu($nomor,$text);
//}
});
Route::any('coba',function(){
$aa = OutboxSms::all();
$tot = count($aa);
echo '<p>';
foreach($aa as $i => $row){
echo $row->number.'::'.$row->body;
if($i<$tot-1){echo ';;';}
OutboxSms::find($row->id)->delete();
}
echo '</p>';
});

Route::get('date',function(){
echo Date('Y-m-d H:i:s');
// $request = array_merge($_GET, $_POST);

// // check that request is inbound message
// if(!isset($request['to']) OR !isset($request['msisdn']) OR !isset($request['text'])){
//     error_log('not inbound message');
//     return;
// }

// $VM             = new VisitorModel;
// $VM->ip         = $request['msisdn'];
// $VM->id_article = 1;
// $VM->type       = 1;
// $VM->save();
});

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

// App::error(function($exception, $code) { 
// 	if(!Request::is('admin/*')){		
// 		return View::make('front.404');
// 	}
// });