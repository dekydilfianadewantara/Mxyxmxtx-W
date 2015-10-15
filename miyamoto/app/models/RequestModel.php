<?php

class RequestModel extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'request';

	public function about()
	{
		return $this->belongsTo('UserModel','id_user');
	}
}
