<?php

namespace application;


/**
 * PDO Factory class.
 * Creates PDO connection.
 * Room Types project.
 * 
 * @author Jvb 04 Jun 2015
 * @version 0.1 04.05.2015
 */
class DbConnect {

	private static $instance = null;

	private function __construct() {
		try {
			$dsn = "mysql:dbname=".MYSQLDB."; host=".MYSQLHOST;				
			self::$instance = new \PDO($dsn, MYSQLUSERNAME, MYSQLPASS);
			self::$instance->query("SET NAMES '".MYSQLCHARSET."'");
		} catch (\PDOException $e) {
			die(date('d.m.Y H:i:s') .' Connection failed: '. $e->getMessage());
		}

	}

	public static function getInstance() {

		if (self::$instance === null) {
			new DbConnect();
		}

		return self::$instance;
	}

}
