<?php

namespace Lib;


class Session {
	
	private $session;


	public function __construct() {
		\session_start();
		
		$this->session =& $_SESSION;
	}
	
	/**
	 * For POST requests: return it or key value.
	 * 
	 * @param string $key
	 * @return array|string
	 */
	public function getValue($key) {
		return isset($this->session[$key]) ? $this->session[$key] : '';
	}
	
	/**
	 * Set session variable.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return Session
	 */
	public function setValue($key, $value) {
		$this->session[$key] = $value;
		
		return $this;
	}
	
}
