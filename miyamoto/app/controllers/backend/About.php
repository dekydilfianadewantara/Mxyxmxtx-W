<?php

class About extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function getIndex()
	{
		View::share('title','About');
		View::share('path','Index');
		$data['about'] = AboutModel::find(1);
		return View::make('backend.about.index',$data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postIndex()
	{
		$rules = array(
			'name'                   => 'required',
			'email'                  => 'required',
			'address'                => 'required',
			'facebook'               => 'required',
			'twitter'                => 'required',
			'instagram'              => 'required',
			'bbm'                    => 'required',
			'phone_number'           => 'required|numeric',
			'web_keywords'           => 'required',
			'home_description'       => 'required',
			'blog_description'       => 'required',
			'how_to_buy_description' => 'required',
			'order_description'      => 'required',
			'contact_description'    => 'required',
			'product_description'    => 'required'
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/about')->withErrors($valid)->withInput();
		}else{
			$picture                    = Input::get('picture');
			$cm                         = AboutModel::find(1);
			$cm->name                   = Input::get('name');
			$cm->email                  = Input::get('email');
			$cm->web_keywords           = Input::get('web_keywords');
			$cm->address                = Input::get('address');
			$cm->facebook               = Input::get('facebook');
			$cm->twitter                = Input::get('twitter');
			$cm->instagram              = Input::get('instagram');
			$cm->bbm                    = Input::get('bbm');
			$cm->phone                  = Input::get('phone_number');
			$cm->home_description       = Input::get('home_description');
			$cm->blog_description       = Input::get('blog_description');
			$cm->how_to_buy_description = Input::get('how_to_buy_description');
			$cm->order_description      = Input::get('order_description');
			$cm->contact_description    = Input::get('contact_description');
			$cm->product_description    = Input::get('product_description');
			$cm->save();
			return Redirect::to('admin/about')->with('about','Data has been updated');
		}
	}
}
