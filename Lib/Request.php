<?php

namespace Lib;

class Request {
	private $server;
	private $request;
	private $method;
	private $path_arr;
	private $query_arr;


	public function __construct() {
		$this->server = $_SERVER;
		$this->request = $_REQUEST;
		$this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
		
		$this->path_arr = $this->getPathArray();
		$this->query_arr = $this->getQuery();
	}
	
	/**
	 * For POST requests: return it or key value.
	 * 
	 * @param string $key
	 * @return array|string
	 */
	public function getRequest($key = '') {
		if (empty($key)) {
			return $this->request;
		}
		
		return isset($this->request[$key]) ? $this->request[$key] : '';
	}
	
	public function getPathArr() {
		return $this->path_arr;
	}
	
	public function getFirst() {
		// console usage
		if (isset($this->server['argc'])) {
			if ($this->server['argc'] > 1) {
				return $this->server['argv'][1];
			}
			
			return '';
		}
		
		return $this->path_arr[0]; // browser usage
	}
	
	public function getSecond() {
		// console usage
		if (isset($this->server['argc'])) {
			if ($this->server['argc'] > 2) {
				return $this->server['argv'][2];
			}
			
			return '';
		}
		
		return empty($this->path_arr[1]) ? '' : $this->path_arr[1]; // browser usage
	}
	
	public function getThird() {
		// console usage
		if (isset($this->server['argc'])) {
			if ($this->server['argc'] > 3) {
				return $this->server['argv'][3];
			}
			
			return '';
		}
		
		return empty($this->path_arr[2]) ? '' : $this->path_arr[2];
	}
	
	public function getQueryArr() {
		return $this->query_arr;
	}
	
	public function getQueryValue($key) {
		return isset($this->query_arr[$key]) ? $this->query_arr[$key] : '';
	}
	
//	private function getPathArray() {
//		return explode('/', $this->getPath());
//	}
	
	private function getPathArray() {
		// well, the main problem with 'BASE' or 'REDIRECT_BASE' is that they are not always present;
		// their appearance can not be predicted (depends on Apache configuration etc.)
//		$dr = isset($this->server['REDIRECT_BASE']) ? $this->server['REDIRECT_BASE'] : '';
		$dr = dirname($this->server['PHP_SELF']); // stable variant, working everywhere
		$len = strlen($dr);
		
		$redir = isset($this->server['REQUEST_URI']) ? $this->server['REQUEST_URI'] : '';
		
		$path = substr($redir, $len);
		
		preg_match("/([[:alnum:]]+)\/?([[:alnum:]]+)?\/?([[:alnum:]]+)?/i", $path, $m);
		
		if (empty($m)) {
			return ['', ];
		}
		
		array_shift($m);
		
		return $m;
	}
	
	private function getQuery() {
		$query_string = isset($this->server['QUERY_STRING']) ? $this->server['QUERY_STRING'] : '';
		
		if (empty($query_string)) {
			return [];
		}
		
		parse_str($query_string, $query);
		
		return $query;
	}
	
}
