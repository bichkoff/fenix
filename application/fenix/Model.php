<?php
/**
 * Database Model: data manipulation.
 * ArtIndex project.
 * 
 * @author Jvb 26 Nov 2015
 * 
 */

namespace application\fenix;

use application\Db;


class Model {
	
	private $db;


	public function __construct(Db $db) {
		$this->db = $db;
	}
	
	public function getDb() {
		return $this->db;
	}
	
	/**
	 * Convert one arbitrary format date string to another arbitrary format.
	 *
	 * @param string $dt Input date
	 * @return string Output date
	 */
	public static function cv($dt) {
		if (empty($dt) || $dt == '0000-00-00') {
			return '';
		}
		$date = \DateTime::createFromFormat('Y-m-d', $dt);
		return $date->format('d.m.Y');
	}
	
	/**
	 * Convert Russian format date string to SQL format.
	 *
	 * @param string $dt Input date
	 * @return string Output date
	 */
	public static function cv2Sql($dt) {
		if (empty($dt) || $dt == '00.00.0000') {
			return '';
		}
		$date = \DateTime::createFromFormat('d.m.Y', $dt);
		return $date->format('Y-m-d');
	}
	
	/**
	 * Search record existence in a table by given `id`.
	 * 
	 * @param string $table table name
	 * @param array $params Primary key: `id` + `company`
	 * @return int Number of records found
	 */
    public function checkId($table, $params) {
		try {
			$this->db->fetchAll("
				SELECT 
					1
				FROM
					$table
				WHERE
					`id` = ?
					AND `company` = ?
				",
				$params
			);
		} catch (\Exception $e) {
			echo $e->getMessage();
			
			return false;
		}
		
		return $this->db->getCount();
    }
	
	/**
	 * Make insertion into table from an instance using its model.
	 * 
	 * @param Object $instance
	 * @return boolean
	 */
	public function insFromObject($instance) {
		$refl = new \ReflectionClass($instance);
		
		$cl = $refl->getName(); // class name
		$clnons = \substr(\strrchr($cl, '\\'), 1); // strip namespace
		$props = $refl->getProperties(); // class properties
		
		$names = [];
		$values = [];
		
		foreach ($props as $prop) {
		    $pname = $prop->getName();
			$names[] = $pname;
			
			$method = $refl->getMethod('get'.$pname);
			$mname = $method->getName(); // 'getter' name
			
			$values[] = $instance->$mname(); // property value
		}
		
		$placeholders = \implode(',', \array_fill(0, \count($props), '?'));
		
		// use IGNORE, because same tours may be present in different queries (exist in db)
		$res = $this->db->insUpdDel("
				INSERT INTO 
					`". \strtolower($clnons) ."` (
					`". \implode('`,`', $names) ."`
				) VALUES (
					{$placeholders}
				)
			",
			$values
		);
					
		if ($res) {
			return $res;			
		}
		
		return false;
	}
	/**
	 * Place retrieved data, filtered with $criteria into array of Object from \entity.
	 * 
	 * @param string $table
	 * @param array $criteria
	 * @return array
	 */
	public function getData($table, array $criteria = [], $orderby = '') {
		$ns = \str_replace('application', 'entity', __NAMESPACE__);
		$tbl = \strtolower($table);
		
		$oby = empty($orderby) ? '' : " ORDER BY `{$orderby}`";
		
		$sql = "SELECT * FROM $tbl". $this->setParams($criteria) . $oby;
		
		$params = \array_values($criteria);
		
		return $this->db->fetchAllIntoObject($sql, $ns .'\\'. $table, $params);
	}
	
	/**
	 * Helper: create parameterized string for PDO::prepare().
	 * 
	 * @param array $criteria
	 * @return string
	 */
	private function setParams(array $criteria) {
		if (empty($criteria)) {
			return '';
		}
		
		$keys = \array_keys($criteria);
		$tmp = [];
		
		foreach ($keys as $v) {
			$tmp[] = "{$v} = ?";
		}
		
		return ' WHERE '. \implode(' AND ', $tmp);		
	}
	
}
