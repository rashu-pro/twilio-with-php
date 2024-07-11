<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

//$server_name = $_ENV['SERVER_NAME'];
$db_name = $_ENV['DB_NAME'];
$db_password = $_ENV['DB_PASSWORD'];
$db_username = $_ENV['DB_USERNAME'];

// Create data base connection with mysqli
$conn = new mysqli('localhost', $db_username, $db_password, $db_name);

create_contact_list_table($conn);

create_threads_table($conn);

// Check connection
if ($conn->connect_error) {
    die("MySqli Connection failed: " . $conn->connect_error);
}

function create_contact_list_table($conn) {
    // Check if the table already exists
    $checkTableQuery = "SHOW TABLES LIKE 'contact_list'";
    $result = $conn->query($checkTableQuery);

    if ($result->num_rows == 0) {
        // Table does not exist, so create it
        $createTableQuery = "
            CREATE TABLE contact_list (
                id INT AUTO_INCREMENT PRIMARY KEY,
                contact_number VARCHAR(255),
                contact_mode INT,
                contact_name VARCHAR(255),
                contact_address VARCHAR(255),
                contact_details VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                is_delete INT DEFAULT 0
            )";

        if ($conn->query($createTableQuery) === TRUE) {
            echo "Table 'contact_list' created successfully.";
        } else {
            echo "Error creating table: " . $conn->error;
        }
    } else {
        // Table already exists
        // echo "Table 'contact_list' already exists.";
    }
}

function create_threads_table($conn) {
    // Check if the table already exists
    $checkTableQuery = "SHOW TABLES LIKE 'threads'";
    $result = $conn->query($checkTableQuery);

    if ($result->num_rows == 0) {
        // Table does not exist, so create it
        // SQL query to create the threads table
        $create_table_query = "
        CREATE TABLE threads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            contact_id INT,
            message_body VARCHAR(255),
            inbound INT DEFAULT 0,
            outbound INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            is_delete INT DEFAULT 0,
            FOREIGN KEY (contact_id) REFERENCES contact_list(id)
        )
    ";

        // Attempt to execute the query
        if ($conn->query($create_table_query) === TRUE) {
            echo "Table 'threads' created successfully.";
        } else {
            echo "Error creating table: " . $conn->error;
        }
    } else {
        // Table already exists
        // echo "Table 'contact_list' already exists.";
    }


}