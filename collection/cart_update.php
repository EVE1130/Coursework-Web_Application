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

$product_id = $_POST['product_id'];

// Check if $product_id is not already in $_SESSION['cart_products']
if (!isset($_SESSION['cart_products']) || !in_array($product_id, array_column($_SESSION['cart_products'], 'pro_id'))) {
    require_once '../db_connection.php';

    // Fetch product details from the database
    $stmt = $conn->prepare('SELECT * FROM product WHERE pro_id = ?');
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
            
    $product = $stmt->get_result();
        
    // Check if a product was found
    if ($product && $product->num_rows > 0) {
        // Add product details to $_SESSION['cart_products']
        if (!isset($_SESSION['cart_products'])) {
            $_SESSION['cart_products'] = array();
        }

        while($row = $product->fetch_assoc()){
            $_SESSION['cart_products'][] = array(
            'pro_id' => $row['pro_id'],
            'pro_name' => $row['pro_name'],
            'img1' => $row['img1'],
            'brand' => $row['brand'],
            'price' => $row['price'] 
            );
        }
        echo 'success';
    } else {
        echo 'product_not_found';
    }
} else {
    echo 'duplicate_entry';
}

