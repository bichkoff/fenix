<?php
/**
 * Data retrieval from fenixtours.gr.
 * Fenix project.
 * 
 * @author Jvb 26 Jan 2016
 * 
 */

namespace application\fenix\getfenix;

use Lib\IController;
//use application\Db;


class Controller implements IController {
	
	private $vmodel;


	public function __construct(ViewModel $vmodel = null) {
		//change the maximum execution time
		\ini_set('max_execution_time', 1200);
		
		$this->vmodel = $vmodel;
    }
	
	/**
	 * Main action that does all. 
	 * In this case - retrieves data from tourindex.ru and saves it in db.
	 * 
	 * @return type
	 */
	public function action() {
		
		$curl = new \application\fenix\Curl;
		$auth = new \application\fenix\Auth($curl);
		
		if (! $auth->run()) {
			return false;
		}
		
		$third = (int) $this->vmodel->getRequest()->getThird(); // third argument; stopsales only
		
		// list of Fenix funcs to execute; corresponding tables will be populated
		if ($third) { // retrievals to execute daily (see crontab)
			$funcs = ['Stopsales', ];
		} else { // full set; execute weekly 
			$funcs = [
//				'Countries',
				'Places',
				'Hotels',
				'Stopsales',
			];
		}
		
		$this->vmodel->deletions($funcs);
		
		foreach ($funcs as $v) {
			$cl = __NAMESPACE__ .'\\'. $v;			
			$instance = new $cl($curl, $auth);			
			$instance->run();
			
			$objs[$v] = $instance;
		}
		
		if (! $third) {
			// 2. Get other data; processing a bit different here
			$rooms = new Rooms($curl, $auth);		
			$rooms->setHotels($objs['Hotels']);		
			$rooms->run();
			$objs['Rooms'] = $rooms;
		}		
			
		// make insertions now for the retrieved data
		$this->vmodel->insertions($objs);
	}
	
}
