<?php

namespace application\fenix;


class Auth {
	
	/**
	 * Instance of Curl class
	 * @var Curl
	 */
	private $curl;
	/**
	 * Class specific URL.
	 * @var string
	 */
	private $url = 'http://www.fenixtours.gr/webservice/auth';
	/**
	 * Decoded result of run()
	 * @var stdObject
	 */
	private $auth;


	public function __construct(Curl $curl) {
        $this->curl = $curl;
    }
    
	public function run() {
		$this->curl->setUrl($this->url);
		
		$this->curl->setFenixPost([
			"method" => "auth",
			"username" => USERNAME,
			"password" => PASSWORD,
		]);
		
		$res = $this->curl->curlPost();
		$this->auth = \json_decode($res); // stdObject
		
		if (isset($this->auth) && $this->auth->status === 'Success') {
			return true;
		} else {
			return false;
		}
	}
	
	public function setUrl($param) {
		$this->url = $param;
	}
       
	public function getAuthKey() {
		return $this->auth->auth_key;
	}
	
}
