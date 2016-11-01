<?php
/**
 * Get all contracts for a Hotel. 
 * NB: One hotel only!
 * Fenix project.
 * 
 * @author Jvb 02 Feb 2016
 * 
 */

namespace application\fenix\getcontracts;

use application\fenix\IFFunc;
use application\fenix\Curl;
use application\fenix\Auth;


class Contracts implements IFFunc {
		
	/**
	 * Instance of Curl class
	 * @var Curl
	 */
	private $curl;
	/**
	 * Class specific URL.
	 * @var string
	 */
	private $url = 'http://www.fenixtours.gr/webservice/call';
	/**
	 * Instance of
	 * @var Auth
	 */
	private $auth;
	/**
	 * This class name w/o namespace
	 * @var string
	 */
	private $cls;
	/**
	 * Hotel id
	 * @var int
	 */
	private $hotel_id;
	/**
	 * Instances of entity\fenix\Countries
	 * @var array
	 */
	private $table = [];
	/**
	 * Boolean: should we look for an existing record on SQL insertion?
	 * @var int
	 */
	private $check_primary = 0;


	public function __construct(Curl $curl, Auth $auth) {
        $this->curl		= $curl;
		$this->auth		= $auth;
		$this->cls		= \substr(\strrchr(__CLASS__, '\\'), 1); // strip namespace;
    }
    
	public function run() {
		$this->curl->setUrl($this->url);
		
		$this->curl->setFenixPost([
			"method" => "get_hotel_contracts",
			"auth_key" => $this->auth->getAuthKey(),
			"hotel_id" => $this->hotel_id,
		]);
		
		$res = $this->curl->curlPost();
		$std = \json_decode($res); // stdObject
		
		if (isset($std) && $std->status === 'Success') {
			return $this->processStd($std);
		} else {
			return false;
		}
	}
	
	public function setUrl($param) {
		$this->url = $param;
	}
       
	public function setHotelId($param) {
		$this->hotel_id = $param;
	}
       
	public function setTable($param) {
		return $this->table = $param;
	}
       
	public function getTable() {
		return $this->table;
	}
       
	public function getCls() {
		return $this->cls;
	}
       
	public function setCheckPrimary($param) {
		$this->check_primary = $param;
		
		return $this;
	}
       
	public function getCheckPrimary() {
		return $this->check_primary;
	}
       
	public function getClass() {
		return __CLASS__;
	}
       
	public function processStd($std) {
		return isset($this->hotel_id) && isset($std->root->data->{$this->hotel_id}) ? $std->root->data->{$this->hotel_id} : [];
	}
	
}
