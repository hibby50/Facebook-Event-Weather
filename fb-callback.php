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
  var_dump($userData);
  echo $accessToken;

  $events=userData["events"];
  $description=$events["description"];
  $name==$events["name"];
  //$startTime=$events["start_time"];
  $rsvpStatus=$events["rsvp_status"];
  $location=$events["location"];
  $zip==location["zip"];
  
  $weather=getWeather($zip);
  
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
