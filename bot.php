<?php

// chatbot for Facebook 
// author: Daniel Kozina

require 'vendor/autoload.php';

// FB token
$hubVerifyToken = 'fb_bot';
$accessToken =   "EAAGPZBz7N3GMBAJ7MR4B87hLCieg819bZBkmAC5xOUxDsUZCPBZAPuRXnqFemcrcq0ZASe8QZAHGEbS8JwnrINkHs06ZCIAyG90wXXgUZAQtbwHZA3fcEaRQL6TBsZBigbc0L7pE3VHlkuWlSMsKekYrGeoYbLc3RiyGcvt2iFhyZCp9vAsROHYOiJu";

// check FB token
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
$response = null;

if($messageText == "Hi" || $messageText == 'hi') {
    $answer = "Hello";
}

$file = 'error.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= $senderId;
// Write the contents back to the file
file_put_contents($file, $current);
$json_data = json_encode($input);
file_put_contents('myfile.json', $json_data);

$response = [
    'recipient' => [ 'id' => $senderId ],
    'message' => [ 'text' => $answer ]
];

$ch = curl_init('https://graph.facebook.com/v4.0/me/messages?access_token='.$accessToken);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    if(!empty($input)){
        $result = curl_exec($ch);
    }
curl_close($ch);

// coding