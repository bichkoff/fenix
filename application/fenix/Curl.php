<?php

namespace application\fenix;


class Curl {
	
	/**
	 * URL to access with cURL
	 * @var string
	 */
	private $url;
	/**
	 * Array of key => values to POST
	 * @var array
	 */
	private $post_fields = [];
	/**
	 * Just a User Agent
	 * @var string
	 */
    private $useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36";


	public function __construct() {
		
    }
	
	public function setUrl($param) {
		$this->url = $param;
	}
    
	public function setPostFields(array $param) {
		$this->post_fields = $param;
	}
    
	public function setUserAgent($param) {
		$this->useragent = $param;
	}
	
	/**
	 * Helper to use with Fenix requests.
	 * 
	 * @param array $param
	 */
	public function setFenixPost(array $param) {
		$this->post_fields = [
			'request' => \json_encode($param),
		];
	}
    
	/**
	 * cURL POST request.
	 * 
	 * @return string
	 */
	public function curlPost() {
		if (empty($this->url) || empty($this->post_fields)) {
			return '';
		}
	
		$options = [
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_FAILONERROR		=> true,
			CURLOPT_FOLLOWLOCATION	=> true,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_USERAGENT		=> $this->useragent,
			CURLOPT_URL				=> $this->url,
			CURLOPT_POST			=> true,
			CURLOPT_POSTFIELDS		=> \http_build_query($this->post_fields),
		];

		$ch = \curl_init();	// Initialising cURL session

		\curl_setopt_array($ch, $options);
		$data = \curl_exec($ch);	// Executing cURL session
		\curl_close($ch);	// Closing cURL session

		return $data;
	}
       
}
