<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once 'resources/database_connection.php';
require_once 'inc/functions.php';
use Twilio\Rest\Client;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$sid = $_ENV['TWILIO_ACCOUNT_SID'];
$token = $_ENV['TWILIO_AUTH_TOKEN'];
$client = new Client($sid, $token);

$contact_details_by_id = fetch_contacts_by_id($conn, $_GET['contact_id']);

$from_number = $contact_details_by_id[0]['contact_number'];
$is_whatsapp_number = false;
if(strpos($contact_details_by_id[0]['contact_number'], ':')){
    $from_number = "whatsapp:+18334630495";
    $is_whatsapp_number = true;
}

if(isset($_POST['chat_response'])){
    send_message($client, $from_number, $contact_details_by_id[0]['contact_number'], $_POST['chat_response_text']);
    store_contact_message($conn, $contact_details_by_id[0]['id'], $_POST['chat_response_text'], 0, 1);
}

$threads = fetch_threads_by_id($conn, $_GET['contact_id']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Details</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet">

</head>
<body>
<div class="my-5">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-8">

                <div>
                    <?php if($is_whatsapp_number): ?>
                        <p class="font-monospace text-success text-uppercase m-0">whatsapp</p>
                    <?php endif; ?>
                    <h4 class="card-title">
                        <?php
                        if($is_whatsapp_number){
                            echo str_replace('whatsapp:', '', $contact_details_by_id[0]['contact_number']);
                        }else{
                            echo $contact_details_by_id[0]['contact_number'];
                        }
                        ?>

                    </h4>
                </div>

                <div class="chat-wrapper mt-3" style='background-color: #efeae2'>

                    <div class="chat-wrapper-image" style='background-image: url("assets/images/bg-whatsapp.png");background-repeat: repeat; opacity: 0.6'>
                    </div>

                    <div class="chat-wrapper-content" style="z-index: 9; position: relative">
                        <?php if($threads): ?>
                        <?php foreach ($threads as $thread): ?>
                                <?php
                                $message_outbound_class = '';
                                if($thread['outbound']){
                                    $message_outbound_class = 'justify-content-end text-end';
                                }
                                ?>
                                <div class="d-flex <?php echo $message_outbound_class ?>">
                                    <div class="chat-contact mb-2">
                                        <div class="p-2 border-0">
                                            <div class="card-body">
                                                <p class="m-0" style="font-size: 0.7rem;color: #3e3838"><?php echo $thread['created_at'] ?></p>
                                                <p class="m-0"><?php echo $thread['message_body'] ?></p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                    <div class="send-form-holder">
                        <form action="#" method="post">
                            <div class="input-group mb-0">
                                <input type="text" name="chat_response_text" class="form-control" placeholder="Type your message..." style="border: 0; height: 50px">

                                <div class="hidden-fields">
                                    <input type="hidden" name="to_number" value="whatsapp:+8801643177674">
                                </div>
                                <button class="btn btn-primary px-4" name="chat_response" type="submit" id="button-addon2">Send</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>