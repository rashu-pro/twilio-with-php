<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/resources/database_connection.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';

use Twilio\Rest\Client;

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

$sid = $_ENV['TWILIO_ACCOUNT_SID'];
$token = $_ENV['TWILIO_AUTH_TOKEN'];

if(isset($_POST['chat_response'])){

    $client = new Client($sid, $token);

    $from_number = '+18334630495';
    if(strpos($_POST['to_number'], ':')){
        $from_number = "whatsapp:+18334630495";
    }
    send_message($client, $from_number, $_POST['to_number'], $_POST['chat_response_text']);
    store_contact_message($conn, $_POST['id'], $_POST['chat_response_text'], 0, 1);
    unset($_POST['chat_response']);

    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");
}