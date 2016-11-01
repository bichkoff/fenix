<?php

namespace application;


class Db {
	
	private $dbh;
	private $count;


	public function __construct() {
		$this->dbh = DbConnect::getInstance();
	}
	
	public function getCount() {
		return $this->count;
	}
	
	public function startTran() {
		$this->dbh->beginTransaction();
	}
	
	public function commit() {
		try {
			$this->dbh->commit();
		} catch (\PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function rollBack() {
		try {
			$this->dbh->rollBack();
		} catch (\PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	/**
	 * Used for SQL SELECTS.
	 * 
	 * @param string $sql
	 * @param array $params
	 * @return array
	 * @throws Exception
	 */
	public function fetchAll($sql, $params = []) {
		$sth = $this->dbh->prepare($sql);
		
		try {
			$res = $sth->execute($params);
		} catch (\PDOException $e) {
			echo $e->getMessage();
			
			return;
		}
		
		if ($res) {
			$arr = $sth->fetchAll();
			
			$this->count = \count($arr);
			
			return $arr;
		} else {
			throw new \Exception("Error executing '{$sql}'.");
		}
	}
	
	/**
	 * Used for SQL INSERTs, UPDATEs and DELETEs.
	 * 
	 * @param string $sql
	 * @param array $params
	 * @return array
	 * @throws Exception
	 */
	public function insUpdDel($sql, $params = []) {
		$sth = $this->dbh->prepare($sql);
		
		try {
			$res = $sth->execute($params);
		} catch (\PDOException $e) {
			echo $e->getMessage();
			
			return;
		}
		
		if ($res) {
			$this->count = $sth->rowCount();
			
			return $this->count;
		} else {
			return false;
		}
	}
	
	/**
	 * Get data from database and put it into array of class instances.
	 * 
	 * @param string $sql SQL query to execute
	 * @param string $entity Class name (with ns) to put results into
	 * @param array $params Criteria params to pass to SQL query
	 * @return boolean|array
	 */
	public function fetchAllIntoObject($sql, $entity, array $params = []) {
		$sth = $this->dbh->prepare($sql);
		
		try {
			$res = $sth->execute($params);
		} catch (\PDOException $e) {
			echo $e->getMessage();
			
			return;
		}
		
		if ($res) {
			$arr = $sth->fetchAll(\PDO::FETCH_CLASS, $entity);
			
			$this->count = \count($arr);
			
			return $arr;
		} else {
			throw new \Exception("Error executing '{$sql}'.");
		}
	}
	
}
