<?php

namespace application\fenix\createresult;


class Period {
	
	private $id;
	/**
	 * Set of original periods, that may contain from 1 to 4 elements
	 * with from-end data represented as SQL-format strings (see Controller::setPeriods())
	 * @var array
	 */
	private $subp = [];


	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($param) {
		$this->id = $param;
		
		return $this;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function addSubPeriod($param) {
		$this->subp[] = $param;
	}
	
	public function getSubPeriods() {
		return $this->subp;
	}
	
}
