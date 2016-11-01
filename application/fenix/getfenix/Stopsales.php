<?php

namespace application\fenix\getfenix;

use application\fenix\IFFunc;
use application\fenix\Curl;
use application\fenix\Auth;


class Stopsales implements IFFunc {
		
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
	 * Instances of entity\fenix\Hotels
	 * @var array
	 */
	private $table = [];
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
	 * Full class name of the Table model
	 * @var string
	 */
	private $entity;
	/**
	 * Boolean: should we look for an existing record on SQL insertion?
	 * In this table duplicates exist! That is why it has special primary key.
	 * @var int
	 */
	private $check_primary = 0;


	public function __construct(Curl $curl, Auth $auth) {
        $this->curl		= $curl;
		$this->auth		= $auth;
		$this->cls		= \substr(\strrchr(__CLASS__, '\\'), 1); // strip namespace;
		$this->entity	= '\\entity\\fenix\\'. $this->cls;
    }
    
	public function run() {
		$this->curl->setUrl($this->url);
		
		$auth_key = $this->auth->getAuthKey();
			
		$this->curl->setFenixPost([
			"method" => "get_hotel_stop_sales",
			"auth_key" => $auth_key,
		]);
		
		$res = $this->curl->curlPost();
		$std = \json_decode($res); // stdObject
		
		if (isset($std) && $std->status === 'Success') {
			$this->processStd($std);
			
			return true;
		} else {
			return false;
		}
	}
	
	public function setUrl($param) {
		$this->url = $param;
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
       
	public function getEntity() {
		return $this->entity;
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
		foreach ($std->root->data as $hotel) {
			foreach ($hotel as $v) {				
				// add a row (object) to the table
				$obj = new $this->entity();

				$obj->setId($v->id)
					->setCompany(self::COMPANY)
					->setHid($v->hotel_id)
					->setRid($v->hotel_room_id)
					->setFrom($v->from_date)
					->setTo($v->to_date)
					->setCreated($v->creation_date);

				$this->table[] = $obj;
			}
		}
	}
	
}
