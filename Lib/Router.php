<?php

namespace Lib;


class Router {
    private $rules = [];
    
    public function addRule(IRule $rule) {
        $this->rules[] = $rule;
    }
    
    public function getRoute() {
		
        foreach ($this->rules as $rule) {
            if ($found = $rule->find()) {
				return $found;    
			}
        }
        
        throw new Exception('No matching route found');
    }
	
}
