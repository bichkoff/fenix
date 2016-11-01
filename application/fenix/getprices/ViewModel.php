<?php
/**
 * 
 * Fenix project.
 * 
 * @author Jvb 26 Jan 2016
 * 
 */

namespace application\fenix\getprices;


class ViewModel {
	
	private $model;
	private $request;


	public function __construct($model, \Lib\Request $request) {
		$this->model = $model;
		$this->request = $request;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function getRequest() {
		return $this->request;
	}
	
}
