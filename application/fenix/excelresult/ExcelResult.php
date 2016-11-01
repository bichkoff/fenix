<?php
/**
 * Results report to Excel.
 *
 * @author Jvb 16 Feb 2016
 */

namespace application\fenix\excelresult;

use application\Excel;


class ExcelResult extends Excel {
	
	/**
	 * View output array from session
	 * @var array
	 */
	private $data;
	/**
	 * Instance of 
	 * @var ViewModel
	 */
	private $vm;
	/**
	 * Number of columns
	 * @var int
	 */
	private $cols;
	/**
	 * Number of table rows
	 * @var int
	 */
	private $rows;
	/**
	 * Number of the first row of the table
	 * @var int
	 */
	private $st = 2;


	public function __construct() {				
		$session = new \Lib\Session;
		
		parent::initPHPExcel();
		
		$this->objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(14);
		
		// dig out from session
		$this->data = $session->getValue('resultoutput');
		$this->vm	= $session->getValue('vmodel');
		
		$this->cols = count($this->vm->getUniqueAccs()) + 5; // + number of always present columns
		
		//$this->rows = count($this->data) + $this->st; // with header
	}

	/**
	 * Manual Excel columns titles setting for Journal use only
	 */
	public function setTitles() {
		$this->objPHPExcel->getActiveSheet()->setCellValue('A1', $this->vm->getHotelName());
		$this->objPHPExcel->getActiveSheet()->getStyle('A1', $this->vm->getHotelName())->getFont()->setBold(true);
		
		// first 5 columns - fixed number and content
		$this->objPHPExcel->getActiveSheet()
			->setCellValue('A'.$this->st, "From")
			->setCellValue('B'.$this->st, "Till")
			->setCellValue('C'.$this->st, 'Room Type')
			->setCellValue('D'.$this->st, "Meal")
			->setCellValue('E'.$this->st, "Nights");
				
		// now set the last (variable number of) columns' titles starting from after 'Nights'
		// see earlier versions of the file with Excel::exNum usage and $i starting from 6
		$i = 5; // NB: in setCellValueByColumnAndRow() columns numeration starts from 0
		
		foreach ($this->vm->getUniqueAccs() as $v) {			
			$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $this->st, $v);
			
			$i++;
		}
		
		$last_col = Excel::exNum($this->cols);		
		$this->objPHPExcel->getActiveSheet()->getStyle('A'. $this->st .':'. $last_col.$this->st)->getFont()->setBold(true);		
		
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(36);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
	}
	
	/**
	 * Iteration over tours array and setting cells' proper values.
	 */
	public function setContent() {
		
		$row = 1 + $this->st; // start from 2nd row
		
		foreach ($this->data as $r) {
			// set fixed cells
			$this->objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r->getFrom()->format('d.m.Y'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $r->getTo()->format('d.m.Y'));
			$this->objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $r->getRoom()->getName());
			$this->objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $r->getBoard());
			$this->objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $r->getNights());
			
			$col = 5;
			
			foreach ($this->vm->getUniqueAccs() as $a => $v) {
				$acc = $r->getAccByKey($a);
				
				if (! empty($acc) && $acc->getPrice() > 0) {
					$price = $acc->getPrice();
					
					$this->setCell($col, $row, $price);
				}				
				$col++;
			}			
			$row++; 
		}
	}
	
	/**
	 * Set column [$col, $row] value.
	 * 
	 * @param int $col Numbering starts from 0!
	 * @param int $row
	 * @param float $value Value to set
	 */
	private function setCell($col, $row, $value) {
		$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
	}
	
	/**
	 * File name setter/getter to use in Excel::export().
	 * NB: w/o extension!
	 * 
	 * @return string File name w/o extension
	 */
	protected function getFileName() {
		if ($this->vm->getOfferVal() == '-1') {
			return 'fenix_'. $this->vm->getHotelName() .'_Contract Rates_'. \date("dHis");
		}
		
		$hname = $this->vm->getHotelName();
		
		$offer = $this->vm->getOffer();
		
		$title = $offer->getTitle();
		$discount = 'ebb '. $offer->getDAdult();
		$valid = $offer->getVFrom()->format('d.m.Y') .'-'. $offer->getVTo()->format('d.m.Y');
		$book = 'bbd '. $offer->getBFrom()->format('d.m.Y') .'-'. $offer->getBTo()->format('d.m.Y');
		
		return 'fenix_'.$hname.'_'. $title.' '.$discount.'% '.$valid.' '.$book.'_'. \date("dHis");
	}	
	
}
