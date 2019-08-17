<?php

require 'vendor/autoload.php';

// FB token
$hubVerifyToken = 'fb_bot';
$accessToken =   "EAAGPZBz7N3GMBAJ2gZBaxlznfL11qbtYmZCcoI9TiRTzlp4t2m4lltCe9MiuOTfdB7H6ZBa2WnNrwn9QH4b3aCNHTMPm5HT6LdGPice49hD9qsGSQcZBOHcnt6QEXg2kIZA0bgczj7JZAnJ9fngrLbRieXJrikUOfEZANbtSbiyV6dMCFhGEEr4I";

// check FB token
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
$response = null;

if($messageText == "Hi") {
    $answer = "Hello";
}

$response = [
    'recipient' => [ 'id' => $senderId ],
    'message' => [ 'text' => $answer ]
];

$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    if(!empty($input)){
        $result = curl_exec($ch);
    }
curl_close($ch);

// coding