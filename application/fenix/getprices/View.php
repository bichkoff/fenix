<?php
/**
 * This branch returns offers names only. Though it retrieves prices 
 * they are stored in Session var 'prices' for future use. 
 * REPEAT: output() return here is a JS object for offers <select>!
 * Fenix project.
 * 
 * @author Jvb 03 Feb 2016
 * 
 */

namespace application\fenix\getprices;


class View implements \Lib\IView {
	
    private $vmodel;
	private $template;
	private $session;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
		
		$this->session = new \Lib\Session;
    }
    
	public function output() {
		$ids = $this->vmodel->getRequest()->getRequest('ids');
		
		$prices = $this->getPrices($ids);
		
		if ($prices === false) {
			$response = ['status' => 'error', ];
		} else {
			$response = ['status' => 'success', ];
			
			// For use in JS: dates are strings here
			foreach ($prices->offers as $k => $v) {
				// measure array size and take only the last element into consideration
				$last = count($v->offer_dates) - 1;

				$response['data'][$k] = [
					$v->translations->{2}->title,
					\application\fenix\Model::cv($v->offer_dates[$last]->valid_from),
					\application\fenix\Model::cv($v->offer_dates[$last]->valid_to),
					\application\fenix\Model::cv($v->offer_dates[$last]->book_from),
					\application\fenix\Model::cv($v->offer_dates[$last]->book_to),
					(isset($v->discount_Adult) ? $v->discount_Adult .'%' : ''), 
				];
			}
		}
		
		return \json_encode($response);
	}
       
	/**
	 * Get prices data by Hotel and Contract ids.
	 * From remote server or Session array.
	 * 
	 * @param array $ids
	 * @return boolean|array
	 */
	private function getPrices($ids) {
		$curl = new \application\fenix\Curl;
		$auth = new \application\fenix\Auth($curl);
		
		if (! $auth->run()) {
			return false;
		}
		
		$cl = __NAMESPACE__ .'\\Prices';
		$instance = new $cl($curl, $auth);	
		$instance->setHotelId($ids[2]);
		$instance->setContractId($ids[3]);
		
		// is session array empty?
		$fenix = $this->session->getValue('fenix');
		
		// if not set, run Ajax request to fenix.gr
		if (isset($fenix[$ids[0]][$ids[1]][$ids[2]][$ids[3]])) {
			$result = $fenix[$ids[0]][$ids[1]][$ids[2]][$ids[3]];
		} else {
			// otherwise take data from array
			$result = $instance->run();
			
			// keep every retrieved result in session!
			$prices = $fenix;
			$prices[$ids[0]][$ids[1]][$ids[2]][$ids[3]] = $result; // add new
			
			$this->session->setValue('fenix', $prices);
		}
		
		return $result;
	}
	
}
