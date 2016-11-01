<?php

/**
 * MVC project.
 * Route search based on Configuration rules.
 * At present, Configuration here acts as an 'alias system', that translates rules to Convention ones.
 * But, generally speaking, this class can be anything.
 *
 * @author Jvb 20 Jan 2015
 * @version 0.1 08.07.2015
 */

namespace Lib;


class Configuration extends RouteSet implements IRule {
	
	private $request;

    public function __construct(Request $request) {
		$this->request = $request;
		
		parent::__construct();
    }

	/**
	 * Route creation, founded on Configuration that is looked in $this->routeset.
	 * 
	 * @return \Lib\Route|boolean
	 */
    public function find() {
		
		$first = $this->request->getFirst(); // first from REQUEST
		$second = $this->request->getSecond(); // second part from REQUEST
		$vmodel = null;
		$controller = null;
		
		$route = $this->getConfigurationRoute($first);
		
		if ($route === false) { // nothing to do here - go to Convention() or Def() etc.
			return false;
		}
		
		// 'real' first and second parts to serve (after translations)
		$first_part = $route->getFirst();
		$second_part = $route->getRequestSecond() == $second ? $route->getSecond() : '';
		
		//The path to the class
		$className = rtrim('\\'. APP_PATH .'\\' . $first_part . '\\' . $second_part, '\\');

		$viewName = $className . '\\View';

		//If the view doesn't exist, the rule can't continue
		if (! class_exists($viewName)) {
			return false;
		}

		$controllerName = $className . '\\Controller';

		//The model (or Model) will need to be shared between the controller and view
		$vmodelName = $className .'\\ViewModel';

		//The model doesn't need to exist, in its purest form, an MVC triad could just be a view
		if (class_exists($vmodelName)) {
			$model_name = '\\'. APP_PATH .'\\'. $first_part .'\\Model';
			$db = '\\'. APP_PATH .'\\Db';
			
			$vmodel = new $vmodelName(new $model_name(new $db), $this->request);
		}            

		if (class_exists($controllerName)) {
			$controller = new $controllerName($vmodel);
		}

		$view = new $viewName($vmodel, new Template($className, 'tpl.twig'));
	
		//Create the route object
		if (is_null($controllerName)) {
			return new Route($view);
		}

		return new Route($view, $controller);
    }
	
}
