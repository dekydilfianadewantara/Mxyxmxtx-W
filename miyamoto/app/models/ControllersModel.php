<?php
class ControllersModel extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $softDelete = true;
	protected $table = 'controllers';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
}
