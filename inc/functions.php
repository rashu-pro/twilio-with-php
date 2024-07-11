<?php

function insert_contact_number($conn, $contact_number) {
    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("INSERT INTO contact_list (contact_number, created_at, updated_at) VALUES (?, NOW(), NOW())");

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $contact_number);

    try {
        if ($stmt->execute()) {
            // Get the ID of the inserted row
            $inserted_id = $conn->insert_id;
            return $inserted_id;
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        if ($conn->errno == 1062) {
            echo "Error: Duplicate entry for contact_number";
        } else {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Close the statement
    $stmt->close();
}

// Insert contact messages
function store_contact_message($conn, $contact_id, $message_body, $inbound, $outbound) {
    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("INSERT INTO threads (contact_id, message_body, inbound, outbound, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("isii", $contact_id, $message_body, $inbound, $outbound);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // echo "New message stored successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Alter the column contact_number from contact_list table
function make_contact_number_unique($conn) {
    // Prepare an SQL statement for altering the table
    $alter_table_query = "ALTER TABLE contact_list ADD UNIQUE (contact_number)";

    // Attempt to execute the query
    if ($conn->query($alter_table_query) === TRUE) {
        echo "Unique constraint added to contact_number successfully.";
    } else {
        if ($conn->errno == 1061) {
            echo "Error: Duplicate key name 'contact_number' already exists.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Function to fetch contacts from the database
function fetch_contacts($conn) {
    $contacts = array();

    // Query to select all columns from contact_list table
    $sql = "SELECT * FROM contact_list";

    // Execute query and fetch results
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch each row as an associative array
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
    }

    return $contacts;
}

// Function to fetch contacts from the database
function fetch_contacts_by_id($conn, $id) {
    $contacts = array();

    // Query to select all columns from contact_list table
    $sql = "SELECT * FROM contact_list where id=$id";

    // Execute query and fetch results
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch each row as an associative array
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
    }

    return $contacts;
}

// Function to fetch threads by contact_id
function fetch_threads_by_id($conn, $id) {
    $contacts = array();

    // Query to select all columns from contact_list table
    $sql = "SELECT * FROM threads where contact_id=$id";

    // Execute query and fetch results
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch each row as an associative array
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
    }

    return $contacts;
}

function fetch_contacts_by_contact_number($conn, $contact_number) {
    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("SELECT * FROM contact_list WHERE contact_number = ?");

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $contact_number);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Get the result set from the executed statement
        $result = $stmt->get_result();

        // Fetch the result as an associative array
        if ($result->num_rows > 0) {
            $contact = $result->fetch_assoc();
            print_r($contact); // Output the contact for demonstration purposes
            return $contact;
        } else {
            echo "No contact found with the provided number.";
            return null;
        }
    } else {
        echo "Error: " . $stmt->error;
        return null;
    }

    // Close the statement
    $stmt->close();
}

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