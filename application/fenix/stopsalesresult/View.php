<?php

namespace application\fenix\stopsalesresult;


class View implements \Lib\IView {
	
    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
    }
    
	public function output() {
		$twig = $this->template->getTwig();
		$tpl = $this->template->getTpl();
		
		$hotel = $this->vmodel->getHotelVal();
		$from = $this->vmodel->getFromVal();
		$to = $this->vmodel->getToVal();
		
		$res = $this->vmodel->query([$hotel, $from, $to, ]);
		
		$this->prepareOutput($res);
		
		$data['data'] = $this->vmodel->getResults();

		$response = ['status' => 'success', ];		
		$response['data'] = $twig->render($tpl, $data);
		
		$this->vmodel->getSession()->setValue('stopsales', $data['data']);		
		
		return \json_encode($response);
	}
	
	/**
	 * Prepare final result set convenient to output (containing one row per period).
	 * 
	 * @param array $res Set of Result instances
	 * @return array
	 */
	private function prepareOutput($res) {
		
		foreach ($res as $v) {
			$r = new Result();
			
			$r->setCompany($v['company']);
			$r->setHotel($v['hotel']);
			$r->setFrom(new \DateTime($v['from']));
			$r->setTo(new \DateTime($v['to']));
			$r->setRname($v['rname']);
			$r->setCreated(new \DateTime($v['created']));
			$r->setPlacePath($v['placepath']);
			
			$this->vmodel->addResult($r);
		}
	}
	
}
