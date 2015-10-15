<?php

class Profile extends BaseController {
	public $admin;

	public function __construct()
	{
		$this->admin = Session::get('admin');
	}

	public function getIndex()
	{
		View::share('title','Profile');
		View::share('path','Edit');
		$data['profile'] = UserModel::find($this->admin['id']);
		return View::make('backend.profile.index',$data);
	}

	public function postUpdate()
	{
		$pass = Input::get('password');
		$rules['name']         = 'required';
		$rules['phone_number'] = 'required|unique:users,phone,'.$this->admin['id'];
		$rules['address']      = 'required';
		if(!empty($pass)){
			$rules['password']        = 'required|min:5';
			$rules['retype_password'] = 'required|same:password';
		}
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/profile')->withErrors($valid);
		}else{
			$cm            = UserModel::find($this->admin['id']);
			$cm->name      = Input::get('name');
			$cm->address   = Input::get('address');
			$cm->phone     = Input::get('phone_number');
			if(!empty($pass)){
				$cm->password     = md5($pass.'mhayamoto');
			}
			$cm->save();
			return Redirect::to('admin/profile')->with('users','Data has been updated'); 
		}
	}

	public function getNotif()
	{
		$rm = RequestModel::where('id_user',$this->admin['id'])->get();
		if(count($rm) > 0){
			foreach ($rm as $i => $r) {
				$req[] = $r->id;
			}
			// $BM = BidModel::whereIn('id_request',$req)->where('status',0)->orderBy('id','desc')->count();
			// if(!empty($BM)){
			$bidnotif = DB::table('bid')->whereIn('id_request',$req)->where('bid_notif',0)->update(array('bid_notif'=>1));
			// }
		}
		$bd = DB::table('bid')->where('id_user',$this->admin['id'])->where('notif',0)->update(array('notif'=>1));
		return (isset($bidnotif))? $bidnotif+$bd:$bd;
		// $Bid = BidModel::where('id_user',$this->admin['id'])->where('status',1)->where('notif',0)->count();
		// return (isset($BM))?$BM+$Bid:$Bid;

	}
}