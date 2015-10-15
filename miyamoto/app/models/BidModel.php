<?php

class BidModel extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bid';
	
	public function about()
	{
		return $this->belongsTo('UserModel','id_user');
	}
	public function request()
	{
		return $this->belongsTo('RequestModel','id_request');
	}
}
