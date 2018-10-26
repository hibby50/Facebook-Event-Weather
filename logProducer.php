<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLog($error)
{
$client = new rabbitMQClient("rabbitMQLogging.ini","testServer");

$client->publish($error);
}
