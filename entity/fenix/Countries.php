<?php

namespace entity\fenix;


class Countries implements ITable {
	
	private $id;
	private $company;
	private $name;
	private $iso2;
	private $iso3;
	
	
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
	
	public function setName($param) {
		$this->name = $param;
		
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setIso2($param) {
		$this->iso2 = $param;
		
		return $this;
	}
	
	public function getIso2() {
		return $this->iso2;
	}
	
	public function setIso3($param) {
		$this->iso3 = $param;
		
		return $this;
	}
	
	public function getIso3() {
		return $this->iso3;
	}
	
}
