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

	<title>CHECKOUT | EVANGELION</title>
</head>
<body>

<br>
<br>
<?php
include('../include/evan_header.php');
?>

<div class="cart-view-table back">
<table width="100%"  cellpadding="6" cellspacing="0">
<thead><tr><th>No.</th><th>Image</th><th>Name</th><th>Brand</th><th>Price</th></tr></thead>
  <tbody>
 	<?php
 	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
 	
	if(isset($_SESSION["cart_products"]) && count($_SESSION["cart_products"])>0)
	{
		$total = 0;
		$n = 1;

		echo '<form action="checkout.php" method="post">';

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
			echo '</tr>';
			$total = ($total + $product_price);

			echo '<input type="hidden" name="product_id[]" value="'.$product_id.'">';
		}
		$grand_total = $total;
		echo '<input type="hidden" name="grand_total" value="'.$grand_total.'">';
	}

    	?>
		<tr><td colspan="5" >
		<span >
		Amount Payable : RM<?php echo sprintf("%01.2f", $grand_total);?></span>
		</td></tr>
		<tr><td colspan="3" style="text-align: left;"><a href="index.php" id="adddelItem" class="button">Add/Delete Items</a>
		</td><td colspan="2"><input type="submit" name="submit" value = "Check out"class="button"></td></tr>	
	</form>
	</tbody>
	</table>
	
	<input type="hidden" name="return_url" value="<?php 
	$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	echo $current_url; ?>" />  
	
	<?php
		if (isset($_SESSION['checkout_success'])) {
			// Unset the session variable so the message doesn't show again on refresh
			unset($_SESSION['checkout_success']);

			echo '<script type="text/javascript">
				alert("Thank you for your purchase!");
				window.location.href = "../wardrobe/";
			</script>';
		}?>

</form>
</div>
</body>
</html>