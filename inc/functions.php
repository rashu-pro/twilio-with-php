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

// Function to fetch contacts with last message
function fetch_contact_list_with_last_message($conn) {
    $sql = "
        SELECT
            c.id AS contact_id,
            c.contact_number,
            t.id AS message_id,
            t.message_body,
            t.inbound,
            t.outbound,
            t.created_at AS message_created_at
        FROM
            contact_list c
        LEFT JOIN
            (
                SELECT
                    contact_id,
                    id,
                    message_body,
                    inbound,
                    outbound,
                    created_at
                FROM
                    threads
                WHERE
                    (contact_id, created_at) IN (
                        SELECT
                            contact_id,
                            MAX(created_at) AS created_at
                        FROM
                            threads
                        GROUP BY
                            contact_id
                    )
            ) t ON c.id = t.contact_id
        ORDER BY
            t.created_at DESC
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch all rows as an associative array
        $contactMessages = $result->fetch_all(MYSQLI_ASSOC);
        return $contactMessages;
    } else {
        return [];
    }
}

// Function to fetch contacts from the database
function fetch_contacts_by_id($conn, $id) {
    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("SELECT * FROM contact_list WHERE id = ?");

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("i", $id);

    $data = false;

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Get the result set from the executed statement
        $result = $stmt->get_result();

        // Fetch the result as an associative array
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            echo "No contact found with the provided number.";
            $data = null;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    return $data;
}

// Function to fetch threads by contact_id
function fetch_threads_by_id($conn, $id) {
    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("SELECT * FROM threads WHERE contact_id = ?");

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("i", $id);

    $data = [];

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Get the result set from the executed statement
        $result = $stmt->get_result();

        // Fetch the result as an associative array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            echo "No contact found with the provided number.";
            $data = null;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    return $data;
}

/**
 * @param $conn
 * @param $contact_number
 * @return false|mixed|null
 */
function fetch_contacts_by_contact_number($conn, $contact_number) {
    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("SELECT * FROM contact_list WHERE contact_number = ?");

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $contact_number);

    $data = false;
    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Get the result set from the executed statement
        $result = $stmt->get_result();

        // Fetch the result as an associative array
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            echo "No contact found with the provided number.";
            $data = null;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    return $data;
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