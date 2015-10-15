<?php

class Permissions extends BaseController {
	public $limit = 10;
	public $admin;
	
	public function __construct()
	{
		$this->admin = Session::get('admin');
	}
	public function getIndex()
	{
		View::share('title','Permission');
		View::share('path','index');
		View::share('user_group','User Group');

		$data['group_session'] = (Session::get('group')) ? Session::get('group') : '';
		$data['group']         = UsersGroupModel::where('id','!=',1)->orderBy('id','desc')->get();
		$data['controllers']   = ControllersModel::orderBy('id','asc')->get();
		return View::make('backend.permission.index',$data);
		
	}

	public function getEdit($id){
		View::share('title','Permission');
		View::share('path','Edit');
		$data['parent']     = ControllersModel::where('id_parent',null)->where('url',null)->orderBy('id','desc')->get();
		$data['permission'] = ControllersModel::find($id);
		return View::make('backend.permission.edit',$data);
	}

	public function postUpdate($id){
		$rules = array(
			'name'        => 'required',
			'description' => 'required'
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/permission/'.$id.'/edit')->withErrors($valid);
		}else{
			$parent = Input::get('parent');
			$path   = Input::get('path');
			$icon   = Input::get('icon');
			$cm     = ControllersModel::find($id);
			if(!empty($parent)){
				$cm->id_parent   = $parent;
			}
			$cm->name        = Input::get('name');
			if(!empty($path)){
				$cm->url         = $path;
			}
			$cm->description = Input::get('description');
			if(!empty($icon)){
				$cm->icon        = $icon;
			}
			$cm->save();
			return Redirect::to('admin/permission')->with('permission','Data has been updated');
		}
	}
	public function getCreate(){
		View::share('title','Permission');
		View::share('path','Create');
		$data['parent'] = ControllersModel::where('id_parent',null)->where('url',null)->orderBy('id','desc')->get();
		return View::make('backend.permission.create',$data);
	}
	public function postStore(){
		$rules = array(
			'name'        => 'required',
			'description' => 'required'
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/permission/create')->withErrors($valid)->withInput();
		}else{
			$parent = Input::get('parent');
			$path   = Input::get('path');
			$icon   = Input::get('icon');
			$cm     = new ControllersModel;
			if(!empty($parent)){
				$cm->id_parent   = $parent;
			}
			$cm->name        = Input::get('name');
			if(!empty($path)){
				$cm->url         = $path;
			}
			$cm->description = Input::get('description');
			if(!empty($icon)){
				$cm->icon        = $icon;
			}
			$cm->save();
			return Redirect::to('admin/permission')->with('permission','Data has been added');
		}
	}
	public function postSave(){
		$controllerInput = Input::get('controller');
		$group           = Session::get('group');
		AccessModel::where('id_group',$group)->delete();
		if(!empty($controllerInput))
		{
			foreach ($controllerInput as $key => $row) {

					$am                	= new AccessModel;
					$am->id_group      	= $group;
					$am->id_controller 	= $row;
					$am->save();

			}
		}
		return Redirect::to('admin/permission')->with('permission','Data has been updated');
	}

	public function postSubmit(){
		$group = Input::get('group');
		if($group){	
			Session::put('group',$group);
		}else{
			Session::put('group','');
		}
		return Redirect::to('admin/permission');		
	}
	
	public function getDelete($id)
	{
		$am = ControllersModel::find($id);
		if(!empty($am)){
			if(empty($am->id_parent) && empty($am->url)){
				$cm = ControllersModel::where('id_parent',$am->id)->get();
				if(!empty($cm)){
					foreach ($cm as $key => $value) {
						$parents[] = $value->id;
					}
				}
				$parents[] = $id;
			}else{
				$parents[] = $id;
			}

			if(!empty($parents)){
				foreach ($parents as $row) {
					AccessModel::where('id_controller',$row)->delete();
				}
				foreach ($parents as $key) {
					ControllersModel::find($key)->delete();
				}
			}
			return Redirect::to('admin/permission')->with('permission','Data has been deleted');
		}else{
			return Redirect::to('admin/permission')->with('permission_errors','Data is not available');
		}
	}
}