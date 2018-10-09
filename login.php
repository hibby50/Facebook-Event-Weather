<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$user = $_POST["user"];
$password = $_POST["password"];

/*if (mysqli_num_rows($result) > 0) {
    echo "Login Success";
    }
    else{
    echo "<img src='giphy.gif' alt='WRONG'>";
    }
*/

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = "login";
$request['username'] = $user;
$request['password'] = $password;
//$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

if ($response == 1){
    echo "login success.";
}
else{
    echo "login failure" . "<img src='/giphy.gif' alt='error'>";
}

echo $argv[0]." END".PHP_EOL;

?>


