<?php

namespace application\fenix\createresult;


class Accommodation {

	private $acc;
	private $price;

	public function __construct($acc, $price) {
		$this->acc = $acc;
		$this->price = $price;
	}

	public function setAcc($param) {
		$this->acc = $param;

		return $this;
	}

	public function getAcc() {
		return $this->acc;
	}

	public function setPrice($param) {
		$this->price = $param;

		return $this;
	}

	public function getPrice() {
		return $this->price;
	}

}
