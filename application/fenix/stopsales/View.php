<?php

namespace application\fenix\stopsales;


use Lib\IView;

class View implements IView {

    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
		
		$this->setAssets();
    }
    
    public function output() {
		$this->vmodel->setCriteria([
			'company' => 2, 
		]);		
		$this->vmodel->setOrderBy('name');
		
		$hs = $this->vmodel->find('Hotels');
		
		return $this->template->output([
			'rootpath' => ROOTPATH,
			'hs' => $hs,
		]);
	}
	
	private function setAssets() {
		//$this->template->setTpl(__NAMESPACE__ .'/tpl.twig');
		$this->template->setTitle('Fenix');
		$this->template->addStyle('jquery-ui.min.css');
		$this->template->addStyle('theme.blue.min.css');
		
		$this->template->addJs('jquery.min.js');
		$this->template->addJs('jquery-ui.min.js');
		$this->template->addJs('jquery.inputmask.js');
		$this->template->addJs('fenix.min.js');
		$this->template->addJs('jquery.tablesorter.min.js');
		$this->template->addJs('jquery.tablesorter.widgets.min.js');
	}
	
}
