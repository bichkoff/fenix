<?php

namespace application\fenix\createresult;


class Offer {
	
	/**
	 * Cumulative with
	 * @var array
	 */
	private $cwith;
	/**
	 * Absolute value or discount in '%'
	 * @var string Supposedly, a string
	 */
	private $dtype = '%';
	/**
	 * Discount in $dtype units
	 * @var int
	 */
	private $dadult = 0;
	private $title = '';
	private $desc = '';
	/**
	 * Valid from
	 * @var \DateTime
	 */
	private $vfrom;
	private $vto;
	/**
	 * Book from
	 * @var \DateTime
	 */
	private $bfrom;
	private $bto;
	/**
	 * Check in from
	 * @var \DateTime
	 */
	private $cinfrom;
	private $cinto;
	/**
	 * Check out from
	 * @var \DateTime
	 */
	private $coutfrom;
	private $coutto;
	/**
	 * Days before arrival more than (supposedly)
	 * @var int
	 */
	private $daysbeforemt;
	private $daysbeforelt;
	/**
	 * Minimum stay in days
	 * @var int
	 */
	private $minstay;
	private $maxstay;
	/**
	 * Indicator: original data doesn't contain some essential values
	 * @var boolean
	 */
	private $error = false;


	public function __construct() { }
	
	public function setCWith($param) {
		$this->cwith = $param;
		return $this;
	}
	
	public function getCWith() {
		return $this->cwith;
	}
	
	public function setDType($param) {
		$this->dtype = $param;
		return $this;
	}
	
	public function getDType() {
		return $this->dtype;
	}
	
	public function setDAdult($param) {
		$this->dadult = $param;
		return $this;
	}
	
	public function getDAdult() {
		return $this->dadult;
	}
	
	public function setTitle($param) {
		$this->title = $param;
		return $this;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setDesc($param) {
		$this->desc = $param;
		return $this;
	}
	
	public function getDesc() {
		return $this->desc;
	}
	
	public function setVFrom($param) {
		$this->vfrom = $param;
		return $this;
	}
	
	public function getVFrom() {
		return $this->vfrom;
	}
	
	public function setVTo($param) {
		$this->vto = $param;
		return $this;
	}
	
	public function getVTo() {
		return $this->vto;
	}
	
	public function setBFrom($param) {
		$this->bfrom = $param;
		return $this;
	}
	
	public function getBFrom() {
		return $this->bfrom;
	}
	
	public function setBTo($param) {
		$this->bto = $param;
		return $this;
	}
	
	public function getBTo() {
		return $this->bto;
	}
	
	public function setCInFrom($param) {
		$this->cinfrom = $param;
		return $this;
	}
	
	public function getCInFrom() {
		return $this->cinfrom;
	}
	
	public function setCInTo($param) {
		$this->cinto = $param;
		return $this;
	}
	
	public function getCInTo() {
		return $this->cinto;
	}
	
	public function setCOutFrom($param) {
		$this->coutfrom = $param;
		return $this;
	}
	
	public function getCOutFrom() {
		return $this->coutfrom;
	}
	
	public function setCOutTo($param) {
		$this->coutto = $param;
		return $this;
	}
	
	public function getCOutTo() {
		return $this->coutto;
	}
	
	public function setDaysBeforeMt($param) {
		$this->daysbeforemt = $param;
		return $this;
	}
	
	public function getDaysBeforeMt() {
		return $this->daysbeforemt;
	}
	
	public function setDaysBeforeLt($param) {
		$this->daysbeforelt = $param;
		return $this;
	}
	
	public function getDaysBeforeLt() {
		return $this->daysbeforelt;
	}
	
	public function setMinStay($param) {
		$this->minstay = $param;
		return $this;
	}
	
	public function getMinStay() {
		return $this->minstay;
	}
	
	public function setMaxStay($param) {
		$this->maxstay = $param;
		return $this;
	}
	
	public function getMaxStay() {
		return $this->maxstay;
	}
	
	public function setError($param) {
		$this->error = $param;
		return $this;
	}
	
	public function getError() {
		return $this->error;
	}
	
}
