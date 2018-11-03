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

	  $humanStartTime = $startTime->format('Y-m-d H:i:s T');//[YYYY]-[MM]-[DD][HH]:[MM]:[SS][timezone]
	  $unixStartTime = $startTime->format('U');

	  if (isset($zip)){

	  	$weather=getWeather($latitude,$longitude,$unixStartTime);
	}else{
	$weather="No Location Data Available";
	}

	  $currEvent = array("description"=>$description,"name"=>$name,"startTime"=>$humanStartTime,"rsvpStatus"=>$rsvpStatus,"latitude"=>$latitude,"longitude"=>$longitude,"zip"=>$zip,"weather"=>$weather);

	  array_push($parsedEvents,$currEvent);

	  $i++;
}
echo $parsedEvents[0]['description'];
echo $parsedEvents[0]['weather']['summary'];

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
 <!doctype html>
 <html lang="en">
 <head>
   <meta charset="UTF-8">
     <meta name="viewport"
         content="width-device-width, user-scalable=no, initial-scale=1.0">
         <meta http-equiv="X-UA-Compatible" content="ie-edge">
             <title>FB User</title>

             <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
             integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
             </head>

             <body>
               <!-- <div class="container">
                 <div class="row justify-content-center">
                   <div class="col-md-9"> -->
<div class="container">
 <table class="table table-hover table-bordered">


     <?php foreach($parsedEvents as $event)
     {
     ?>

       <thead class="thead-dark"><tr><th colspan="2"><?= $event['name']?></th></tr></thead>
   <tbody>
     <tr>
       <td>Description</td>
       <td><?= $event['description']?></td>
     </tr>
     <tr>
       <td>Start Time</td>
       <td><?= $event['startTime']?></td>
     </tr>
     <thead><tr><th colspan="2">Weather</th></tr></thead>
     <tr>
       <td>Day Summary</td>
       <td><?= $event['weather']['daySummary']?></td>
     </tr>
     <tr>
       <td>Temperature (Fahrenheit)</td>
       <td><?= $event['weather']['temperature']?></td>
     </tr>
   </tbody>

   <?php } ?>

 </tbody>
 </table>

</div>
</body>
</html>
