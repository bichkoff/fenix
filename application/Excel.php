<?php
/**
 * Excel class: all procedures for working with PHPExcel library.
 *
 * @author Jvb 31 Mar 2014
 * @version 0.2 08.07.2014
 */

namespace application;

// this class has no namespace
require_once __DIR__ .'/../vendor/phpexcel/PHPExcel.php';


abstract class Excel {

	/**
	 * Instance of a class
	 * @var \PHPExcel
	 */
	protected $objPHPExcel;
	/**
	 * Headers: see implementation.
	 */
	abstract protected function getFileName();
	/**
	 * Document titles - the first row
	 */
	abstract protected function setTitles();
	/**
	 * Document contents
	 */
	abstract protected function setContent();
	/**
	 * Excel numbers converting string (used as array)
	 * @var string
	 */
	private static $abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	
	public function initPHPExcel() {
		$this->objPHPExcel = new \PHPExcel(); // ms export library
		$this->objPHPExcel->setActiveSheetIndex(0);

		$this->objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.1);
		$this->objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.1);
		$this->objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.1);
		$this->objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.1);
		$this->objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(9);
	}

	/**
	 * Recursive func, converting numbers to base-26-Excel-style numbers.
	 * This variant works with Excel numbering system (starting from 1).
	 * Ex., 26 outputs 'Z'. 
	 * 
	 * @param int $n
	 * @param string $s
	 * @return string
	 */
	public static function exNum($n, $s = '') {
		if (! $n) {
			return false;
		}

		$num = $n - 1;

		$i = (int) ($num / 26);
		$r = (int) fmod($num, 26);
		$s = self::$abc[$r] . $s;

		if ($i < 1) {
			return $s;
		} else {
			return self::exNum($i, $s);
		}
	}

	/**
	 * Recursive func, converting numbers to base-26-Excel-style numbers.
	 * This variant works with array keys (starting from 0; cf: Excel columns start from 1 or 'A').
	 * Ex., 26 outputs 'AA'. 
	 * 
	 * @param int $n
	 * @param string $s
	 * @return string
	 */
	public static function num26($n, $s = '') {
		$i = (int) ($n / 26);
		$r = (int) fmod($n, 26);
		
		$s = self::$abc[$r] . $s;

		if ($i < 1) {
			return $s;
		} else {
			return self::num26($i - 1, $s);
		}
	}
	
	/**
	 * The only function for use in a Controller to make Browser save the file.
	 */
	public function export() {
		$f_name = $this->getFileName();
		
		\header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
		\header('Content-Disposition: attachment; filename="'.$f_name.'.xlsx"');
		\header('Cache-Control: max-age=0');

		//$objWriter = new \PHPExcel_Writer_Excel2007($this->objPHPExcel);
		$objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	/**
	 * Set Excel file properties from array.
	 * 
	 * @param array $props
	 */
	public function setProperties($props = ['', '', '', '', '', '']) {
		$this->objPHPExcel->getProperties()
			->setCreator($props[0])
			->setTitle($props[1])
			->setSubject($props[2])
			->setDescription($props[3])
			->setKeywords($props[4])
			->setCategory($props[5]);
	}

	/**
	 * Array to string implode by key, using given separator.
	 * 
	 * @param array $arr Array to implode
	 * @param string $key Array key to get imploded values
	 * @param string $glue Separator
	 * @param int $len Output length. If 0 - don't crop the contents
	 * @return string $glue separated string
	 */
	public static function aToStr(Array $arr, $key, $glue = " ", $len = 0) {
		$c = [];
		foreach ($arr as $v) {
			$c[] = $len ? mb_substr($v[$key], 0, $len) : $v[$key];
		}
		return implode($glue, $c);
	}

	/**
	 * Russian number by words.
	 * 
	 * @author runcore
	 * @uses morph(...)
	 */
	public static function num2str($number) {
		$num = abs($number);
		
		$nul = 'ноль';

		$ten = [
			['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
			['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
		];

		$a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
		$tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
		$hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];

		$unit = [// Units
			['копейка', 'копейки', 'копеек', 1],
			['рубль', 'рубля', 'рублей', 0],
			['тысяча', 'тысячи', 'тысяч', 1],
			['миллион', 'миллиона', 'миллионов', 0],
			['миллиард', 'милиарда', 'миллиардов', 0],
		];

		list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));

		$out = [];

		if (intval($rub) > 0) {
			foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
				if (! intval($v)) {
					continue;
				}
				
				$uk = sizeof($unit) - $uk - 1; // unit key
				$gender = $unit[$uk][3];
				
				list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				
				if ($i2 > 1) {
					$out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];# 20-99
				} else {
					$out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];# 10-19 | 1-9
				}
				// units without rub & kop
				if ($uk > 1) {
					$out[] = self::morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
				}
			} 
		} else {
			$out[] = $nul;
		}
		
		$out[] = self::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
		$out[] = $kop .' '. self::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
		
		return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
	}

	/**
	 * Bend the form.
	 * 
	 * @author runcore
	 */
	public static function morph($n, $f1, $f2, $f5) {
		$n = abs(intval($n)) % 100;
		
		if ($n > 10 && $n < 20) {
			return $f5;
		}
		
		$n = $n % 10;
		
		if ($n > 1 && $n < 5) {
			return $f2;
		}
		
		if ($n == 1) {
			return $f1;
		}
		
		return $f5;
	}

}
