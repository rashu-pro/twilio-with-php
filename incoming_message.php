<?php
require 'vendor/autoload.php';
use Twilio\TwiML\MessagingResponse;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve the incoming message data from the request
$from = $_POST['From'];
$body = $_POST['Body'];

// Log the message for debugging purposes
error_log("Received message from $from: $body");

// Respond to Twilio with a simple TwiML message (optional)
$response = new MessagingResponse();
if($body === 'cabo' || $body === 'Cabo' || $body === 'CABO'){
    $response->message($_ENV['WHATSAPP_MESSAGE_BODY']);
    // Send the response back to Twilio
    header("Content-Type: text/xml");
    echo $response;
}
