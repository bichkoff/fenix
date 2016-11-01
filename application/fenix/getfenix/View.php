<?php

namespace application\fenix\getfenix;


class View implements \Lib\IView {
	
    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
    }
    
	public function output() {
		return ''; // nothing to output here
	}
       
}
