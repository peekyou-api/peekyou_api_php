<?php
// Sample of code using peekyou_api
require_once("peekyou_api.php");
$test=new peekyou();
$test->set_key("YOUR API KEY GOES HERE");
$test->set_app_id("YOUR APP ID GOES HERE");
//In seconds.
$test->set_frequency(1);
//second parameter for get_url can be xml,json,or stdclass
print_r($test->get_social_audience_info("www.twitter.com/michaelhussey","json"));
print_r($test->get_social_consumer_info("www.twitter.com/michaelhussey","json"));
?>
