<?php

class UsersDetailModel extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_detail';
	public $timestamps = false;

	public function users()
	{
		return $this->belongsTo('UserModel','id_user');
	}

}
