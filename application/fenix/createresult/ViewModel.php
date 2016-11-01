<?php
/**
 * 
 * Fenix project.
 * 
 * @author Jvb 26 Jan 2016
 * 
 */

namespace application\fenix\createresult;


class ViewModel {
	
	private $model;
	private $request;
	/**
	 * Instance of
	 * @var \Lib\Session 
	 */
	private $session;
	private $data;
	/**
	 * POST data: ids of all the <select>s
	 * @var array
	 */
	private $ids;
	/**
	 * POST data: hotel name
	 * @var string
	 */
	private $hotelname = '';
	private $rooms = [];
	private $periods = [];
	/**
	 * Array of Instances of Offer
	 * @var array
	 */
	private $offers;
	private $configuration = [];
	private $results = [];
	/**
	 * Unique Accommodations
	 * @var array
	 */
	private $unique_accs = [];
	static $adults = [
		0 => ' ADL',
		1 => 'SNGL', 
		2 => 'DBL', 
		3 => 'TRPL', 
	];


	public function __construct($model, \Lib\Request $request) {
		$this->model = $model;
		$this->request = $request;
		
		$this->session = new \Lib\Session;
		
		$this->ids = $request->getRequest('ids');
		$this->hotelname = $request->getRequest('hotelname');
	}
	
	public function setModel($data) {
		$this->model = $data;		
		return $this;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function getRequest() {
		return $this->request;
	}
	
	public function getSession() {
		return $this->session;
	}
	
	public function setData($data) {
		$this->data = $data;		
		return $this;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function getCountryVal() {
		return $this->ids[0];
	}
	
	public function getTownVal() {
		return $this->ids[1];
	}
	
	public function getHotelVal() {
		return $this->ids[2];
	}
	
	public function getContractVal() {
		return $this->ids[3];
	}
	
	public function getHotelName() {
		return strtoupper($this->hotelname);
	}
	
	/**
	 * A bit confusing: this value - POST data at Ajax call.
	 * In other words, currently selected <option> value.
	 * 
	 * @return string
	 */
	public function getOfferVal() {
		return $this->ids[4];
	}
	
	public function setRooms($id, $param) {
		$this->rooms[$id] = $param;		
		return $this;
	}
	
	public function getRooms() {
		return $this->rooms;
	}
	
	public function getRoomById($id) {
		return $this->rooms[$id];
	}
	
	public function addPeriod($id, $param) {
		$this->periods[$id] = $param;		
		return $this;
	}
	
	public function getPeriods() {
		return $this->periods;
	}
	
	public function getPeriodById($id) {
		return $this->periods[$id];
	}
	
	/**
	 * Offer object instance to keep.
	 * Only one offer is needed for our task.
	 * 
	 * @param Offer
	 * @return ViewModel
	 */
	public function addOffer($id, $param) {
		$this->offers[$id] = $param;		
		return $this;
	}
	
	/**
	 * Getter.
	 * 
	 * @return Offer
	 */
	public function getOffers() {
		return $this->offers;
	}
	
	/**
	 * Get the instance by offer id.
	 * 
	 * @param int $id
	 * @return Offer
	 */
	public function getOfferById($id) {
		return isset($this->offers[$id]) ? $this->offers[$id] : null;
	}
	
	/**
	 * Getter of the current (selected) offer.
	 * 
	 * @return Offer
	 */
	public function getOffer() {
		$id = $this->getOfferVal();
		
		return $this->getOfferById($id);
	}
	
	public function setConfiguration($param) {
		$this->configuration = $param;
		
		return $this;
	}
	
	public function getConfiguration() {
		return $this->configuration;
	}
	
	public function addResult($result) {
		$this->results[] = $result;
	}
	
	public function getResults() {
		return $this->results;
	}
	
	/**
	 * Well, this trick allows to collect all possible accomodations met in the result. As array keys.
	 * 
	 * @param string $acc Example: 'Adult,Adult,ChildB'
	 */
	public function addUniqueAcc($acc) {
		$tran = $this->translateAcc($acc);
			
		if (! $tran) {
			return;
		}
		
		$this->unique_accs[$acc] = $tran;
	}
	
	/**
	 * Unique Accommodations getter.
	 * 
	 * @return array
	 */
	public function getUniqueAccs() {
		return $this->unique_accs;
	}
	
	public function translateAcc($acc) {
		//return $acc;
		$adults = \substr_count($acc, 'Adult');
		$ca = \substr_count($acc, 'ChildA');
		$cb = \substr_count($acc, 'ChildB');
		$cc = \substr_count($acc, 'ChildC');
		$senior = \substr_count($acc, 'Senior');
		
		if ($senior) {
			return false;
		}
		if ($ca && $this->configuration->getFirst()[1] <= 2) {
			return false;
		}
		if ($ca > 2 || $cb > 2 || $cc > 2 || ($ca + $cb + $cc) > 2) {
			return false;
		}
		if ($adults === 1 && ($ca >= 1 || $cb >= 1 || $cc >= 1)) {
			return false;
		}

		$tran = $this->rules($adults, $ca, $cb, $cc);
		
		// output original accommodation if could not be translated
		return $tran === false ? $acc : $tran; 
	}
	
	/**
	 * Aux for translateAcc(). Translates similar rules.
	 * 
	 * @param int $adults
	 * @param int $cb
	 * @param int $cc
	 * @param array $second
	 * @param array $third
	 * @return string
	 */
	private function rules($adults, $ca, $cb, $cc) {
		
		$first = $this->configuration->getFirst();
		$second = $this->configuration->getSecond();
		$third = $this->configuration->getThird();
		
		$numc = $ca + $cb + $cc;
		
		$cprefix = $numc > 1 ? $numc : '';
		$a = $adults > 3 ? $adults . self::$adults[0] : self::$adults[$adults];
		
		$ch = [];
		
		if ($ca) {
			$ch[] = "({$first[0]}-{$first[1]})";
		}
		if ($cb) {
			$ch[] = "({$second[0]}-{$second[1]})";
		}
		if ($cc) {
			$ch[] = "({$third[0]}-{$third[1]})";
		}
		
		if ($numc > 0) {
			return $a ."+{$cprefix}CHD". \implode('', $ch);
		} else {
			return $a;
		}

		return false;
	}
	
}
