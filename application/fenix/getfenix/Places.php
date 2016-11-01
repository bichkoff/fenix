<?php

namespace application\fenix\getfenix;

use application\fenix\IFFunc;
use application\fenix\Curl;
use application\fenix\Auth;


class Places implements IFFunc {
		
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
	 * Instances of entity\fenix\Places
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
			"method" => "get_areas",
			"auth_key" => $auth_key,
			"language" => 2,
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
		foreach ($std->root->data as $v) {
			$id_path = \trim($v->id_path, '/');
			$path_ids_arr = \explode('/', $id_path);
			
			$country= $path_ids_arr[0];
			$region = isset($path_ids_arr[1]) ? $path_ids_arr[1] : 0;
			$area	= isset($path_ids_arr[2]) ? $path_ids_arr[2] : 0;
			$town	= isset($path_ids_arr[3]) ? $path_ids_arr[3] : 0;
			
			$path_names_arr = \explode('^', $v->path);
			$path_names = \serialize($path_names_arr);
			
			// add a row (object) to the table
			$obj = new $this->entity();
			
			$obj->setId($v->area_id)
				->setCompany(self::COMPANY)
				->setName($v->name)
				->setType($v->type)
				->setCountry($country)
				->setRegion($region)
				->setArea($area)
				->setTown($town)
				->setPathNames($path_names);
			
			$this->table[] = $obj;
		}
	}
	
}
