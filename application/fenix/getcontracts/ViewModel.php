<?php
/**
 * Prices retrieval
 * Fenix project.
 * 
 * @author Jvb 26 Jan 2016
 * 
 */

namespace application\fenix\getcontracts;


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
	
	/**
	 * Get contracts by Hotel id.
	 * 
	 * @param int $hotel_id
	 * @return boolean|array
	 */
	public function getContracts($hotel_id) {
		$curl = new \application\fenix\Curl;
		$auth = new \application\fenix\Auth($curl);
		
		if (! $auth->run()) {
			return false;
		}
		
		$cl = __NAMESPACE__ .'\\Contracts';
		$instance = new $cl($curl, $auth);	
		$instance->setHotelId($hotel_id);
		
		$contracts = $instance->run();
		
		return $contracts === false ? false : $contracts;
	}
	
}
