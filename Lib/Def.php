<?php
/**
 * Default Route setting.
 * Can be used to serve Error 404 page etc.
 * MVC project.
 *
 * @author Jvb 20 Jan 2015
 * 
 */

namespace Lib;


class Def implements IRule {
	
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

	/**
	 * Route creation; the only possible choice - to serve the default route (hardcoded here).
	 * 
	 * @return \Lib\Route|boolean
	 */
    public function find() {
		$first = $this->request->getFirst();
		
		$viewName = '\\'. APP_PATH .'\\'. $first .'\\View';

        //If the view doesn't exist, roll back to application\def\View
        if (! class_exists($viewName)) {
			$viewName = '\\'. APP_PATH .'\\def\\View'; // this only line is enough, in fact
		}
		
        return new Route(new $viewName(null, new Template('/templates', 'def.twig')));
    }
	
}
