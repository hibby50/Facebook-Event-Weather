<?php

function getWeather($latitude,$longitude,$time)
{
$url="https://api.darksky.net/forecast/2054752949de3d214dd2451d237f886d/".$latitude.",".$longitude.",".$time."?exclude=[daily,alerts,currently,minutely,daily,flags]";

//  Initiate curl
$ch = curl_init();
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

// Will dump a beauty json :3
$data = (json_decode($result, true));

$startHour = $data["hourly"]["data"][0];

$startHour['daySummary'] = $data["hourly"]["summary"];

return $startHour;
}
?>
