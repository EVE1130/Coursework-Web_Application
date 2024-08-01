<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Remove items 
if(isset($_POST["remove_code"]))
{
    //remove an item from product session
    if(isset($_POST["remove_code"]) && is_array($_POST["remove_code"])){
        foreach($_POST["remove_code"] as $product_id){
            foreach($_SESSION["cart_products"] as $index => $product){
                if($product['pro_id'] == $product_id){
                    unset($_SESSION["cart_products"][$index]);
                    // Re-index the array to avoid gaps
                    $_SESSION["cart_products"] = array_values($_SESSION["cart_products"]);
                    break;
                }
            }
        }
    }
}

//back to return url
$return_url = (isset($_POST["return_url"]))?urldecode($_POST["return_url"]):''; //return url
header('Location:'.$return_url);
