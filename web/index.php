<?php
/**
 * Entry point.
 * ArtIndex project.
 * 
 * @author Jvb 26 Nov 2015
 * 
 */

require_once __DIR__ .'/../config/config.php';
require_once __DIR__ .'/../config/autoloader.php';
require_once __DIR__ .'/../vendor/autoload.php';

use Lib\FrontController;
use Lib\Router;
use Lib\Request;


$fc = new FrontController(new Router(), new Request());

$fc->run();

//var_dump($fc);
