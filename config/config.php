<?php
/**
 * MVC project.
 * Main configuration file.
 *
 * @author Jvb 20 Jan 2015
 * @version 0.2 06.07.2015
 *
 */

chdir(__DIR__ .'/../'); // needed for console use

// 'secret' parameters
include_once 'params.php';

//////////////////////////////////

// common params set to use in the project
define('APP_PATH', 'application');
define('VERSION', '20150316');
