<?php namespace Nuli\Nulinexmo;
use Illuminate\Support\Facades\Config as Config;
use Illuminate\Http\Response;

class Nulinexmo {
	
	// Nexmo account credentials
	// private $nx_key = Config::get('nulinexmo::key');
	// private $nx_secret = Config::get('nulinexmo::secret');

	/**
	 * @var string Nexmo server URI
	 *
	 * We're sticking with the JSON interface here since json
	 * parsing is built into PHP and requires no extensions.
	 * This will also keep any debugging to a minimum due to
	 * not worrying about which parser is being used.
	 */
	var $nx_uri = 'https://rest.nexmo.com/sms/json';

	
	/**
	 * @var array The most recent parsed Nexmo response.
	 */
	private $nexmo_response = '';
	

	/**
	 * @var bool If recieved an inbound message
	 */
	var $inbound_message = false;


	// Current message
	public $to = '';
	public $from = '';
	public $text = '';
	public $network = '';
	public $message_id = '';

	// A few options
	public $ssl_verify = false; // Verify Nexmo SSL before sending any message

	function sendText ( $to, $from, $message, $unicode=null ) {
	
		// Making sure strings are UTF-8 encoded
		if ( !is_numeric($from) && !mb_check_encoding($from, 'UTF-8') ) {
			trigger_error('$from needs to be a valid UTF-8 encoded string');
			return false;
		}

		if ( !mb_check_encoding($message, 'UTF-8') ) {
			trigger_error('$message needs to be a valid UTF-8 encoded string');
			return false;
		}
		
		if ($unicode === null) {
			$containsUnicode = max(array_map('ord', str_split($message))) > 127;
		} else {
			$containsUnicode = (bool)$unicode;
		}
		
		// Make sure $from is valid
		$from = $this->validateOriginator($from);

		// URL Encode
		$from = urlencode( $from );
		$message = urlencode( $message );
		
		// Send away!
		$post = array(
			'from' => $from,
			'to' => $to,
			'text' => $message,
			'type' => $containsUnicode ? 'unicode' : 'text'
		);
		return $this->sendRequest ( $post );
		
	}
	
 
	
	/**
	 * Prepare and send a new message.
	 */
	private function sendRequest ( $data ) {
		// Build the post data
		$data = array_merge($data, array('username' => Config::get('nexmo.key'), 'password' => Config::get('nexmo.secret')));
		$post = '';
		foreach($data as $k => $v){
			$post .= "&$k=$v";
		}

		// If available, use CURL
		if (function_exists('curl_version')) {

			$to_nexmo = curl_init( $this->nx_uri );
			curl_setopt( $to_nexmo, CURLOPT_POST, true );
			curl_setopt( $to_nexmo, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $to_nexmo, CURLOPT_POSTFIELDS, $post );

			if (!$this->ssl_verify) {
				curl_setopt( $to_nexmo, CURLOPT_SSL_VERIFYPEER, false);
			}

			$from_nexmo = curl_exec( $to_nexmo );
			curl_close ( $to_nexmo );

		} elseif (ini_get('allow_url_fopen')) {
			// No CURL available so try the awesome file_get_contents

			$opts = array('http' =>
				array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => $post
				)
			);
			$context = stream_context_create($opts);
			$from_nexmo = file_get_contents($this->nx_uri, false, $context);

		} else {
			// No way of sending a HTTP post :(
			return false;
		}
	 
	}
	
	private function validateOriginator($inp){
		// Remove any invalid characters
		$ret = preg_replace('/[^a-zA-Z0-9]/', '', (string)$inp);

		if(preg_match('/[a-zA-Z]/', $inp)){

			// Alphanumeric format so make sure it's < 11 chars
			$ret = substr($ret, 0, 11);

		} else {

			// Numerical, remove any prepending '00'
			if(substr($ret, 0, 2) == '00'){
				$ret = substr($ret, 2);
				$ret = substr($ret, 0, 15);
			}
		}
		
		return (string)$ret;
	}

}