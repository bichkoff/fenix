<?php

namespace entity\fenix;


class Places implements ITable {
	
	private $id;
	private $company;
	private $name;
	private $type;
	private $country;
	private $region;
	private $area;
	private $town;
	private $pathnames;


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
	
	public function setType($param) {
		$this->type = $param;
		
		return $this;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setCountry($param) {
		$this->country = $param;
		
		return $this;
	}
	
	public function getCountry() {
		return $this->country;
	}
	
	public function setRegion($param) {
		$this->region = $param;
		
		return $this;
	}
	
	public function getRegion() {
		return $this->region;
	}
	
	public function setArea($param) {
		$this->area = $param;
		
		return $this;
	}
	
	public function getArea() {
		return $this->area;
	}
	
	public function setTown($param) {
		$this->town = $param;
		
		return $this;
	}
	
	public function getTown() {
		return $this->town;
	}
	
	public function setPathNames($param) {
		$this->pathnames = $param;
		
		return $this;
	}
	
	public function getPathNames() {
		return $this->pathnames;
	}
	
}
