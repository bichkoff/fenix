<?php
/**
 * Common structure for Fenix Functions.
 * Fenix project.
 * 
 * @author Jvb 26 Jan 2016
 * 
 */

namespace application\fenix;


interface IFFunc {
	const COMPANY = 2;
	
	public function run();
	public function setTable($param);
	public function getTable();
	public function processStd($std);
	public function getCls();
	public function getClass();
	public function getCheckPrimary();
}
