<?php
/**
 * 
 * Fenix project.
 * 
 * @author Jvb 26 Jan 2016
 * 
 */

namespace application\fenix;


class ViewModel {
	
	private $model;
	private $request;
	/**
	 * Condition for filtering records
	 * @var array
	 */
	private $criteria = [];
	/**
	 * SQL ORDER BY value; ex. 'name DESC'
	 * @var string
	 */
	private $orderby = '';


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
	
	public function setCriteria(array $param) {
		$this->criteria = $param;
		
		return $this;
	}
	
	public function setOrderBy($param) {
		$this->orderby = $param;
		
		return $this;
	}
	
	/**
	 * Execute a model func, that retrieves data from database using $criteria.
	 * 
	 * @param string $table
	 * @return array
	 */
	public function find($table) {
		return $this->model->getData($table, $this->criteria, $this->orderby);
	}

	/**
	 * Get data directly.
	 * 
	 * @param string $table
	 * @return array
	 */
	public function findRegions($table) {
		$tbl = \strtolower($table);
		
		$sql = "
			SELECT 
				* 
			FROM
				`{$tbl}` 
			WHERE 
				`company` = 2
				AND `country` = 1
				AND `town` = 0
				AND `area` = 0
				AND `region` <> 0
			ORDER BY
				`name`
		";
		
		return $this->model->getDb()->fetchAllIntoObject($sql, '\entity\fenix\\'. $table);
	}

	/**
	 * Get data directly.
	 * 
	 * @param string $table
	 * @return array
	 */
	public function findTownsAndLocs($table) {
		$tbl = \strtolower($table);
		
		$sql = "
			SELECT 
				* 
			FROM
				`{$tbl}` 
			WHERE 
				`company` = 2
				AND `country` = 1
				AND (
					`type` = 'Location' 
					OR `type` = 'Town'
				)
			ORDER BY
				`name`
		";
		
		return $this->model->getDb()->fetchAllIntoObject($sql, '\entity\fenix\\'. $table);
	}

}
