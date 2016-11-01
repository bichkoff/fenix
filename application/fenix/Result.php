<?php

namespace application\fenix;


class Result {
	
	/**
	 * Instance of Auth class
	 * @var Auth
	 */
	private $objs = [];


	public function __construct() {
    }
    
	public function add($instance) {
		$refl = new \ReflectionClass($instance);
		
		$cl = $refl->getName(); // class name
		$clnons = \substr(\strrchr($cl, '\\'), 1); // strip namespace
		//$props = $refl->getProperties(); // class properties
		
		$this->objs[$clnons] = $instance;
	}
	
	public function getObjs() {
		return $this->objs;
	}
	
	public function getObj($name) {
		return isset($this->objs[$name]) ? $this->objs[$name] : null;
	}
	
	public function processStd($clnons, $instance) {
		$entity = '\entity\\fenix\\'. $clnons;
		
		new $entity();
	}
       
}
