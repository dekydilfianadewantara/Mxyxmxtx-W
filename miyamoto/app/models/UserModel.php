<?php

class UserModel extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	//protected $softDelete = true;
	protected $table = 'users';
 
	public function group()
	{
		return $this->belongsTo('UsersGroupModel','level');
	}
}
