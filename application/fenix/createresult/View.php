<?php

namespace application\fenix\createresult;


class View implements \Lib\IView {
	
    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
    }
    
	public function output() {
		$twig = $this->template->getTwig();
		
		$data		= ['vm'		=> $this->vmodel, ];
		$response	= ['status' => 'success', ];		
			
		if ($this->vmodel->getData() === 'expired') {
			$tpl = $this->template->setTpl('expired.twig')->getTpl();
			$response['substatus'] = 'expired';
		} else if ($this->vmodel->getOffer()->getError()) {
			$tpl = $this->template->setTpl('wrongdata.twig')->getTpl();
			$response['substatus'] = 'wrongdata';
		} else {
			$res = $this->vmodel->getResults();

			$output = $this->prepareOutput($res);

			$data['data'] = $output;
			
			$this->vmodel->setModel(null); // some of properties can not be serialized, so we just remove them :)
			
			// for export to Excel keep data in session
			$this->vmodel->getSession()->setValue('resultoutput', $output);
			$this->vmodel->getSession()->setValue('vmodel', $this->vmodel);

			$tpl = $this->template->getTpl();
		}
		
		$response['data'] = $twig->render($tpl, $data);
		
		return \json_encode($response);
	}
	
	/**
	 * Prepare final result set convenient to output (containing one row per period).
	 * 
	 * @param array $res Set of Result instances
	 * @return array
	 */
	private function prepareOutput($res) {
		$tmp = []; // new res list, containing one instance for one row
		
		$is_offer = $this->vmodel->getOfferVal() === '-1'; // foo <select> value switch
		
		$ovf = $this->vmodel->getOffer()->getVFrom();
		$ovt = $this->vmodel->getOffer()->getVTo();
		
		foreach ($res as $result) {
			// if found at least one not empty price - place row in the final result set
			if ($this->isEmpty($result)) {
				continue;
			}			
			// skip room names starting from 1Adult
			if (preg_match("/^1Adult/", $result->getRoom()->getName())) {
				continue;
			}
			
			foreach ($result->getPeriod()->getSubPeriods() as $p) {
				$copy = clone $result;
				
				$pf = new \DateTime($p[0]);
				$pt = new \DateTime($p[1]);
				
				// when showing contract rates don't take into account any offers at all
				if (! $is_offer && ($ovf > $pt || $ovt < $pf)) {
					continue;
				}
				
				// analyze/compare dates
				if (! $is_offer && $ovf > $pf) {
					$copy->setFrom($ovf);
				} else {
					$copy->setFrom($pf);
				}
				
				if (! $is_offer && $ovt < $pt) {
					$copy->setTo($ovt);
				} else {
					$copy->setTo($pt);
				}
				
				$tmp[] = $copy;
			}			
		}
		
		return $tmp;
	}
	
	/**
	 * For every result check accommodation price and try to find at least one not empty.
	 * 
	 * @param Result $result
	 * @return boolean
	 */
	private function isEmpty($result) {		
		foreach ($this->vmodel->getUniqueAccs() as $k => $v) {
			if (! empty($result->getAccByKey($k))) {
				return false;
			}
		}
		
		return true;
	}
       
}
