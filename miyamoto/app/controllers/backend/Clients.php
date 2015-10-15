<?php

class Clients extends BaseController {
	public $limit = 10;
	public $admin;

	public function __construct()
	{
		$this->admin = Session::get('admin');
	}

	public function getIndex()
	{
		View::share('title','Clients');
		View::share('path','Index');
		$data['limit'] = $this->limit;
		$cari          = Input::get('search');

		$qr	= ClientsModel::orderBy('id','desc');
		if($cari){
			$qr = $qr->where('name','LIKE',"%$cari%");
		}
		$qr = $qr->paginate($this->limit);
		
		$data['clients'] = $qr;
		return View::make('backend.clients.index',$data);
	}

	public function getModalDetailClient($id)
	{
		View::share('title','Clients');
		View::share('path','Detail');

		$data['clients'] = ClientsModel::find($id);
		return View::make('backend.clients.modal_detail_client',$data);
	}
	
	public function getCreate()
	{
		View::share('path','Create');
		View::share('title','Clients');
		return View::make('backend.clients.create');
	}

	public function postInsert()
	{
		$rules = array(
			'name'         => 'required',
			'email'        => 'required|unique:clients,email',
			'phone_number' => 'required',
			'address'      => 'required'
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/clients/create')->withErrors($valid)->withInput();
		}else{
			$cm          = new ClientsModel;
			$cm->name    = Input::get('name');
			$cm->email   = Input::get('email');
			$cm->phone   = Input::get('phone_number');
			$cm->address = Input::get('address');
			$cm->save();
			return Redirect::to('admin/clients')->with('clients','Data has been added');
		}
	}

	public function getEdit($id)
	{
		View::share('path','Edit');
		View::share('title','Clients');

		$data['clients'] = ClientsModel::find($id);
		return View::make('backend.clients.edit',$data);
	}

	public function postUpdate($id)
	{
		$rules = array(
			'name'         => 'required',
			'email'        => 'required|unique:clients,email,'.$id,
			'phone_number' => 'required',
			'address'      => 'required'
		);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/clients/edit/'.$id)->withErrors($valid);
		}else{
			$cm          = ClientsModel::find($id);
			$cm->name    = Input::get('name');
			$cm->email   = Input::get('email');
			$cm->phone   = Input::get('phone_number');
			$cm->address = Input::get('address');
			$cm->save();
			return Redirect::to('admin/clients')->with('clients','Data has been updated'); 
		}
	}

	public function getDelete($id)
	{
		$cm = ClientsModel::find($id);
		$om = OrdersModel::where('id_client',$id)->count();
		if($om > 0){
			return Redirect::to('admin/clients')->with('clients_alert','Data is used');
		}else{
			$cm->delete();
			return Redirect::to('admin/clients')->with('clients','Data has been deleted');
		}
	}
}