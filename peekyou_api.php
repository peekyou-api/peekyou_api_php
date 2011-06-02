<?php
//Peekyou class used in an effort for providing ease of use for our peekyou api.
class peekyou{
	private $api_key;
	private $frequency=5;//default frequency in seconds

 /**
     * Sets the private key.
     *
     * @param string $key
     * @return none
     * 									
     */
public function set_key($key){
	$this->api_key=$key;
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
     * Gets url information from peekyou_api(For example http://www.peekyou.com/[username]
     * 
     * @param string $url
     * @param string $type (stdclass,json,xml)
     * @return string representing json,or xml,if paremeter type was stdclass then stdclass object is returned.
     * 									
     */
public function get_url($url,$type){
	$type=trim(strtolower($type));
	$flag=0;
	if($type!="stdclass" && $type!="json" && $type!="xml")
	return "Invalid type!!\n";
	if($type=="stdclass"){
	$flag=1;
	$type="json";	
	}
	$url="http://api.peekyou.com/analytics.php?key=".$this->api_key."&url=".$url."&output=".$type."";	
	while(($result=$this->check_status($url,$type))==-1)
	sleep($this->frequency);
	
    if($flag){
        $temp=json_decode($result);
        if(empty($temp))
        return $result;
    return $temp;
    }
    return $result;
}

/**
     * For testing purposes used to print out current key
     * 
     * 
     * @return api_key
     * 									
     */
public function echo_key(){
	
	echo $this->api_key;
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
	
	$result=$this->curl_exec($url);

	if(empty($result))
	return "API down. Try again later\n";	

	if($type=="json"){
	$json=json_decode($result);
	$status=$json->results->status;
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
