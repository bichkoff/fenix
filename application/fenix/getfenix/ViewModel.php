<?php
/**
 * 
 * Fenix project.
 * 
 * @author Jvb 26 Jan 2016
 * 
 */

namespace application\fenix\getfenix;


class ViewModel {
	private $model;
	private $request;


	public function __construct($model, \Lib\Request $request) {
		$this->model = $model;
		$this->request = $request;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function getRequest() {
		return $this->request;
	}
	
	/**
	 * Delete all data from tables indisciminately.
	 * Result of operation(s) is echoed into stream.
	 * 
	 * @param array $funcs List of tables to delete from
	 */
	public function deletions($funcs) {
		$db = $this->model->getDb();
		
		foreach ($funcs as $cls) {
			$table	= \strtolower($cls);
			
			$db->insUpdDel("DELETE FROM `{$table}`");
			
			if ($db->getCount()) {
				echo \date("d.m.Y H:i:s") ." Success; deleted ".$db->getCount()." rows from fenix.{$table}.\n"; // for log message
			}
		}
	}
	
	/**
	 * Try to insert into database from objects, containing various tables instances.
	 * 
	 * @param array $objs Set of \entity\fenix\ITable objects
	 * @return type
	 */
	public function insertions($objs) {		
		$db = $this->model->getDb();
		
		$db->startTran();
		
		// instances of classes: Countries, Hotels etc.
		foreach ($objs as $obj) {
			$cls		= $obj->getCls(); // Class name (ex., Countries)			
			$check_pk	= $obj->getCheckPrimary(); // constant: should we look for an existing record?
			
			// process table (array of rows objects)
			foreach ($obj->getTable() as $instance) {
				// row is an instance of entity\fenix\Countries, for example
				if ($this->insIntoTable($instance, $cls, $check_pk) === false) { 
					$db->rollBack();
					
					return;
				}
			}
		}
		
		$db->commit();
		
		echo \date("d.m.Y H:i:s") ." Success importing data from www.fenixtours.gr.\n"; // for log message
	}
	
	/**
	 * Insertion into cities, resorts etc. using instance of a class.
	 * 
	 * @param \entity\fenix\ITable $instance
	 * @param string $cls Table (class) name w/o namespace
	 * @param bool $check_pk Should we query db for an existing record or just try to insert?
	 * @return boolean False on error
	 */
	public function insIntoTable(\entity\fenix\ITable $instance, $cls, $check_pk) {
		$table	= \strtolower($cls);
		
		$id	= $instance->getId();
		$com= $instance->getCompany();
		
		if (empty($id)) {
			return false;
		}
		
		if ($check_pk) {
			$primary_key = [
				$id,
				$com,
			];

			$num = $this->model->checkId($table, $primary_key);

			// if row exists
			if ($num) {
				return true;
			}
		}
		
		return $this->model->insFromObject($instance); // insert new from object
	}
	
}
