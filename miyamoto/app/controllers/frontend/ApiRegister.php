<?php

class ApiRegister extends BaseController {
	// public $limit = 9;
	// public $ip;

	// public function __construct()
	// {
	// 	 $this->ip = $_SERVER['REMOTE_ADDR'];
	// }
	public function postIndex()
	{
		$full_name = Input::get('full_name');
		$email     = Input::get('email');
		$phone     = Input::get('phone');
		$address   = Input::get('address');
		$code_area = Input::get('code_area');
		$level     = Input::get('level');
		$password  = md5(Input::get('password').'mhayamoto');

        if(isset($full_name) && isset($email) && isset($phone) && isset($address) && isset($code_area) && isset($level) && isset($password)){
			
			
			$CheckEmail = UserModel::where('email',$email)->count();
			if(!empty($CheckEmail)){
					$data['success'] = FALSE;
					$data['message'] = 'The email address has already been taken';
					return Response::json($data);
			}else{
				$phone_number = $code_area.$phone;
				$CheckNo = UserModel::where('phone',$phone_number)->count();
				if(!empty($CheckNo)){
					$data['success'] = FALSE;
					$data['message'] = 'The phone number has already been taken';
					return  Response::json($data);
				}else{
					$UG = UsersGroupModel::find($level);
					if($UG->is_detail){
						$data['success'] = TRUE;
						$data['data']    = array(
							'full_name' => $full_name,
							'email'     => $email,
							'phone'     => $phone_number,
							'address'   => $address,
							'level'     => $level,
							'password'  => $password
				  		);
				  		$data['type'] = 2; 
				  		$data['description'] = 'Continue to second registration (only for worker)';
				  		return  Response::json($data);
					}else{
						$UM           = new UserModel;
						$UM->name     = $full_name;
						$UM->email    = $email;
						$UM->address  = $address;
						$UM->phone    = $phone_number;
						$UM->level    = $level;
						$UM->password = $password;
						$UM->save();

						$data['success'] = TRUE;
						$data['data']    = array(
							'full_name' => $full_name,
							'email'     => $email,
							'phone'     => $phone_number,
							'address'   => $address
							// 'level'     => $level,
							// 'password'  => $password
				  		);
				  		$data['type'] = 1;
				  		$data['description'] = 'Register complete(only for hire)';
				  		return  Response::json($data);
					}
				}
			}
        }else{
        	$data['success'] = FALSE;
			$data['message'] = 'Required parameters is missing!';
			return  Response::json($data);
        }
	}

	public function postSecond()
	{
		$full_name = Input::get('full_name');
		$email     = Input::get('email');
		$phone     = Input::get('phone');
		$address   = Input::get('address');
		$code_area = Input::get('code_area');
		$level     = Input::get('level');
		$password  = md5(Input::get('password').'mhayamoto');

		$charge  = Input::get('charge_per_kilometer');
		$size    = Input::get('vehicles_size');
		$payment = Input::get('payment_method');
		$lat     = Input::get('latitude');
		$lng     = Input::get('longitude');


        if(isset($full_name) && isset($email) && isset($phone) && isset($address)  && isset($code_area)  && isset($level)  && isset($password) && isset($charge) && isset($size) && isset($payment) && isset($lat) && isset($lng)){
				$phone_number = $code_area.$phone;
				$UM           = new UserModel;
				$UM->name     = $full_name;
				$UM->email    = $email;
				$UM->address  = $address;
				$UM->phone    = $phone_number;
				$UM->level    = $level;
				$UM->password = $password;
				$UM->save();

				$UD = new UsersDetailModel;
				$UD->id_user              = $UM->id;
				$UD->charge_per_kilometer = $charge;
				$UD->id_vehicles_size     = $size;
				$UD->id_payment           = $payment;
				$UD->latitude             = $lat;
				$UD->longitude            = $lng;
				$UD->save();

				$data['success'] = TRUE;
				$data['data']    = array(
					'full_name' => $full_name,
					'email'     => $email,
					'phone'     => $phone_number,
					'address'   => $address
					// 'level'     => $level,
					// 'password'  => $password
		  		);
		  		$data['description'] = 'Registration success';
		  		return  Response::json($data);
        }else{
        	$data['success'] = FALSE;
			$data['message'] = 'Required parameters is missing!';
			return  Response::json($data);
        }

	}

	public function postLogin()
	{
		$email    = Input::get('username');
		$password = Input::get('password');

			$um = UserModel::where('email',$email)
				  ->where('password',md5($password.'mhayamoto'))
				  ->where('deleted_at',null)
				  ->first();
			if(count($um) > 0)
			{
			  	if($um->is_active==1){
					$data['success'] = TRUE;
				  	$data['data'] = array(
						// 'id'         => $um->id,
						'name'       => $um->name,
						'level'      => $um->level,
						'group_name' => Permission::CheckGroupName($um->level)
						// 'created_at' => $um->created_at
				  		);
				  	// $data['login_status'] = 'logged_in';
		  			$data['description'] = 'Login Success';
			  		return Response::json($data);

				}else{
					$data['success'] = FALSE;
					$data['description'] = 'your account is not active';
			  		return Response::json($data);
				}
			}else{
				$data['success'] = FALSE;
				$data['description'] = 'your account is not valid';
		  		return Response::json($data);
			}
	}
	public function getCountry()
	{
		$data['success'] = TRUE;
		$lc = ListCountryModel::orderBy('id','asc')->get();
		foreach ($lc as $key => $val) {
			$data['data'][$key]['area_code']   =$val->country_areacode;
			$data['data'][$key]['country_name'] = $val->country_name;
		}
		// $data['description'] = ''
		return Response::json($data);
		// $data['group'] = 
	}

	public function getLevel()
	{
		$data['success'] = TRUE;
		$ugm = UsersGroupModel::where('for_register',1)->orderBy('id','desc')->get();
		foreach ($ugm as $key => $val) {
			$data['data'][$key]['level_id']   = $val->id;
			$data['data'][$key]['level_name'] = $val->group_name;
		}
		return Response::json($data);
	}

	public function getSize()
	{
		$data['success'] = TRUE;
		$vm = VehiclesModel::orderBy('id','asc')->where('is_active',1)->get();
		foreach ($vm as $key => $val) {
			$data['data'][$key]['id']   = $val->id;
			$data['data'][$key]['name'] = $val->name;
		}
		return Response::json($data);
	}

	public function getPayment()
	{
		$data['success'] = TRUE;
		$vm = PaymentsModel::orderBy('id','asc')->where('is_active',1)->get();
		foreach ($vm as $key => $val) {
			$data['data'][$key]['id_payment']   = $val->id;
			$data['data'][$key]['method'] = $val->name;
		}
		return Response::json($data);
	}
}