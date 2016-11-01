<?php

namespace application\fenix\createresult;


class Room {
	
	private $hrid;
	private $name;
	
	
	public function __construct($hrid, $name) {
		$this->hrid = $hrid;
		$this->name = $name;
	}
	
	public function setHrId($param) {
		$this->hrid = $param;
		
		return $this;
	}
	
	public function getHrId() {
		return $this->hrid;
	}
	
	public function setName($param) {
		$this->name = $param;
		
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	
}
