<?php
/**
 * Results report to Excel.
 *
 * @author Jvb 15 Mar 2016
 */

namespace application\fenix\stopsalesexcel;

use application\Excel;


class ExcelResult extends Excel {
	
	/**
	 * View output array from session
	 * @var array
	 */
	private $data;
	/**
	 * Number of table columns
	 * @var int
	 */
	private $cols = 8;
	/**
	 * Number of the first row of the table
	 * @var int
	 */
	private $st = 1;


	public function __construct() {				
		$session = new \Lib\Session;
		
		parent::initPHPExcel();
		
		$this->objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(14);
		
		// dig out from session
		$this->data = $session->getValue('stopsales');
		
		// supposedly, session expired
		if (empty($this->data)) {
			die;
		}
	}

	/**
	 * Manual Excel columns titles setting for Journal use only
	 */
	public function setTitles() {
		// first 5 columns - fixed number and content
		$this->objPHPExcel->getActiveSheet()
			->setCellValue('A'.$this->st, "Hotel")
			->setCellValue('B'.$this->st, 'Date Beg')
			->setCellValue('C'.$this->st, "Date End")
			->setCellValue('D'.$this->st, "Nights")
			->setCellValue('E'.$this->st, "Room")
			->setCellValue('F'.$this->st, "Accommodation")
			->setCellValue('G'.$this->st, "Meal")
			->setCellValue('H'.$this->st, "Created");
				
		$last_col = Excel::exNum($this->cols);		
		$this->objPHPExcel->getActiveSheet()->getStyle('A'. $this->st .':'. $last_col.$this->st)->getFont()->setBold(true);		
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(44);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(36);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(24);
	}
	
	/**
	 * Iteration over tours array and setting cells' proper values.
	 */
	public function setContent() {
		$row = 1 + $this->st; // start from 2nd row
		
		foreach ($this->data as $r) {
			// set fixed cells
			$this->objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r->getHotel());
			$this->objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $r->getFrom()->format('d.m.Y'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $r->getTo()->format('d.m.Y'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('D'.$row, '');
			$this->objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $r->getRname());
			$this->objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'All');
			$this->objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'All');
			$this->objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $r->getCreated()->format('d.m.Y H:i:s'));
			
			$row++;
		}
	}
	
	/**
	 * File name setter/getter to use in Excel::export().
	 * NB: w/o extension!
	 * 
	 * @return string File name w/o extension
	 */
	protected function getFileName() {
		return 'fenix_StopSales_'. \date("dHis");
	}	
	
}
