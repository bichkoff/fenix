<?php

namespace Lib;


class ConfigurationRoute {
	
	/**
	 * REQUEST: first part
	 * @var string
	 */
	private $request_first;
	/**
	 * REQUEST: second part
	 * @var string
	 */
	private $request_second;
	/**
	 * application's namespace first part
	 * @var string
	 */
	private $first;
	/**
	 * application's namespace seconf part
	 * @var string
	 */
	private $second;
	
	
	public function __construct($params) {
		$this->request_first = $params[0];
		$this->request_second = $params[1];
		$this->first = $params[2];
		$this->second = $params[3];
	}
	
	public function getRequestFirst() {
		return $this->request_first;
	}
	
	public function getRequestSecond() {
		return $this->request_second;
	}
	
	public function getFirst() {
		return $this->first;
	}
	
	public function getSecond() {
		return $this->second;
	}
	
}
