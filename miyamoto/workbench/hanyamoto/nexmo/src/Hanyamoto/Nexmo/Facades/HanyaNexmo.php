<?php namespace Hanyamoto\Nexmo\Facades;

use Illuminate\Support\Facades\Facade;
class HanyaNexmo extends Facade{
	protected static function getFacadeAccessor() { return 'hanyanexmo'; }
}