<?php
/**
 * Get all contracts for a Hotel. 
 * NB: One hotel only!
 * Fenix project.
 * 
 * @author Jvb 02 Feb 2016
 * 
 */

namespace application\fenix\getprices;

use application\fenix\IFFunc;
use application\fenix\Curl;
use application\fenix\Auth;


class Prices implements IFFunc {
		
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
	 * Contract id, that is necessary for prices retrieval
	 * @var int
	 */
	private $contract_id;
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
			"method"			=> "get_pricetable_os",
			"auth_key"			=> $this->auth->getAuthKey(),
			"hotel_id"			=> $this->hotel_id,
			"hotel_contract_id" => $this->contract_id,
		]);
		
		$res = $this->curl->curlPost();
		$std = \json_decode($res); // stdObject
		
		if (isset($std) && $std->status === 'Success') {
			return $std->root;
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
       
	public function setContractId($param) {
		$this->contract_id = $param;
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
		
	}
	
}
