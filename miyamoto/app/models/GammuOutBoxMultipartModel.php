<?php

class GammuOutBoxMultipartModel extends Eloquent{
	public $timestamps = false;
	protected $connection = 'mysql2';
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'outbox_multipart';

}
