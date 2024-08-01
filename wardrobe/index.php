<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//Connect to database
require_once '../db_connection.php';

// Check if the user is logged in
if(!isset($_SESSION['email'])){
    header('Location: ../login/');
    exit;
}

//The design of the button
$purchasedButtonStyle = '';
$favoritesButtonStyle = '';
if (isset($_POST['submit'])) {
    if ($_POST['submit'] === 'Purchased') {
        $purchasedButtonStyle = 'color:#fff; text-shadow: 0 0 5px #ff00ff, 0 0 8px #00a2ff;';
        $favoritesButtonStyle = 'color:black;';
    } else if ($_POST['submit'] === 'Favorites') {
        $favoritesButtonStyle = 'color:#fff; text-shadow: 0 0 5px #ff00ff, 0 0 8px #00a2ff;';
        $purchasedButtonStyle = 'color:black;';
    }
} else {
    $purchasedButtonStyle = 'color:#fff; text-shadow: 0 0 5px #ff00ff, 0 0 8px #00a2ff;';
    $favoritesButtonStyle = 'color:black;';
}

// Retrieve the user's ID from the session
$mem_id = $_SESSION['mem_id'];

// Check if the favorite or purchase was chosen
if (isset($_POST['submit']) && $_POST['submit'] === 'Favorites') {
    $sql = 'SELECT * FROM Favorite WHERE mem_id = ?';
}else{
    $sql = 'SELECT * FROM Sales_Record WHERE mem_id = ?'; 
}
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $mem_id);
$stmt->execute();

// Retrieve the product IDs
$products_id = array();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $products_id[] = $row['product_id'];
}

// Retrieve the products only if there are product IDs
$products = array();
if (!empty($products_id)) {
    $sql2 = 'SELECT * FROM product WHERE pro_id IN (' . implode(',', $products_id) . ')';
    $stmt2 = $conn->prepare($sql2); 
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    while ($row = $result2->fetch_assoc()) {
        $products[] = $row;
    }
    //Free memories of results
    $result2->close();
}

//Free memories of results
$result->close();

// Handle the removal of a product from the favorites
if (isset($_POST['remove_favorite'])) {
    $product_id = $_POST['product_id'];
    $sql = 'DELETE FROM Favorite WHERE mem_id = ? AND product_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $mem_id, $product_id);
    if ($stmt->execute()){
    echo 'Product removed from favorites';
    }
    else{
        echo 'Failed to remove product from favorites';
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="wardrobe_style.css">
    <link href="https://fonts.cdnfonts.com/css/venus-rising" rel="stylesheet">
    
    <!--This is for the icon-->
    <script src="https://kit.fontawesome.com/3e15daf571.js" crossorigin="anonymous"></script>
    
    <title>META WARDROBE | EVANGELION</title>


</head>
<body>
    <?php include '../include/evan_header.php'; ?>

    <!--webpage starts here-->   
    <section id="ward_banner">
        <h1><?php echo $_SESSION['mem_name']?>'s META WARDROBE</h1>
    </section>

    <div id="mark">
        <form method="post" action="">
            <input id="purchased-button" type="submit" value="Purchased" name="submit" style="<?php echo $purchasedButtonStyle; ?>">
            <input id="favorites-button" type="submit" value="Favorites" name="submit" style="<?php echo $favoritesButtonStyle; ?>">
        </form>
    </div>

    <div class="container">
    <?php 
        if (isset($products) && count($products) > 0) {
            foreach ($products as $product) {
                echo '<div class="product">';
                echo '<img src="'. $product['img1'].'" alt="'. $product['pro_name'].' Image">';
                if (isset($_POST['submit']) && $_POST['submit'] === 'Favorites') {
                    echo '<i class="fa-solid fa-heart" style="color:red; position:absolute; bottom:10px; right:10px; cursor:pointer;" onclick="removeFavorite(' . $product['pro_id'] . ')"></i>';
                }
                echo '<p class="product_name">'.$product['pro_name']. '<br><span class="product_brand">'. $product['brand'].'</span></p>';
                if ($product['discount_p'] != 0){
                    echo '<p class="product_price"><span class="discount"><del>RM '.$product['discount_p'].'</del></span><br>RM '.$product['price'].'</p>';
                }else{
                    echo '<p class="product_price">RM '. $product['price'] . '</p>';
                }
                echo '</div>';
            }
        }else{
            echo '<h3>No products found.</h3><br>';
            echo '<p><a href="../collection/">Shop now</a> to add products to your wardrobe.</p>';
        }
    ?>
                  
    </div>

    <?php include('../include/evan_footer.php'); ?>

    <script>
        function removeFavorite(product_id) {
            if (confirm('Do you want to remove this product from favorites?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status == 200) {
                        alert(this.responseText);
                        location.reload();
                    }
                };
                xhr.send('remove_favorite=true&product_id=' + product_id);
            }
        }
    </script>
</body>
</html>