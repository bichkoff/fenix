<?php

namespace application\fenix\stopsalesexcel;


class View implements \Lib\IView {
	
    private $vmodel;


	public function __construct(ViewModel $vmodel = null, \Lib\Template $template = null) {
        $this->vmodel = $vmodel;
    }
    
	public function output() {
		
		$excel = new ExcelResult();
		
		$excel->setProperties(['Fenix', 'StopSales', '', '', '', '']);
		$excel->setTitles();
		$excel->setContent();

		$excel->export(); // actual journal data processing
		
		return '';
	}
	
}
