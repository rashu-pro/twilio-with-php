<?php
require_once 'vendor/autoload.php';
use Twilio\Rest\Client;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$sid = $_ENV['TWILIO_ACCOUNT_SID'];
$token = $_ENV['TWILIO_AUTH_TOKEN'];
$from_number = "+18334630495";
$from_number_whatsapp = "whatsapp:+18334630495";
$to = "+19499293776";
$to_me = "+8801643177674";
$to_me_whatsapp = "whatsapp:+8801643177674";
$to_alex_whatsapp = "whatsapp:+19499293776";

// Array of recipient WhatsApp numbers
$recipients = [
//    'whatsapp:+19496772261',
    $to_me_whatsapp,
];
$message_body = "Welcome to We Do Cabo Fun!";

$client = new \Twilio\Rest\Client($sid, $token);
$to_number = 'whatsapp:'.$_POST['to_number'];
$message_body = "Thank you for the order!";
echo send_message($client, $from_number_whatsapp, $to_number, $message_body);

$response = [];
foreach ($recipients as $to_whatsapp) {
    // echo send_message($client, $from_number_whatsapp, $to_whatsapp, $message_body);
}

// Use the Client to make requests to the Twilio REST API
/**
 * Sends a WhatsApp message using Twilio.
 *
 * @param Client $client The Twilio client instance.
 * @param string $from The sender's WhatsApp number.
 * @param string $to The recipient's WhatsApp number.
 * @param string $body The message body.
 * @return string $message
 */
function send_message($client, $from, $to, $body) {
    $message = false;
    try {
        $response = $client->messages->create(
            $to,
            [
                'from' => $from,
                'body' => $body
            ]
        );
        $message = "Message sent to $to | Response id: $response->sid\n";
    } catch (Exception $e) {
        $message = "Failed to send message to $to: " . $e->getMessage() . "\n";
    }
    return $message;
}