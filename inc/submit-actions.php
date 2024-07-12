<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/resources/database_connection.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';

use Twilio\Rest\Client;

session_start();

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

// Send New Message
if(isset($_POST['send_message_to_new_contact'])){
    // From And To Number
    $from_number = '+18334630495';
    $to_number = $_POST['to_number'];
    if($_POST['texting_mode'] === 'whatsapp'){
        $from_number = $_POST['texting_mode'].':+18334630495';
        $to_number = $_POST['texting_mode'].':'.$_POST['to_number'];
    }

    $status = insert_contact_number($conn, $to_number);

    if(!$status['status']){
        $_SESSION['error_message'] = 'Existing Contact. Send message from the list for this number.';
    }

    if($status['status']){
        $client = new Client($sid, $token);
        send_message($client, $from_number, $to_number, $_POST['text_area']);
        store_contact_message($conn, $status['data'], $_POST['text_area'], 0, 1);
        unset($_SESSION['error_message']);
        $_SESSION['success_message'] = 'Message has been sent to '.$_POST['to_number'];
    }
    unset($_POST['send_message_to_new_contact']);
    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");
}