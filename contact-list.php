<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once 'resources/database_connection.php';
require_once 'inc/functions.php';
$contacts = fetch_contacts($conn);
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
</head>
<body>
<div class="my-5">
    <div class="container">
        <div class="contact-list-holder contact-list-holder-js">
            <?php if($contacts): ?>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Contact Number</td>
                        <td>Contact Mode</td>
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
                        ?>
                        <tr>
                            <td><?php echo $counter ?></td>
                            <td><?php echo $contact['contact_number'] ?></td>
                            <td><?php echo $contact_mode ?></td>
                            <td><a href="contact-details.php?contact_id=<?php echo $contact['id'] ?>" class="btn btn-secondary btn-sm">View Details</a> </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php $counter++; ?>
                    </tbody>

                </table>
            <?php else: ?>
                <h2>No contacts found yet!</h2>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>