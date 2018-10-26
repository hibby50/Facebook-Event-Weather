<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLog($error)
{
$client = new rabbitMQClient("rabbitMQLogging.ini","testServer");
$msg = $error;


$request = array();
$request['message'] = $msg;
//$response = $client->send_request($request);
$client->publish($request);
//file_put_contents("log.log", Date.$msg, FILE_APPEND); append a log file
}
