<?php

class UserDetail extends Eloquent {

    protected $table = 'user_detail';

    protected $dates = ['created_at', 'updated_at', 'disabled_at'];

}
