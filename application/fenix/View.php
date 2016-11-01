<?php

namespace application\fenix;


class View implements \Lib\IView {
	
    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
		
		$this->setAssets();
    }
    
	public function output() {		
		$reg = $this->vmodel->findRegions('Places');
		$tn  = $this->vmodel->findTownsAndLocs('Places');
		
		return $this->template->output([
			'regions' => $reg,
			'towns' => $tn,
			'rootpath' => ROOTPATH,
		]);
	}
	
	private function setAssets() {
		//$this->template->setTpl(__NAMESPACE__ .'/tpl.twig');
		$this->template->setTitle('Fenix');
		$this->template->addStyle('theme.blue.min.css');
		$this->template->addJs('jquery.min.js');
		$this->template->addJs('fenix.min.js');
		$this->template->addJs('jquery.tablesorter.min.js');
		$this->template->addJs('jquery.tablesorter.widgets.min.js');
	}
       
}
