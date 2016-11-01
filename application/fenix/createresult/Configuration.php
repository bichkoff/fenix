<?php

namespace application\fenix\createresult;


class Configuration {
	
	private $boards;
	private $children = [];


	public function __construct($boards) {
		$this->boards = $boards;
	}
	
	public function setBoards($param) {
		$this->boards = $param;
		
		return $this;
	}
	
	public function getBoards() {
		return $this->boards;
	}
	
	/**
	 * Children ages.
	 * 
	 * @param array $first Child: [age from, age to]
	 * @param array $second
	 * @return \application\fenix\createresult\Configuration
	 */
	public function addChild($child) {
		$this->children[] = $child;
		
		return $this;
	}
	
	/**
	 * Returns all children data.
	 * 
	 * @return array
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * Returns the first child ages interval.
	 * 
	 * @return array
	 */
	public function getFirst() {
		return $this->children[0];
	}
	
	public function getSecond() {
		return isset($this->children[1]) ? $this->children[1] : [];
	}
	
	public function getThird() {
		return isset($this->children[2]) ? $this->children[2] : [];
	}
	
}
