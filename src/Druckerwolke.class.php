<?php

function Druckerwolke($username, $password, $api_key){
	return new Druckerwolke($username, $password, $api_key);
}

class Druckerwolke{
	
	private $username = '';
	private $password = '';
	private $api_key = '';
	
	const BASE_URL = 'https://app.druckerwolke.de/apiv1/';
		
	public function __construct($username, $password, $api_key){
		$this->username = $username;
		$this->password = $password;
		$this->api_key = $api_key;
	}
	
	private function call_api($method = "GET", $endpoint, $data = NULL){
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => self::BASE_URL.$endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_USERPWD => $this->username.':'.$this->password,
			CURLOPT_HTTPHEADER => array(
				"X-Api-Key-DruckerWolke: {$this->api_key}",
				'Content-Type: application/json',
			),
		));
		
		if($data !== NULL){
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		}
		
		$response = curl_exec($curl);

		$http_code = curl_getinfo($curl)['http_code'];
        if($http_code < 200 or $http_code >= 300){
			
			$info = json_encode(curl_getinfo($curl)).'<br><br>'.json_encode($response);
			throw new Exception($info);
        }
		
		curl_close($curl);
		
		return json_decode($response);
	}
	
	public function printers(){
		return $this->call_api("GET", "/input"); // returns array of printers
	}
	
	public function add_document($data){
		return $this->call_api("POST", "/jobs", $data); // returns result object
	}
	
	public function status(){
		return $this->call_api("GET", "/general/status"); // returns status object
	}
	
	

}

?>
