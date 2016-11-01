<?php

namespace application\def;


use Lib\IView;

class View implements IView {

    private $vmodel;
	private $template;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
		$this->template = $template;
    }
    
    public function output() {
		return $this->template->output([
			'data' => 'Default, default...',
		]);
	}
}