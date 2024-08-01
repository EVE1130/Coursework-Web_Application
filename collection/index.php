<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Connect to database
require_once '../db_connection.php';

//Get the filter data
$category = isset($_POST['Category']) ? $_POST['Category'] : null;
$brand = isset($_POST['Brand']) ? $_POST['Brand'] : null;
$price = isset($_POST['Price']) ? $_POST['Price'] : null;
$sortPrice = isset($_POST['sort-price']) ? $_POST['sort-price'] : null;

//Form sql query
//For default or show all
$sql = 'SELECT * FROM product WHERE 1=1';

// Add categories condition if needed
if (isset($category) && $category !== null) {
    $category = array_map(function($item) use ($conn) {
        return "'" . $conn->real_escape_string($item) . "'";
    }, $category);
    $sql .= ' AND category IN (' . implode(',', $category) . ')';
}

// Add brand condition if needed
if (isset($brand) && $brand !== null) {
    $brand = array_map(function($item) use ($conn) {
        return "'" . $conn->real_escape_string($item) . "'";
    }, $brand);
    $sql .= ' AND brand IN (' . implode(',', $brand) . ')';
}

//Add price condition if needed
if (isset($price) && $price) {
    $sql .= ' AND price <= ' . intval($price);
}

//Add sortPrice condition if needed
if (isset($sortPrice) && $sortPrice == 'low-to-high') {
    $sql .= ' ORDER BY price ASC';
} else if (isset($sortPrice) && $sortPrice == 'high-to-low') {
    $sql .= ' ORDER BY price DESC';
}

// Execute the query
$stmt = $conn->prepare($sql);
$stmt->execute();

// Fetch the products
$products = array();
$result = $stmt->get_result(); 
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

//Free memories of results
$result->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="collection_style.css">
    <link href="https://fonts.cdnfonts.com/css/venus-rising" rel="stylesheet">
    
    <!--This is for the icon-->
    <script src="https://kit.fontawesome.com/3e15daf571.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>COLLECTION | EVANGELION</title>

</head>
<body>

    <?php include '../include/evan_header.php'; ?>

    <!--webpage starts here-->   
    <section id="coll_banner">
        <h1>Digital Fashion Marketplace</h1>
        <p>Discover the latest digital fashion collection from the top designers</p>
    </section>
    
    <div class="shop_container">    
        <aside id="sort">
            <form id="shop_sort" action="" method="post">
                <fieldset>
                    <legend>CATEGORY</legend>
                    <div class="sort_check">
                        <li><input type="checkbox" id="Top" name="Category[]" value="Top">
                            <label for="Top">Top</label></li>
                        <li><input type="checkbox" id="Bottom" name="Category[]" value="Bottom">
                            <label for="Bottom">Bottom</label></li>
                        <li><input type="checkbox" id="One-piece" name="Category[]" value="One-piece">
                            <label for="One-piece">One-piece</label></li>
                        <li><input type="checkbox" id="Accessories" name="Category[]" value="Accessories">
                            <label for="Accessories">Accessories</label></li>
                        <li><input type="checkbox" id="Shoes" name="Category[]" value="Shoes">
                            <label for="Shoes">Shoes</label></li>
                        <li><input type="checkbox" id="Bags" name="Category[]" value="Bags">
                            <label for="Bags">Bags</label></li>
                    </div>
                </fieldset>
                <br>
                <fieldset>
                    <legend>BRAND</legend>
                    <div class="sort_check">
                        <li><input type="checkbox" id="fabricant" name="Brand[]" value="THE FABRICANT">
                            <label for="fabricant">THE FABRICANT</label></li>
                        <li><input type="checkbox" id="supermolecule" name="Brand[]" value="SUPERMOLECULE">
                            <label for="supermolecule">SUPERMOLECULE</label></li>
                        <li><input type="checkbox" id="auroboros" name="Brand[]" value="AUROBOROS">
                            <label for="auroboros">AUROBOROS</label></li>
                        <li><input type="checkbox" id="iris" name="Brand[]" value="IRIS VAN HERPEN">
                            <label for="iris">IRIS VAN HERPEN</label></li>
                        <li><input type="checkbox" id="codes" name="Brand[]" value="CODE.S">
                            <label for="codes">CODE.S</label></li>
                        <li><input type="checkbox" id="metalax" name="Brand[]" value="METALAX">
                            <label for="metalax">METALAX</label></li>
                    </div>
                </fieldset>
                <br>
                <fieldset>
                    <legend>PRICE</legend>
                    <input type="range" id="Price" name="Price" min="0" max="1000" step="10" value="1000">
                    <span id="priceRange"></span>
                </fieldset>
                <br>
                <fieldset>
                    <legend>SORT</legend>
                    <select id="sort-price" name="sort-price">
                        <option value="low-to-high">Lowest to Highest Price</option>
                        <option value="high-to-low">Highest to Lowest Price</option>
                    </select>
                </fieldset>
                <br>
                <input type="submit" value="Filter">
                <button onClick="history.go(0);">Reset</button>
            </form>
        </aside>
        
        <!--Display products dynamically according to the filter-->
        <section id="display_list">
            <?php 
                if (count($products)>0) {
                    foreach ($products as $product) {
                        echo '<div class="product">';
                        echo '<img src="'. $product['img1'].'" alt="'. $product['pro_name'].' Image">';
                        echo '<p class="product_name">'.$product['pro_name']. '<br><span class="product_brand">'. $product['brand'].'</span></p>';
                        echo '<i class="fa-solid fa-expand" onclick="showPopup(' . $product['pro_id'] . ')"></i>';
                        if ($product['discount_p'] != 0){
                            echo '<p class="product_price"><span class="discount"><del>RM '.$product['discount_p'].'</del></span><br>RM '.$product['price'].'</p>';
                        }else{
                            echo '<p class="product_price">RM '. $product['price'] . '</p>';
                        }
                        echo '<i class="fa-regular fa-heart" data-product-id="' . $product['pro_id']  .'"></i>';
                        echo '<i class="fa-solid fa-cart-shopping" data-product-id="' . $product['pro_id'] . '"></i>'; 
                    
                        echo '<div id="popup' . $product['pro_id'] . '" class="popup">';  
                        echo '<div class="popup-content">';
                        echo '<i class="fa-solid fa-xmark" onclick="hidePopup(' . $product['pro_id'] . ')"></i>'; 
                        ?>
                            <div class="product-details">
                                <div class="product-images">
                                    <img class="productImage" src="<?php echo $product['img1']; ?>" alt="<?php echo $product['pro_name']; ?> Image">
                                    <?php if ($product['img2']!="null") { ?>
                                        <img class="productImage" src="<?php echo $product['img2']; ?>" alt="<?php echo $product['pro_name']; ?> Image">
                                    <?php } 
                                    if ($product['img3']!="null") {?>
                                        <img class="productImage" src="<?php echo $product['img3']; ?>" alt="<?php echo $product['pro_name']; ?> Image">
                                    <?php } ?>
                                    <i class="fa-regular fa-heart" data-product-id="$product['pro_id'] "></i>
                                    <i class="fa-solid fa-cart-shopping"data-product-id="$product['pro_id']"></i>
                                    <i class="fa-solid fa-chevron-left" onclick="changeImage(<?php echo $product['pro_id']; ?>, -1)"></i>
                                    <i class="fa-solid fa-chevron-right" onclick="changeImage(<?php echo $product['pro_id']; ?>, 1)"></i>
                                </div>
                                <div class="product-info">
                                    <h2 class="detail_name"><?php echo $product['pro_name']; ?><br>
                                    <span class="detail_brand"><?php echo $product['brand']; ?></span>
                                    </h2>
                                    
                                    <?php if ($product['discount_p'] != 0){
                                        echo '<p class="detail_price"><span class="detail_discount"><del>RM '.$product['discount_p'].'</del></span><br>RM '.$product['price'].'</p>';
                                    }else{
                                        echo '<p class="detail_price">RM '. $product['price'] . '</p>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                        
            <?php       
                        echo '</div>';
                    }
                } else {
                    echo'<p style="text-align:center; font-size:30px; padding:30px;"> No products found</p>';
                }
            ?>
        </section>
    </div>

    <?php include '../include/evan_footer.php'; ?>

    <script>
        var price = document.getElementById('Price');
        var priceRange = document.getElementById('priceRange');

        price.addEventListener('input', function() {
            priceRange.textContent = 'RM' + price.value;
        });

        priceRange.textContent = 'RM' + price.value;

        // Js for popup content
        function showPopup(productId) {
            var popup = document.getElementById('popup' + productId);
            popup.style.display = 'block';
        }

        function hidePopup(productId) {
            var popup = document.getElementById('popup' + productId);
            popup.style.display = 'none';
        }

        //Js for image slider in popup
        var currentImageIndex = {};

        function showPopup(productId) {
            var popup = document.getElementById('popup' + productId);
            popup.style.display = 'block';
            currentImageIndex[productId] = 0;
            switchImage(productId);
        }

        function hidePopup(productId) {
            var popup = document.getElementById('popup' + productId);
            popup.style.display = 'none';
        }

        function changeImage(productId, direction) {
            currentImageIndex[productId] += direction;
            switchImage(productId);
        }

        function switchImage(productId) {
            var images = document.querySelectorAll('#popup' + productId + ' .product-images img');
            for (var i = 0; i < images.length; i++) {
                images[i].classList.remove('active');
            }
            var index = currentImageIndex[productId] % images.length;
            if (index < 0) {
                index += images.length;
            }
            images[index].classList.add('active');
        }

        //Add to wishlist --send to favorite.php
        $('.fa-heart').click(function() {
            var product_id = $(this).data('product-id');

            $.post('favorite.php', { product_id: product_id }, function(data) {
                if (data === 'not_logged_in') {
                    alert('Please log in to add items to favorites.');
                } else if  (data === 'success') {
                    alert('Item added to favorite.');
                } else if (data === 'duplicate_entry') {
                    alert('Item already exist in the wishlist.');
                } else {
                    alert('There was error adding item to favorites.');
                }
            });
        });

        //Add to cart --send to cart_update.php
        $('.fa-cart-shopping').click(function() {
            var product_id = $(this).data('product-id');

            $.post('cart_update.php', { product_id: product_id }, function(data) {
                if (data === 'not_logged_in') {
                    alert('Please log in to add items to cart.');
                } else if (data === 'success') {
                    alert('Item added to cart.');
                } else if (data === 'duplicate_entry') {
                    alert('Item already exist in the cart.');
                }else if (data === 'product_not_found') {
                    alert('No product match');
                }else {
                    alert('There was an error adding the item to the cart.');
                }
            });
        });
    </script>
</body>
</html>