<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/resources/database_connection.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
$contacts = fetch_contact_list_with_last_message($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link href="assets/css/style.css?ver=2.0.0" rel="stylesheet">
</head>
<body>
<div class="my-5">
    <div class="container">
        <div class="contact-list-holder contact-list-holder-js">

            <!-- show alert/warning messages -->
            <?php
            if(isset($_SESSION['error_message'])){
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <?php
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
            }

            if(isset($_SESSION['success_message'])){
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                    <?php
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
            ?>

            <div class="d-flex justify-content-between py-2 px-4 mb-4" style="background-color: #e9ecef">
                <div>
                    <h4 class="text-success"> Send Message Using Twilio</h4>
                </div>
                <div class="text-end">
                    <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#send_message_modal">Send Message To a New Contact</a>
                </div>
            </div>
            <?php if($contacts): ?>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Contact Number</td>
                        <td>Mode</td>
                        <td>Last Message</td>
                        <td>Action</td>
                    </tr>
                    </thead>

                    <tbody>
                    <?php $counter = 1; ?>
                    <?php foreach ($contacts as $contact): ?>
                        <?php
                        // Extract contact number mode from contact number
                        $contact_mode = '';
                        if(strpos($contact['contact_number'], ':')){
                            $contact_mode = 'whatsapp';
                            $contact['contact_number'] = str_replace('whatsapp:', '', $contact['contact_number']);
                        }

                        $message_direction_class = 'inbound';
                        $message_direction_text = 'incoming';
                        if($contact['outbound']){
                            $message_direction_class = 'outbound';
                            $message_direction_text = 'outgoing';
                        }
                        ?>
                        <tr>
                            <td><?php echo $counter ?></td>
                            <td><?php echo $contact['contact_number'] ?></td>
                            <td><?php echo $contact_mode ?></td>
                            <td>
                                <?php if($contact['message_body']): ?>
                                    <p class="m-0"><span class="message-direction-alert <?php echo $message_direction_class ?>"> <?php echo $message_direction_text ?></span></p>
                                    <p class="m-0"> <?php echo $contact['message_body'] ?> </p>
                                <?php endif; ?>
                            </td>
                            <td><a href="contact-details.php?contact_id=<?php echo $contact['contact_id'] ?>" class="btn btn-secondary btn-sm">View Details</a> </td>
                        </tr>
                        <?php $counter++; ?>
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else: ?>
                <h2>No contacts found yet!</h2>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="send_message_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="inc/submit-actions.php" method="post">
                <div class="modal-body">
                    <div class="">
                        <!-- From Number -->
                        <div class="mb-3">
                            <label for="from_number">From Number</label>
                            <select class="form-select" id="from_number" name="from_number">
                                <option value="+18334630495">+18334630495</option>
                            </select>
                        </div>

                        <!-- Texting Mode -->
                        <div class="mb-3">
                            <label for="texting_mode">Texting Mode</label>
                            <select class="form-select" id="texting_mode" name="texting_mode">
                                <option value="whatsapp">whatsapp</option>
                            </select>
                        </div>

                        <!-- To Number -->
                        <div class="mb-3">
                            <label for="to_number">To Number</label>
                            <span> (Insert the number with country code) </span>
                            <input type="text" class="form-control" id="to_number" name="to_number" required>
                        </div>

                        <!-- Text -->
                        <div class="mb-3">
                            <label for="text_area">Message</label>
                            <textarea rows="3" class="form-control" id="text_area" name="text_area" required></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary px-4" name="send_message_to_new_contact" type="submit">Send</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>