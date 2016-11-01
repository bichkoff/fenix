<?php

namespace entity\fenix;


class Stopsales implements ITable {
	
	private $pk; // real primary key; fucking greeks supply one id with different data...
	private $id;
	private $company;
	private $hid;
	private $rid;
	private $from;
	private $to;
	private $created;
	
	
	public function __construct() { }
	
	public function setPk($param) {
		$this->pk = $param;
		
		return $this;
	}
	
	public function getPk() {
		return $this->pk;
	}
	
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
	
	public function setRid($param) {
		$this->rid = $param;
		
		return $this;
	}
	
	public function getRid() {
		return $this->rid;
	}
	
	public function setFrom($param) {
		$this->from = $param;
		
		return $this;
	}
	
	public function getFrom() {
		return $this->from;
	}
	
	public function setTo($param) {
		$this->to = $param;
		
		return $this;
	}
	
	public function getTo() {
		return $this->to;
	}
	
	public function setCreated($param) {
		$this->created = $param;
		
		return $this;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
}
