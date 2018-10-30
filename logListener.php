#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logProducer.php');

ini_set("log_errors", 1);
ini_set("error_log", "/tmp/databaseRabbit.log");
error_log("log init");

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  echo var_dump($request);
  file_put_contents("log.log", $request, FILE_APPEND);
}

$server = new rabbitMQServer("rabbitMQLogging.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

