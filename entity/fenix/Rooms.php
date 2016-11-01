<?php

namespace entity\fenix;


class Rooms implements ITable {
	
	private $id;
	private $company;
	private $hid;
	private $name;
	
	
	public function __construct() { }
	
	public function setId($param) {
		$this->id = $param;
		
		return $this;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompany($param) {
		$this->company = $param;
		
		return $this;
	}
	
	public function getCompany() {
		return $this->company;
	}
	
	public function setHid($param) {
		$this->hid = $param;
		
		return $this;
	}
	
	public function getHid() {
		return $this->hid;
	}
	
	public function setName($param) {
		$this->name = $param;
		
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	
}
