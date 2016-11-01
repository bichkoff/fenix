<?php

/**
 * MVC project.
 * Configuration routes set. 
 * Can be anything: db, file, yml etc. At present it is a simple array $this->routeset.
 * 
 *
 * @author Jvb 08 July 2015
 * @version 0.1 08.07.2015
 */

namespace Lib;


class RouteSet {
	
	/**
	 * Configuration routes array of a special structure
	 * @var array
	 */
    private $routeset;
	
	
	public function __construct() {
		$this->setRouteSet();
	}
	
	protected function getConfigurationRoute($key) {
		$arr = $this->getRouteData($key);
		
		// Configuration route was not found
		if ($arr === false) {
			return false;
		}
		
		return new ConfigurationRoute($arr);
	}
	
	private function getRouteData($key) {
		return isset($this->routeset[$key]) ? $this->routeset[$key] : false;
	}
	
	/**
	 * Manual route setting. Can be switched to db, separate file etc.
	 * Array of first REQUEST parts has the structure:
	 * 0 - first part (redundant but used for uniformity),
	 * 1 - second part,
	 * 2 - first part to serve (application's namespace first part),
	 * 3 - second part to serve (application's namespace second part)
	 */
	private function setRouteSet() {
		$this->routeset = [
			''			=> ['', '', 'fenix', ''], // root route to serve
			'route01'	=> ['route01', 'route11', 'users', 'show'],
		];
	}

}
