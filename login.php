<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = mysqli_connect("127.0.0.1", "test", "1234", "test");

if(!$db) {
	echo "Error: unable to connect to mysql" . PHP_EQL;
	echo "Debugging errno: " . mysqli_connect_error() . PHP_EOL;
	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}

$user = $_POST["user"];
$password = $_POST["password"];

$result = mysqli_query($db,"SELECT username, password FROM users WHERE `username` = '".$user."' AND `password`= '".$password."'");

if (mysqli_num_rows($result) > 0) {
    echo "Login Success";
    }
    else{
    echo "<img src='giphy.gif' alt='WRONG'>";
    }
?>
