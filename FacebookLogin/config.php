<?php
    session_start();
    require_once "Facebook/autoload.php";

    $FB = new \Facebook\Facebook([
        'app_id' => '269392957048586',
        'app_secret' => 'eac95a92cef231db39240c3b4f203da8',
        'default_graph_version' => 'v2.10'
    ]);

    $helper = $FB -> getRedirectLoginHelper();


?>
