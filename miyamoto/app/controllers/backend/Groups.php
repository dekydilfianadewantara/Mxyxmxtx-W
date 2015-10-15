<?php

class Groups extends BaseController {
	public $limit = 10;
	public $admin;
	
	public function __construct()
	{
		$this->admin = Session::get('admin');
	}
	public function getIndex()
	{
		View::share('title','User Group');
		View::share('path','index');
		$data['limit'] = $this->limit;
		$data['group'] = UsersGroupModel::orderBy('id','desc')
						->paginate($this->limit);
		return View::make('backend.group.index',$data);
	}

	public function getCreate()
	{
		View::share('title','User Group');
		View::share('path','Create');
		return View::make('backend.group.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
		$rules = array(
			'name'    => 'required'
			);

		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/group/create')->withErrors($valid)->withInput();
		}else{
			$ug              = new UsersGroupModel;
			$ug->group_name  = ucwords(Input::get('name'));
			$ug->save();

			return Redirect::to('admin/group')->with('groups','Data has been created');
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getEdit($id)
	{
		View::share('title','User Group');
		View::share('path','Edit');
		$data['group'] = UsersGroupModel::find($id);
		return View::make('backend.group.edit',$data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($id)
	{
		$rules = array(
			'name'    => 'required'
			);
		
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/group/edit/'.$id)->withErrors($valid);
		}else{
			$ug              = UsersGroupModel::find($id);
			$ug->group_name  = ucwords(Input::get('name'));
			$ug->save();
			return Redirect::to('admin/group')->with('groups','Data has been updated');	
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDelete($id)
	{
		$ug = UsersGroupModel::find($id);
		$um = UserModel::where('level',$id)->count();
		if($um > 0){
			return Redirect::to('admin/group')->with('groups_alert','Data is used');
		}else{
			if($ug->id == 1){
				return Redirect::to('admin/group')->with('groups_alert','Administrator can not be deleted');
			}else{
				$ug->delete();
				return Redirect::to('admin/group')->with('groups','Data has been deleted');
			}
		}
	}
	
	public function getActive($id)
	{
		$tm = UsersGroupModel::find($id);
		$tm->for_register = 1;
		$tm->save();
		return Redirect::to('admin/group')->with('groups','Data has been activated'); 
	}

	public function getNoactive($id)
	{
		$tm = UsersGroupModel::find($id);
		$tm->for_register = 0;
		$tm->save();
		return Redirect::to('admin/group')->with('groups','Data has been disactivated'); 
	}

}