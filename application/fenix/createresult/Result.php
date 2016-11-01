<?php

namespace application\fenix\createresult;

class Result {

	/**
	 * Instance of; original data, containing from 1 to 4 periods
	 * @var Period
	 */
	private $period;
	/**
	 * Start of the period (for final result set)
	 * @var \DateTime
	 */
	private $from;
	/**
	 * End of the period (for final result set)
	 * @var \DateTime
	 */
	private $to;
	/**
	 * Instance of
	 * @var Room
	 */
	private $room;
	private $board;
	private $nights = 1;
	/**
	 * List of Accommodation objects
	 * @var array
	 */
	private $accs = [];
	

	public function __construct() { }

	public function setPeriod($param) {
		$this->period = $param;

		return $this;
	}

	public function getPeriod() {
		return $this->period;
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

	public function setRoom($param) {
		$this->room = $param;

		return $this;
	}

	public function getRoom() {
		return $this->room;
	}

	public function setBoard($param) {
		$this->board = $param;

		return $this;
	}

	public function getBoard() {
		return $this->board;
	}

	public function setNights($param) {
		$this->nights = $param;

		return $this;
	}

	public function getNights() {
		return $this->nights;
	}

	/**
	 * Set of Accommodation instances.
	 * 
	 * @param type $key
	 * @param Accommodation $obj
	 */
	public function addAcc($key, $obj) {
		$this->accs[$key] = $obj;
	}

	public function getAccs() {
		return $this->accs;
	}

	/**
	 * Get an Accommodation instance by key in $accs array.
	 * 
	 * @param type $key
	 * @return Accommodation
	 */
	public function getAccByKey($key) {
		return isset($this->accs[$key]) ? $this->accs[$key] : null;
	}

}
