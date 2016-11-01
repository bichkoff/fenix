<?php

namespace Lib;


class Convention implements IRule {
	
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

	/**
	 * Route creation, founded on Convention REQUEST rules.
	 * 
	 * @return \Lib\Route|boolean
	 */
    public function find() {
        //The name of the class
		$first = $this->request->getFirst();
		$second = $this->request->getSecond();
		$vmodel = null;
		$controller = null;
		
        $className = rtrim('\\'. APP_PATH .'\\'. $first .'\\'. $second, '\\');

        $viewName = $className .'\\View';
        
        //If the view doesn't exist, the convention rule can't continue - serve Def() route
        if (! class_exists($viewName)) {
			return false;
		}
		
        $controllerName = $className .'\\Controller';
            
		//The model (or ViewModel) will need to be shared between the controller and view
		$vmodelName = $className .'\\ViewModel';

		if (class_exists($vmodelName)) {
			$model_name = '\\'. APP_PATH .'\\'. $first .'\\Model';
			$db = '\\'. APP_PATH .'\\Db';
			
			$vmodel = new $vmodelName(new $model_name(new $db), $this->request);
		}
		
		$view = new $viewName($vmodel, new Template($className, 'tpl.twig'));
		
		if (class_exists($controllerName)) {
			$controller = new $controllerName($vmodel);
		}
		
        //Create the route object
        return new Route($view, $controller);
    }
	
}
