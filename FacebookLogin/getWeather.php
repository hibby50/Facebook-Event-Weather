<?php

function getWeather($latitude,$longitude)
{
$url="https://api.darksky.net/forecast/2054752949de3d214dd2451d237f886d/".$latitude.",".$longitude;

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
return(json_decode($result, true));
}
?>
