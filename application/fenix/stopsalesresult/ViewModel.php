<?php
/**
 * 
 * Fenix project.
 * 
 * @author Jvb 14 Mar 2016
 * 
 */

namespace application\fenix\stopsalesresult;

use application\fenix\Model;


class ViewModel {
	
	private $model;
	private $request;
	/**
	 * Instance of
	 * @var \Lib\Session 
	 */
	private $session;
	private $data;
	/**
	 * POST data: ids of all the <select>s
	 * @var array
	 */
	private $ids;
	/**
	 * Set of instances of Result (final rows)
	 * @var array
	 */
	private $results = [];
	


	public function __construct($model, \Lib\Request $request) {
		$this->model = $model;
		$this->request = $request;
		
		$this->session = new \Lib\Session;
		
		$this->ids = $request->getRequest('ids');
	}
	
	public function setModel($data) {
		$this->model = $data;		
		return $this;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function getRequest() {
		return $this->request;
	}
	
	public function getSession() {
		return $this->session;
	}
	
	public function setData($data) {
		$this->data = $data;		
		return $this;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function getIds() {
		return $this->ids;
	}
	
	public function getHotelVal() {
		return $this->ids[0];
	}
	
	public function getFromVal() {
		return Model::cv2Sql($this->ids[1]);
	}
	
	public function getToVal() {
		return Model::cv2Sql($this->ids[2]);
	}
	
	public function addResult($param) {
		$this->results[] = $param;
		
		return $this;
	}
	
	public function getResults() {
		return $this->results;
	}
	
	public function query($params) {
		$hotel_clause = '';
		
		if (empty($params[0])) {
			array_shift($params);			
		} else {
			$hotel_clause =  '`h`.`id` = ? AND';
		}
		
		return $this->model->getDb()->fetchAll("
			SELECT 
				`s`.`company`,
				`h`.`name` AS `hotel`,
				`from`,
				`to`,
				`r`.`name` AS `rname`,
				`created`,
				`pathnames` AS `placepath`
			FROM
				`stopsales` AS `s` 
				JOIN `hotels` AS `h` 
					ON `h`.`id` = `s`.`hid` 
				JOIN `rooms` AS `r` 
					ON `r`.`id` = `s`.`rid` 
				JOIN `places` AS `p` 
					ON `h`.`place` = `p`.`id`
			WHERE
				$hotel_clause
				`s`.`created` BETWEEN ? AND ? + INTERVAL 1 DAY
			ORDER BY 
				`hotel`,
				`created`
			",
			$params
		);
	}
	
}
