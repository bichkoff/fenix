<?php
/**
 * A row of the resulting Stop Sales table.
 * 
 * Fenix project.
 * 
 * @author Jvb 14 Mar 2016
 * 
 */

namespace application\fenix\stopsalesresult;

class Result {

	/**
	 * Company id
	 * @var int
	 */
	private $company;
	/**
	 * Hotel name
	 * @var Period
	 */
	private $hotel;
	/**
	 * Start of the `created` period (for final result set)
	 * @var \DateTime
	 */
	private $from;
	/**
	 * End of the `created` period (for final result set)
	 * @var \DateTime
	 */
	private $to;
	/**
	 * Room name
	 * @var string
	 */
	private $rname;
	/**
	 * Fenix Stop Sale creation time
	 * @var \DateTime
	 */
	private $created;
	/**
	 * Serialized Places array hotel belongs to, unserialized
	 * @var array
	 */
	private $placepath;



	public function __construct() { }

	public function setCompany($param) {
		$this->company = $param;

		return $this;
	}

	public function getCompany() {
		return $this->company;
	}

	public function setHotel($param) {
		$this->hotel = $param;

		return $this;
	}

	public function getHotel() {
		return $this->hotel;
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

	public function setRname($param) {
		$this->rname = $param;

		return $this;
	}

	public function getRname() {
		return $this->rname;
	}

	public function setCreated($param) {
		$this->created = $param;

		return $this;
	}

	public function getCreated() {
		return $this->created;
	}

	public function setPlacePath($param) {
		$this->placepath = unserialize($param);

		return $this;
	}

	public function getPlaceSecond() {
		return $this->placepath[1];
	}

}
