<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo 'not_logged_in';
    exit;
}

// Check if product_id is set
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    require_once '../db_connection.php';
    try {
        // Prepare the INSERT statement
        $stmt = $conn->prepare('INSERT INTO favorite (mem_id, product_id) VALUES (?, ?)');
        
        // Bind parameters
        $stmt->bind_param('ii', $_SESSION['mem_id'], $product_id);

        // Execute the statement
        $stmt->execute();
        echo 'success';

    } catch (mysqli_sql_exception $e) {
        // If a duplicate key error occurs, show an error message
        if ($e->getCode() == 1062) {
            echo 'duplicate_entry';
        } else {
            echo 'Error: ' . $e->getMessage();
        }
    }
} else {
    echo 'error';
}