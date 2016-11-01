<?php
/**
 * Data retrieval from fenixtours.gr.
 * Fenix project.
 * 
 * @author Jvb 26 Jan 2016
 * 
 */

namespace application\fenix\createresult;

use Lib\IController;
//use application\Db;


class Controller implements IController {
	
	private $vmodel;


	public function __construct(ViewModel $vmodel = null) {
		$this->vmodel = $vmodel;
    }
	
	/**
	 * Main action that does all. 
	 * In this case - retrieves data from tourindex.ru and saves it in db.
	 * 
	 * @return type
	 */
	public function action() {
		
		$country	= $this->vmodel->getCountryVal();
		$town		= $this->vmodel->getTownVal();
		$hotel		= $this->vmodel->getHotelVal();
		$contract	= $this->vmodel->getContractVal();
		$offer		= $this->vmodel->getOfferVal();
		
		$fenix = $this->vmodel->getSession()->getValue('fenix');
		
		// supposedly, session expired
		if (empty($fenix)) {
			$this->vmodel->setData('expired');
			return false;
		}
		
		$data = $fenix[$country][$town][$hotel][$contract];
		
		$this->vmodel->setData($data);
		
		$this->setRooms()
			->setPeriods()
			->setConfiguration()
			->setOffers($offer)
			->setPrices();
	}
	
	public function setRooms() {
		foreach ($this->vmodel->getData()->rooms as $v) {
			$this->vmodel->setRooms($v->hotel_room_id, new Room($v->hotel_room_id, $v->name));
		}
		
		return $this;
	}
	
	public function setPeriods() {
		foreach ($this->vmodel->getData()->periods as $id => $v) {
			$p = new Period($v->id);
			
			if (! empty($v->A_from)) {
				$p->addSubPeriod([$v->A_from, $v->A_to, ]);
			}
			if (! empty($v->B_from)) {
				$p->addSubPeriod([$v->B_from, $v->B_to, ]);
			}
			if (! empty($v->C_from)) {
				$p->addSubPeriod([$v->C_from, $v->C_to, ]);
			}
			if (! empty($v->D_from)) {
				$p->addSubPeriod([$v->D_from, $v->D_to, ]);
			}
			
			$this->vmodel->addPeriod($id, $p);
		}
		
		return $this;
	}
	
	public function setConfiguration() {
		$c = $this->vmodel->getData()->configuration;
		
		$boards = [];
		
		foreach ($c->boards as $k => $v) {
			$boards[$k] = $v;
		}
		
		$conf = new Configuration($boards);
		
		$children = []; // currently, max 3 children may be added
		
		$children[] = [
			$c->ChildA_min,
			ceil($c->ChildA_max),
		];
		
		if (! empty($c->ChildB_max)) {
			$children[] = [
				$c->ChildB_min,
				ceil($c->ChildB_max),
			];
		}
		
		if (! empty($c->ChildC_max)) {
			$children[] = [
				$c->ChildC_min,
				ceil($c->ChildC_max),
			];
		}
		
		foreach ($children as $child) {
			$conf->addChild($child);
		}
		
		$this->vmodel->setConfiguration($conf);
		
		return $this;
	}
	
	/**
	 * Set the necessary offer data for future usage.
	 * 
	 * @param int $offer Offer <select> currently selected value
	 * @return \application\fenix\createresult\Controller
	 */
	private function setOffers($offer) {
		if ($offer === '-1') {
			$this->vmodel->addOffer('-1', new Offer());			
			return $this;
		}
		
		foreach ($this->vmodel->getData()->offers as $k => $v) {
			$o = new Offer();

			if (! isset($v->discount_Adult)) {
				$o->setError(true);
			}

			// measure array size and take the last element into consideration only
			$last = count($v->offer_dates) - 1;
		
			if (isset($v->cumulative_with)) {
				$o->setCWith($v->cumulative_with);
			}
			if (isset($v->discount_type)) {
				$o->setDType($v->discount_type);
			}
			if (isset($v->discount_Adult)) {
				$o->setDAdult($v->discount_Adult);
			}
			if (isset($v->offer_dates[$last]->valid_from)) {
				$o->setVFrom(new \DateTime($v->offer_dates[$last]->valid_from));
			}
			if (isset($v->offer_dates[$last]->valid_to)) {
				$o->setVTo(new \DateTime($v->offer_dates[$last]->valid_to));
			}
			if (isset($v->offer_dates[$last]->book_from)) {
				$o->setBFrom(new \DateTime($v->offer_dates[$last]->book_from));
			}
			if (isset($v->offer_dates[$last]->book_to)) {
				$o->setBTo(new \DateTime($v->offer_dates[$last]->book_to));		
			}
			if (isset($v->offer_dates[$last]->checkin_from)) {
				$o->setCInFrom(new \DateTime($v->offer_dates[$last]->checkin_from));
			}
			if (isset($v->offer_dates[$last]->checkin_to)) {
				$o->setCInTo(new \DateTime($v->offer_dates[$last]->checkin_to));
			}
			if (isset($v->offer_dates[$last]->checkout_from)) {
				$o->setCOutFrom(new \DateTime($v->offer_dates[$last]->checkout_from));
			}
			if (isset($v->offer_dates[$last]->checkout_to)) {
				$o->setCOutTo(new \DateTime($v->offer_dates[$last]->checkout_to));
			}
			if (isset($v->days_before_arrival_mt)) {
				$o->setDaysBeforeMt($v->days_before_arrival_mt);
			}
			if (isset($v->days_before_arrival_lt)) {
				$o->setDaysBeforeLt($v->days_before_arrival_lt);
			}
			if (isset($v->min_stay)) {
				$o->setMinStay($v->min_stay);
			}
			if (isset($v->max_stay)) {
				$o->setMaxStay($v->max_stay);
			}
			if (isset($v->translations->{2}->title)) {
				$o->setTitle($v->translations->{2}->title);
			}
			if (isset($v->translations->{2}->description)) {
				$o->setDesc($v->translations->{2}->description);
			}

			$this->vmodel->addOffer($k, $o);
		}
		
		return $this;
	}
	
	/**
	 * Create (almost) final result.
	 */
	private function setPrices() {
		$prices = $this->vmodel->getData()->prices;
		
		// temporarily assumed discount in % (see Offer::$dtype)
		$discount = (int) $this->vmodel->getOffer()->getDAdult();
		
		$offer = $this->vmodel->getOfferVal(); // <select> value
		
		foreach ($prices as $hrid => $arr1) {
			foreach ($arr1 as $period => $arr2) {
				foreach ($arr2 as $arr3) {
					foreach ($arr3->boards as $board => $arr4) {
						$res = new Result();
			
						$room = $this->vmodel->getRoomById($hrid);			
						$res->setRoom($room);
						$pd = $this->vmodel->getPeriodById($period);				
						$res->setPeriod($pd);
						$res->setBoard($board);
						
						foreach ($arr4 as $acc => $price) {
							// for contract rates (-1) do not apply discount
							$pr = $offer === '-1' ? $price : \round((100 - $discount) * $price / 100, 2);
							
							$res->addAcc($acc, new Accommodation($acc, $pr));
							
							$this->vmodel->addUniqueAcc($acc); // collect data
						}
						
						$this->vmodel->addResult($res);
					}					
				}
			}
		}
	}
	
}
