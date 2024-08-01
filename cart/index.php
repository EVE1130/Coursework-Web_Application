<?php 
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="cart_style.css">
    <link href="https://fonts.cdnfonts.com/css/venus-rising" rel="stylesheet">
    
    <!--This is for the icon-->
    <script src="https://kit.fontawesome.com/3e15daf571.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>CART | EVANGELION</title>
</head>

<body>

<?php 
include('../include/evan_header.php');
?>
<div class="shopping-cart">
    
<div class="cart-view-table front" id="view-cart">
    <h3>Your Shopping Cart</h3>
   <form method="post" action="cart_remove.php">
    <table class="cart-table">
	<thead>
	<tr><th>No.</th><th>Image</th><th>Name</th><th>Brand</th><th>Price</th></tr>
	</thead>
    <tbody>

<?php
$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

if(isset($_SESSION["cart_products"]) && count($_SESSION["cart_products"])>0)
{
    $total = 0;
	$n = 1;
    foreach ($_SESSION["cart_products"] as $cart_itm)
    {
        $product_id = $cart_itm["pro_id"];
        $product_img = $cart_itm["img1"];
        $product_name = $cart_itm["pro_name"];
        $product_brand = $cart_itm["brand"];
        $product_price = $cart_itm["price"];

        echo '<tr class="cart-row">';	
		echo '<td class="cart-item">'.$n++.'</td>';
        echo '<td class="cart-item"><img class="cart-img" src="'.$product_img.'"/></td>';
        echo '<td class="cart-item">'.$product_name.'</td>';
        echo '<td class="cart-item">'.$product_brand.'</td>';
    	echo '<td class="cart-item">'.$product_price.'</td>';
        echo '<td class="cart-item"><input type="checkbox" name="remove_code[]" value="'.$product_id.'" /> Remove</td>';
        echo '</tr>';
        $total = ($total + $product_price);
    }
}else{
    echo '<tr><td colspan="6" style="text-align: center; font-size:x-large;">Your Cart is empty</td></tr>';
    $total = 0.00;
}
?>
    <tr><td colspan="6"><strong>Total:</strong> <?php echo 'RM '.$total; ?></td></tr>
    <td colspan="6">
    <span style="float:right;text-align: right;">
        <button type="submit" class="button">Update</button>
        <a href="view_cart.php" class="button">Checkout</a>
    </span>
    </td>
    </tbody></table></form>

<?php
    $current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
    echo '</form>';
    echo '</div>';
?>
</div>
<?php include("../include/evan_footer.php")?>
</body>
</html>