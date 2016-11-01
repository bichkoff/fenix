<?php

namespace Lib;


class FrontController {

	private $request;
	private $router;
	

	public function __construct(Router $router, Request $request) {
		$this->request = $request;
		$this->router = $router;
	}

	public function run() {

		$this->router->addRule(new Convention($this->request));
		$this->router->addRule(new Configuration($this->request));
		$this->router->addRule(new Def($this->request));
		
		try {
			$route = $this->router->getRoute();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		$controller = $route->getController();

		// does controller exist?
		if (! empty($controller)) {
			$reflector = new \ReflectionClass($controller);

			// execute controller::action() if exists
			if ($reflector->hasMethod('action')) {
				$controller->action();
			}
		}
		
		echo $route->getView()->output();
	}

}
