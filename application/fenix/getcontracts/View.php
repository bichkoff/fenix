<?php

namespace application\fenix\getcontracts;


class View implements \Lib\IView {
	
    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
    }
    
	public function output() {
		$hotel_id = $this->vmodel->getRequest()->getRequest('id');
		
		$contracts = $this->vmodel->getContracts($hotel_id);
		
		if ($contracts === false) {
			$response = ['status' => 'error', ];
		} else {		
			$response = ['status' => 'success', ];

			foreach ($contracts as $v) {
				$response['data'][$v->id] = [$v->season, \application\fenix\Model::cv($v->valid_from), \application\fenix\Model::cv($v->valid_to), ];
			}
		}
		
		return \json_encode($response);
	}
       
}
