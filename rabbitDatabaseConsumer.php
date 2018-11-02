#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logProducer.php');

ini_set("log_errors", 1);
ini_set("error_log", "/tmp/databaseRabbit.log");
error_log("log init");

function doLogin($username,$password)
{
    $db = mysqli_connect("127.0.0.1", "test", "1234", "test");
    
    $username = mysqli_real_escape_string($db, $username); 
    $password = mysqli_real_escape_string($db, $password);
    
    mysqli_real_escape_string($db, $username);

	if(!$db)
	{
		$error = "Error: unable to connect to mysql" . PHP_EQL;
		doLog($error);
		$error = "Debugging errno: " . mysqli_connect_error() . PHP_EOL;
		doLog($error);
		$error = "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		doLog($error);
		return false;
	}

	$result = mysqli_query($db,"SELECT username, password FROM users WHERE `username` = '".$username."' AND `password`= '".$password."'");

	if (mysqli_num_rows($result) > 0)
	{
	    return true;
	}

	return false;
}

function doSignUp($username,$password,$email)
{

	$db = mysqli_connect("127.0.0.1", "test", "1234", "test");
	
    $username = mysqli_real_escape_string($db, $username); 
    $password = mysqli_real_escape_string($db, $password);
    $email = mysqli_real_escape_string($db, $email);

	if(!$db)
	{
		echo "Error: unable to connect to mysql" . PHP_EQL;
		echo "Debugging errno: " . mysqli_connect_error() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		return false;
	}
	
	$checkIfExists = mysqli_query($db,"SELECT username FROM users WHERE `username` = '".$username."'");

	if (mysqli_num_rows($checkIfExists) > 0)
	{
	    return false;
	}

	$result = mysqli_query($db,"INSERT INTO users (username,password,email) VALUES ('".$username."', '".$password."', '".$email."')");

	echo $result;

	if ($result)
	{
	    return true;
	}
return false;
}

function storeToken($user,$token)
{
$db = mysqli_connect("127.0.0.1", "test", "1234", "test");
	if(!$db)
	{
		$error = "Error: unable to connect to mysql" . PHP_EQL;
		doLog($error);
		$error = "Debugging errno: " . mysqli_connect_error() . PHP_EOL;
		doLog($error);
		$error = "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		doLog($error);
		return false;
	}

	$result = mysqli_query($db,"INSERT INTO tokens (user,fb) SELECT * FROM (SELECT '".$user."') AS tmp WHERE NOT EXISTS (SELECT user FROM tokens WHERE user='$user'");
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "signup";
        return doSignUp($request['username'],$request['password'],$request['email']);
    case "validate_session":
      return doValidate($request['sessionId']);
    case "facebookToken":
        return storeToken($request['token']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("rabbitDatabaseConsumer.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>
