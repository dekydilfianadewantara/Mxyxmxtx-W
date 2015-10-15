<?php

class Home extends BaseController {
	public $limit = 9;
	public $ip;

	public function __construct()
	{
		 $this->ip = $_SERVER['REMOTE_ADDR'];
	}

	public function index()
	{
		return View::make('backend.login.index');
	}

	public function forgot()
	{
		return View::make('backend.login.forget');
	}

	public function postForgot()
	{
		$rules = array(
			'email' => 'required|email'
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('forgot')->withErrors($valid);
		}else{
				  // ->where('password',md5(Input::get('password').'mhayamoto'))
			$um = UserModel::where('email',Input::get('email'))
				  ->where('deleted_at',null)
				  ->first();
			if(count($um) > 0)
			{
			  	if($um->is_active==1){
			  		$random = rand(111111,999999);
				  	$um->password = md5($random.'mhayamoto');
				  	$um->save();

				  	Mail::queue('backend.login.message',$data = array('password' => $random),function($message) use ($um){
						$message->to($um->email,$um->name)->subject('New Password');
					});
					return Redirect::to('forgot')->with('success_forgot','Please check your new password to your email');
				}else{
				  	return Redirect::to('forgot')->with('error_forgot','Your account is not active, please contact administrator');
				}
			}else{
			  	return Redirect::to('forgot')->with('error_forgot','Your email is not valid');
			}

		}
	}

	public function register()
	{
		if(Session::has('detail_register')){
			Session::forget('detail_register');
		}
		$data['list_country'] = ListCountryModel::orderBy('id','asc')->get();
		$data['group'] = UsersGroupModel::where('for_register',1)->orderBy('id','desc')->get();
		return View::make('backend.login.register',$data);
	}


	public function postRegister()
	{
		$rules = array(
			'full_name'       => 'required|min:3',
			'email'           => 'required|email|unique:users,email',
			'phone'           => 'required|numeric',
			'address'         => 'required',
			'code_area'       => 'required',
			'want_option'     => 'required|numeric',
			'password'        => 'required|min:5',
			'retype_password' => 'required|same:password',
			'captcha'         => 'required|captcha',
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('register')
				->withErrors($valid)
				->withInput();
		}else{
			$full_name    = Input::get('full_name');
			$email        = Input::get('email');
			$address      = Input::get('address');
			$phone        = Input::get('phone');
			$level        = Input::get('want_option');
			$password     = md5(Input::get('password').'mhayamoto');
			$code_area    = Input::get('code_area');
			// $code_area    = str_replace("+","",Input::get('code_area'));
			$phone_number = $code_area.$phone;

			$CheckNo = UserModel::where('phone',$phone_number)->count();
			if(!empty($CheckNo)){
				return Redirect::to('register')
					->with('phone_unique', 'The phone number has already been taken.')
					->withInput();	
			}else{
				$UG = UsersGroupModel::find($level);
				if($UG->is_detail){
					$data = array(
						'full_name'   => $full_name,
						'email'       => $email,
						'phone'       => $phone_number,
						'address'     => $address,
						'want_option' => $level,
						'password'    => $password
			  		);
					Session::put('detail_register',$data);
					return Redirect::to('detail-register');
				}else{
					$UM           = new UserModel;
					$UM->name     = $full_name;
					$UM->email    = $email;
					$UM->address  = $address;
					$UM->phone    = $phone_number;
					$UM->level    = $level;
					$UM->password = $password;
					$UM->save();

					return Redirect::to('success')->with('success_reg','Data has been created');
				}
			}
		}
	}

	public function nextregister()
	{
		$DetReg = Session::get('detail_register');
		if($DetReg){
			$data['size'] = VehiclesModel::orderBy('id','asc')->where('is_active',1)->get();
			$data['payment'] = PaymentsModel::orderBy('id','asc')->where('is_active',1)->get();
			return View::make('backend.login.detail_register',$data);
		}else{
			return Redirect::to('register')->with('register_first','Please complete this form first');
		}
	}

	public function storenextregister()
	{
		if(Session::has('detail_register')){
			$rules = array(
				'charge_per_kilometer' => 'required|numeric',
				'vehicles_size'        => 'required',
				'payment_method'        => 'required',
				'latitude'             => 'required',
				'longitude'            => 'required'
				);
			$valid = Validator::make(Input::all(),$rules);
			if($valid->fails())
			{
				return Redirect::to('detail-register')
					->withErrors($valid)
					->withInput();
			}else{
				$Detail = Session::get('detail_register');

				$UM           = new UserModel;
				$UM->name     = $Detail['full_name'];
				$UM->email    = $Detail['email'];
				$UM->address  = $Detail['address'];
				$UM->phone    = $Detail['phone'];
				$UM->level    = $Detail['want_option'];
				$UM->password = $Detail['password'];
				$UM->save();

				$UD = new UsersDetailModel;
				$UD->id_user              = $UM->id;
				$UD->charge_per_kilometer = Input::get('charge_per_kilometer');
				$UD->id_vehicles_size     = Input::get('vehicles_size');
				// $UD->start_time_available = Input::get('start_available');
				// $UD->end_time_available   = Input::get('end_available');
				$UD->id_payment           = Input::get('payment_method');
				$UD->latitude             = Input::get('latitude');
				$UD->longitude            = Input::get('longitude');
				$UD->save();

				Session::forget('detail_register');
				return Redirect::to('success')->with('success_reg','Thank you for complete registration');
			}
		}else{
			return Redirect::to('register')->with('register_first','Please complete this form first');
		}
	}

	public function success()
	{
		if(Session::has('success_reg')){
			return View::make('backend.login.success');
		}else{
			return Redirect::to('login');
		}
	}

	public function callback()
	{
		// work with get or post
		$request = array_merge($_GET, $_POST);
		// $to               = $request['to'];
		// $msisdn           = $request['msisdn'];
		// $text             = $request['text'];
		// $type             = $request['type'];
		// $messagetimestamp = $request['message-timestamp'];
		// $messageId        = $request['messageId'];
		// check that request is inbound message
		if(!isset($request['to']) OR !isset($request['msisdn']) OR !isset($request['text'])){
		    error_log('not inbound message');
		    return;
		}

		$rec = new ReceiveMessageModel;
		$rec->msisdn           = $request['msisdn'];
		$rec->text             = $request['text'];
		// $rec->type             = $request['type'];
		// $rec->messagetimestamp = ($request['message-timestamp'])?$request['message-timestamp']:null;
		// $rec->messageId        = ($request['messageId'])?$request['messageId']:null;
		$rec->save();

		$um = UserModel::where('phone','+'.$request['msisdn'])->first();
		if(!empty($um)){
			$SMS = explode("#", $request['text']);
			if(count($SMS)==3){
				$dateNow    = date('Y-m-d H:i').':00';
				//BID#21#10000 (BID#idrequest#price)
				if($SMS[0]=='BID' && is_numeric($SMS[1]) && is_numeric($SMS[2])){
					$ID_REQUEST = $SMS[1];
					$PRICE      = $SMS[2];
					$rm = RequestModel::find($ID_REQUEST);
					if(!empty($rm)){
						if($dateNow <= $rm->close_date){
							$bCheck = BidModel::where('id_user',$um->id)->where('id_request',$ID_REQUEST)->count();
							if(empty($bCheck)){
								if($PRICE <= $rm->open_price){
									$bm = new BidModel;
									$bm->id_user    = $um->id;
									$bm->id_request = $ID_REQUEST;
									$bm->price      = $PRICE;
									$bm->save();

									//Send SMS
									$RIM = RequestItemModel::where('id_request',$ID_REQUEST)->get();
									foreach ($RIM as $key => $value) {
										$Berat[] = $value->weight;
									}
									if(!empty($Berat)){
									$DataAgent  = DB::table('user_detail')
													->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
													->where('vehicles_size.size','>',array_sum($Berat))
													->where('user_detail.id_user','!=',$um->id)->get();
									}else{
									$DataAgent  = DB::table('user_detail')
													->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
													->where('user_detail.id_user','!=',$um->id)
													->get();
									}
									$lat1  = $rm->latitude_from;
									$lng1  = $rm->longitude_from;
									$from  = $rm->desc_from;
									$to    = $rm->desc_to;
									$title = $rm->title;
									foreach ($DataAgent as $key => $value) {
										$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
										if($jarak <= 10){
											Nexmo::SendNotifToOther($value->id_user,$from,$to,$PRICE,$title,$ID_REQUEST);
										}
									}
								}
							}
						}
					}
				}
				//REQUEST#21#YES (request#idrequest#yes)
				if ($SMS[0]=='REQUEST' && is_numeric($SMS[1]) && $SMS[2]=='YES') {
					$IDREQUEST = $SMS[1];
					$RM = RequestModel::find($IDREQUEST);
					if(!empty($RM)){
						if($dateNow > $RM->close_date && $RM->expire == 0){
							$CheckStatus = BidModel::where('id_user',$um->id)
											->where('id_request',$IDREQUEST)
											->where('sent',1)
											->where('reply',0)
											->where('ignore',0)
											->where('finish_time','>=',$dateNow)
											->count();
											if(!empty($CheckStatus)){
												// $CheckBid = BidModel::where('id_user',$um->id)
												// 			->where('id_request',$IDREQUEST)
												// 			->where('reply',1)->count();
												// 			if(empty($CheckBid)){
												DB::table('bid')
													->where('id_user',$um->id)
													->where('id_request',$IDREQUEST)
													->update(array('status' => 1, 'reply' => 1, 'notif' => 0));
															// }
													$r = RequestModel::find($IDREQUEST);
													$r->status = 1;
													$r->expire = 1;
													$r->save();
													Nulinexmo::sendText($request['msisdn'], 'Imhanya', 'Please come to ('.$RM->desc_from.') to get your job');
											}
						}
					}
				}
				if ($SMS[0]=='UPDATE' && is_numeric($SMS[1]) && is_numeric($SMS[2])) {
					$ID_REQUEST = $SMS[1];
					$PRICE      = $SMS[2];
					$rm = RequestModel::find($ID_REQUEST);
					if(!empty($rm)){
						if($dateNow <= $rm->close_date){
							$bCheck = BidModel::where('id_user',$um->id)->where('id_request',$ID_REQUEST)->count();
							if(!empty($bCheck)){
								if($PRICE <= $rm->open_price){
									DB::table('bid')
										->where('id_user',$um->id)
										->where('id_request',$ID_REQUEST)
										->update(array('price'=>$PRICE));

									//Send SMS
									$RIM = RequestItemModel::where('id_request',$ID_REQUEST)->get();
									foreach ($RIM as $key => $value) {
										$Berat[] = $value->weight;
									}
									if(!empty($Berat)){
									$DataAgent  = DB::table('user_detail')
													->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
													->where('vehicles_size.size','>',array_sum($Berat))
													->where('user_detail.id_user','!=',$um->id)->get();
									}else{
									$DataAgent  = DB::table('user_detail')
													->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
													->where('user_detail.id_user','!=',$um->id)
													->get();
									}
									$lat1  = $rm->latitude_from;
									$lng1  = $rm->longitude_from;
									$from  = $rm->desc_from;
									$to    = $rm->desc_to;
									$title = $rm->title;
									foreach ($DataAgent as $key => $value) {
										$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
										if($jarak <= 10){
											Nexmo::SendNotifToOther($value->id_user,$from,$to,$PRICE,$title,$ID_REQUEST);
										}
									}
								}
							}
						}
					}
				}
			}
		}

	}

	// public function getWinner()
	// {
	// 	$RM = RequestModel::where('expire',0)
	// 		->where('close_date','<',date('Y-m-d H:i').':00')
	// 		->where('status','0')
	// 		->get();
	// 		foreach ($RM as $a => $b) {
	// 				$ID_REQUEST[] = $b->id;
	// 		}
	// 	$now = Date('Y-m-d H:i').':00';
	// 	if(!empty($ID_REQUEST)){
	// 		foreach ($ID_REQUEST as $s => $rec) {
	// 					$Bid = BidModel::orderBy('price','asc')
	// 							->orderBy('created_at','asc')
	// 							->where('id_request',$rec)
	// 							->where('status',0)
	// 							->where('sent',1)
	// 							->where('reply',0)
	// 							->where('ignore',0)
	// 							->first();
	// 					if(!empty($Bid)){
	// 						if(($Bid->finish_time < $now) && ($Bid->reply == 0) && $Bid->ignore == 0){
							
	// 							DB::table('bid')->where('id',$Bid->id)->update(array('ignore' => 1));
	// 							$Bid = BidModel::orderBy('price','asc')
	// 									->orderBy('created_at','asc')
	// 									->where('id_request',$rec)
	// 									->where('status',0)
	// 									->where('sent',0)
	// 									->where('reply',0)
	// 									->first();
	// 							if(!empty($Bid)){
	// 								// Nexmo::SendWinner($Bid->id_user,$rec);
	// 								Helper::MessageSent($Bid->id);
	// 							}
	// 						}
	// 					}else{
	// 						$Bid = BidModel::orderBy('price','asc')
	// 								->orderBy('created_at','asc')
	// 								->where('id_request',$rec)
	// 								->where('status',0)
	// 								->where('sent',0)
	// 								->where('reply',0)
	// 								->first();
	// 						if(!empty($Bid)){
	// 							// Nexmo::SendWinner($Bid->id_user,$rec);
	// 							Helper::MessageSent($Bid->id);			
	// 						}
	// 					}
	// 			Helper::ExpireRequest($rec);
	// 		}
	// 	}
	// }

	// public function 

 

	public function not()
	{
		return View::make('front.404');
	}
}