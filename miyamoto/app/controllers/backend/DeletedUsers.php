<?php

class DeletedUsers extends BaseController {
	public $limit = 10;
	public $admin;

	public function __construct()
	{
		$this->admin = Session::get('admin');
	}

	public function getIndex()
	{
		View::share('title','Staff');
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
		return Redirect::to('admin/deleted-staff')->with('users','Data has been restored');
	}
}