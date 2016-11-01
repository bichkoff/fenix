<?php

namespace entity\fenix;


class Hotels implements ITable {
	
	private $id;
	private $company;
	private $place;
	private $name;
	private $stars;
	
	
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
	
	public function setPlace($param) {
		$this->place = $param;
		
		return $this;
	}
	
	public function getPlace() {
		return $this->place;
	}
	
	public function setName($param) {
		$this->name = $param;
		
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setStars($param) {
		$this->stars = $param;
		
		return $this;
	}
	
	public function getStars() {
		return $this->stars;
	}
	
}
