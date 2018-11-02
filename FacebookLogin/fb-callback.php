<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('getWeather.php');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

  require_once "config.php";

  try{
    $accessToken=$helper->getAccessToken();
  }catch (\Facebook\Exceptions\FacebookResponseException $e){
    echo "Response Exception: " . $e->getMessage();
    exit();
  }catch (\Facebook\Exceptions\FacebookSDKException $e){
    echo "SDK Exception: " . $e->getMessage();
    exit();
  }

if(!$accessToken){
  header( 'Location: login.php');
  exit();
}

$oAuth2Client = $FB->getOAuth2Client();
if (!$accessToken->isLongLived())
  $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

  $response = $FB->get("/me?fields=id,name,events", $accessToken);
  $userData = $response->getGraphNode()->asArray();
  echo "<pre>";

  $events=$userData["events"];
  
  $i = 0; /* for illustrative purposes only */

  $parsedEvents = array();
  foreach ($events as $info) {
	  
	  $description=$info["description"];
	  $name=$info["name"];
	  $rsvpStatus=$info["rsvp_status"];
	  $place=$info["place"];
	  $location = $place["location"];
	  $latitude= (string) $location["latitude"];
	  $longitude= (string)$location["longitude"];
	  $zip= $location["zip"];

	  $startTime=$info["start_time"];
	  $startTime = $startTime->format('Y-m-d H:i:s');
	  
	  if (isset($zip)){

	  	$weather=getWeather($latitude,$longitude);
	}else{
	$weather="No Location Data Available";
	}

	  $currEvent = array("description"=>$description,"name"=>$name,"startTime"=>$startTime,"rsvpStatus"=>$rsvpStatus,"latitude"=>$latitude,"longitude"=>$longitude,"zip"=>$zip,"weather"=>$weather);

	  array_push($parsedEvents,$currEvent);

	  $i++;
}

var_dump($parsedEvents);

  $request = array();
  $request['type'] = "facebookToken";
  $request['fbID'] = $userData["id"];
  $request['token'] = (string)$accessToken;

  //$request['message'] = $msg;
  $response = $client->send_request($request);
  //$response = $client->publish($request);

  echo "client received response: ".PHP_EOL;
  print_r($response);
  echo "\n\n";
  // $_SESSION['userData'] = $userData;
  // $_SESSION['access_token'] = (strong) $accessToken;
  // header(string:'Location: index.php');
  // exit();

 ?>
<html>
<body>
<table>
    <tbody>
    <tr><?php echo $userData["id"];?></tr>
    <tr><?php echo $userData["first_name"];?></tr>
    <tr><?php echo $userData["last_name"];?></tr>
    <tr><?php echo $userData["email"];?></tr>
    </tbody>
    </table>

