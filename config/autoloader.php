<?php

spl_autoload_register(function ($class) {
	$filename = str_replace('\\', '/', $class) .'.php';

	if (! file_exists($filename)) {
		return false;
	}

	require_once($filename);
}, true);
