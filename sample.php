<?php
// Sample of code using peekyou_api
require_once("peekyou_api.php");
$test=new peekyou();
$test->set_key("YOUR API KEY GOES HERE");
//In seconds.
$test->set_frequency(1);
//second parameter for get_url can be xml,json,or stdclass
print_r($test->get_url("http://twitter.com/husasdfsaeymichael","xml"));

?>