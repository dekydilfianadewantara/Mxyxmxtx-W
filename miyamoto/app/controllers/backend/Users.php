<?php

class Users extends BaseController {
	public $limit = 10;
	public $admin;

	public function __construct()
	{
		$this->admin = Session::get('admin');
	}

	public function getIndex()
	{
		View::share('title','Users');
		View::share('path','Index');
		$data['limit'] = $this->limit;
		$cari          = Input::get('search');

		$qr	= UserModel::orderBy('id','desc');
		if($cari){
			$qr = $qr->where('name','LIKE',"%$cari%");
		}
		$qr = $qr->paginate($this->limit);
		
		$data['users'] = $qr;
		return View::make('backend.users.index',$data);
	}

	public function getDeletedStaff()
	{
		View::share('title','Users');
		View::share('path','Deleted');
		$data['limit'] = $this->limit;
		$cari          = Input::get('search');

		$qr	= UserModel::onlyTrashed()->orderBy('id','desc');
		if($cari){
			$qr = $qr->where('name','LIKE',"%$cari%");
		}
		$qr = $qr->paginate($this->limit);
		
		$data['users'] = $qr;
		return View::make('backend.users.deleted_staff',$data);
	}

	public function getRestore($id)
	{
		$user = UserModel::onlyTrashed()->where('id',$id)->restore();
		return Redirect::to('admin/users/deleted-staff')->with('users','Data has been restored');
	}

	public function getModalDetailUser($id)
	{
		View::share('title','Users');
		View::share('path','Detail');

		$data['users'] = UserModel::find($id);
		return View::make('backend.users.modal_detail_user',$data);
	}
	
	public function getCreate()
	{
		View::share('path','Create');
		View::share('title','Users');
		$data['group'] = UsersGroupModel::all();
		return View::make('backend.users.create',$data);
	}

	public function postInsert()
	{
		$rules = array(
			'name'         => 'required',
			'email'        => 'required|unique:users,email',
			'phone_number' => 'required|numeric|unique:users,phone',
			'group'        => 'required',
			'address'      => 'required',
			'password'     => 'required|min:5',
			'retype_password' => 'required|same:password'
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/users/create')
				->withErrors($valid)
				->withInput(Input::except('password','retype_password'));
		}else{
			$cm            = new UserModel;
			$cm->name      = Input::get('name');
			$cm->email     = Input::get('email');
			$cm->phone     = Input::get('phone_number');
			$cm->address   = Input::get('address');
			$cm->level     = Input::get('group');
			$cm->is_active = Input::get('status');
			$cm->password  = md5(Input::get('password').'mhayamoto');
			$cm->save();
			return Redirect::to('admin/users')->with('users','Data has been added');
		}
	}

	public function getEdit($id)
	{
		View::share('path','Edit');
		View::share('title','Users');

		$data['users'] = UserModel::find($id);
		$data['group'] = UsersGroupModel::all();
		return View::make('backend.users.edit',$data);
	}

	public function postUpdate($id)
	{
		$pass = Input::get('password');
		$rules['name']         = 'required';
		$rules['email']        = 'required|unique:users,email,'.$id;
		$rules['phone_number'] = 'required|numeric|unique:users,phone,'.$id;
		$rules['group']        = 'required';
		$rules['address']      = 'required';
		if(!empty($pass)){
			$rules['password']        = 'required|min:5';
			$rules['retype_password'] = 'required|same:password';
		}
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/users/edit/'.$id)->withErrors($valid);
		}else{
			$cm            = UserModel::find($id);
			$cm->name      = Input::get('name');
			$cm->email     = Input::get('email');
			$cm->address   = Input::get('address');
			$cm->phone     = Input::get('phone_number');
			$cm->level     = Input::get('group');
			$cm->is_active = Input::get('status');
			if(!empty($pass)){
				$cm->password     = md5($pass.'mhayamoto');
			}
			$cm->save();
			return Redirect::to('admin/users/edit/'.$id)->with('users','Data has been updated'); 
		}
	}

	public function getDelete($id)
	{
		$cm = UserModel::find($id);
		$cm->delete();
		UsersDetailModel::where('id_user',$id)->delete();
		$rm = RequestModel::where('id_user',$id)->first();
		if(!empty($rm)){
			RequestItemModel::where('id_request',$rm->id)->delete();
			$rm->delete();
		}
		BidModel::where('id_user',$id)->delete();
		return Redirect::to('admin/users')->with('users','Data has been deleted');
	}
}