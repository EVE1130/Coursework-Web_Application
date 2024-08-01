<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if ($_POST['submit'] === 'Check out') {
    require_once '../db_connection.php';

    $product_ids = $_POST['product_id'];
	$total = $_POST['grand_total'];
	$user_id = $_SESSION['mem_id'];
	$sales_date = date('Y-m-d H:i:s'); // Current date and time

	// Insert into sales table
	$stmt = $conn->prepare('INSERT INTO sales (mem_id, total_price, sales_date) VALUES (?, ?, ?)');
	$stmt->bind_param('ids', $user_id, $total, $sales_date);
	$stmt->execute();

	// Get the last inserted id (sales_id)
	$sales_id = $conn->insert_id;

	// Insert into sales_record table
	for ($i = 0; $i < count($product_ids); $i++) {
		$stmt = $conn->prepare('INSERT IGNORE INTO  sales_record (sales_id, mem_id, product_id) VALUES (?, ?, ?)');
		$stmt->bind_param('iii', $sales_id, $user_id, $product_ids[$i]);
		$stmt->execute();
	}
	// Clear the cart
	unset($_SESSION['cart_products']);

	// Set a session variable to indicate that the checkout was successful
	$_SESSION['checkout_success'] = true;
	// Redirect to the cart page
	header('Location: view_cart.php');
	exit;
}

