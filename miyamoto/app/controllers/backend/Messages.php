<?php

class Messages extends BaseController {
	public $limit = 10;
	public $admin;

	public function __construct()
	{
		$this->admin = Session::get('admin');
	}

	public function getIndex()
	{
		View::share('title','Messages');
		View::share('path','Index');
		$data['limit'] = $this->limit;
		$data['messages']  = MessageModel::orderBy('id','desc')->paginate($this->limit);
		return View::make('backend.messages.index',$data);
	}

	public function getDetail($id)
	{
		$data['messages'] = MessageModel::find($id);
		return View::make('backend.messages.modal_message_info',$data);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDelete($id)
	{
		$bm = MessageModel::find($id);
		$bm->delete();
		return Redirect::to('admin/messages')->with('messages','Data has been deleted');
	}
}
