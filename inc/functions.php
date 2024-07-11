<?php
// Insert contact number in contact_list table
function insert_contact_number($conn, $contact_number) {
    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("INSERT INTO contact_list (contact_number, created_at, updated_at) VALUES (?, NOW(), NOW())");

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $contact_number);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        echo "New record created successfully";
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