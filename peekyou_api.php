<?php
//Peekyou class used in an effort for providing ease of use for our peekyou api.
class peekyou{

	private $api_key;
	private $frequency=5;//default frequency in seconds
	private $api_version=3;//set default api version to use
	private $app_id;
	
	/**
	 * Sets the api key.
	 *
	 * @param string $key
	 * @return none
	 *
	 */
	public function set_key($key){
		$this->api_key=urlencode($key);
	}

	/**
	 * Sets the app key.
	 *
	 * @param string $key
	 * @return none
	 *
	 */
	public function set_app_id($id){
		$this->app_id=urlencode($id);
	}
	
	/**
	 * Sets the frequency,
	 * Controls the rate a user would like to check peekyou api for status
	 * @param int $value
	 * @return none
	 *
	 */
	public function set_frequency($value){
		$this->frequency=$value;
	}

	/**
	 * Gets url information from peekyou social audience api for a given url(For example http://twitter/[username]
	 *
	 * @param string $url
	 * @param string $type (stdclass,json,xml)
	 * @return string representing json,or xml,if paremeter type was array then php array is returned.
	 *
	 */
	public function get_social_audience_info($url,$type){
		$url=trim($url);
		$type=trim(strtolower($type));
		$flag=false;

		if($type!="array" && $type!="json" && $type!="xml")
		return "Invalid type!!\n";

		if($type=="array"){
			$type="json";
			$flag=true;
		}

		$url="http://api.peekyou.com/analytics.php?key=".$this->api_key."&url=".$url."&output=".$type."&app_id=".$this->app_id;
		
		while(($result=$this->check_status($url,$type))==-1)
		sleep($this->frequency);

		if($flag){
			$temp=json_decode($result,true);
			if(!empty($temp))
			$result=$temp;
		}
		return $result;
	}

	/**
	 * Gets url information from peekyou social consumer api for a given url(For example http://twitter/[username]
	 *
	 * @param string $url
	 * @param string $type (stdclass,json,xml)
	 * @return string representing json,or xml,if paremeter type was array then php array is returned.
	 *
	 */
	public function get_social_consumer_info($url,$type){
		$url=trim($url);
		$type=trim(strtolower($type));
		$flag=false;

		if($type!="array" && $type!="json" && $type!="xml")
		return "Invalid type!!\n";

		if($type=="array"){
			$type="json";
			$flag=true;
		}

		$url="http://api.peekyou.com/api.php?key=".$this->api_key."&url=".$url."&apiv=".$this->api_version."&output=".$type."&app_id=".$this->app_id;
		
		while(($result=$this->check_status($url,$type))==-1)
		sleep($this->frequency);

		if($flag){
			$temp=json_decode($result,true);
			if(!empty($temp))
			$result=$temp;
		}
		return $result;
	}

	/**
	 * Checks the status return by peekyou api.
	 *
	 * @param string $url
	 * @param string $type
	 * @return If status is 1 then -1 is return implying search is still active on peekyou,otherwise any other status results is returned.
	 *
	 */
	private function check_status($url,$type){

		$result=trim($this->curl_exec($url));

		if(empty($result))
		return "API down. Try again later\n";

		$result=trim(str_ireplace(array("[set_cache][340]","<br>"),"",$result));
			
		if($type=="json"){
			$json=json_decode($result,true);
			$status=$json['results']['status'];
		}
		else{
			$xml=new SimpleXMLElement($result);
			$status=$xml->xpath('status');
			$status=$status[0];
		}


		if($status==1)
		return -1;
		else
		return $result;

	}


	/**
	 * Returns html from $url link
	 *
	 * @param string $url
	 * @return string containing html from $url location.
	 *
	 */
	private function curl_exec($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$results=curl_exec($ch);
		curl_close($ch);
		return $results;
	}
}
?>
