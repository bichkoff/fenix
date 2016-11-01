<?php

namespace application\fenix\gethotels;


class View implements \Lib\IView {
	
    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
    }
    
	public function output() {
		$id = $this->vmodel->getRequest()->getRequest('id');
		
		$this->vmodel->setCriteria([
			'company' => 2, 
			'place' => $id, 
		]);
		$this->vmodel->setOrderBy("name");
		$hs = $this->vmodel->find('Hotels');
		
		if (! is_array($hs)) {
			$response = ['status' => 'error', ];
		} else {
			$response = ['status' => 'success', ];

			foreach ($hs as $v) {
				$response['data'][$v->getName()] = $v->getId(); // NB: keys!!!
			}
		}
		
		return \json_encode($response);
	}
       
}
