<?php
require_once('logProducer.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = mysqli_connect("127.0.0.1", "test", "1234", "test");

if(!$db) {
	$error = "Error: unable to connect to mysql" . PHP_EQL;
		doLog($error);
	$error = "Debugging errno: " . mysqli_connect_error() . PHP_EOL;
		doLog($error);
	$error = "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		doLog($error);
	exit;
}

$user = $_POST["user"];
$password = $_POST["password"];

$result = mysqli_query($db,"SELECT username, password FROM users WHERE `username` = '".$user."' AND `password`= '".$password."'");

if (mysqli_num_rows($result) > 0) {
    echo "Login Success";
	session_start(); //new line
	$_SESSION["user"] = $_POST["user"]; //new line
    }
    else{
    echo "<img src='giphy.gif' alt='WRONG'>";
    }
?>
