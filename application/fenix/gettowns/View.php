<?php

namespace application\fenix\gettowns;


class View implements \Lib\IView {
	
    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
    }
    
	public function output() {
		$tn  = $this->vmodel->findTownsAndLocs('Places');
		
		if (! is_array($tn)) {
			$response = ['status' => 'error', ];
		} else {
			$response = ['status' => 'success', ];

			foreach ($tn as $v) {
				$response['data'][$v->getName()] = $v->getId(); // NB: keys!!!
			}
		}
		
		return \json_encode($response);
	}
       
}
