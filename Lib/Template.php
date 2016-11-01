<?php

namespace Lib;


class Template {
	private $tpl = 'templates/def.twig'; // default template
	private $header = 'templates/header.twig';
	private $footer = 'templates/footer.twig';
	private $styles = [];
	private $jss = [];
	private $title = ''; // page title
	private $twig;
	private $class_name;


	public function __construct($class_name, $tpl) {
		$loader = new \Twig_Loader_Filesystem('./');

		$this->twig = new \Twig_Environment($loader, [
			'cache' => './temp',
			'debug' => true,
		]);
		
		$this->class_name = $class_name;
		
		$this->styles[] = "styles.min.css?v=". VERSION; // default stylesheet
		
		$this->setTpl($tpl);
	}

	public function setHeader($header) {
		$this->header = $header;
	}
	
	public function addStyle($css) {
		$this->styles[] = $css .'?v='. VERSION;
	}
	
	public function addJs($js) {
		$this->jss[] = $js .'?v='. VERSION;
	}
	
	public function setTpl($tpl) {
		$filepath = \str_replace('\\', '/', $this->class_name);		
		$templ = '.'. $filepath .'/'. $tpl;
		
		if (file_exists($templ)) {
			$this->tpl = $templ;
		}
		
		return $this;
	}
	
	public function getTpl() {
		return $this->tpl;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setFooter($footer) {
		$this->footer = $footer;
	}
	
	public function getTwig() {
		return $this->twig;
	}
	
	public function output($data) {
		return $this->twig->render($this->header, [
			'rootprefix'=> ROOTPATH,
			"styles"	=> $this->styles, 
			"jss"		=> $this->jss,
			"title"		=> $this->title,
		])
		. $this->twig->render($this->tpl, $data)
		. $this->twig->render($this->footer);
	}
	
}
