<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        header {
            font-family: 'Venus Rising', sans-serif;
            background-color: rgba(10, 10, 10, 0.2);
            backdrop-filter: blur(2.5px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            position: fixed;
            top: 0;
            z-index: 10;
            width: 100%;
            box-sizing: border-box;
        }

        #menu, #account {
            position: relative;
        }

        .fa-solid, .fa-regular {
            color: white;
            transition: color 0.3s ease;
            cursor: pointer;
        }

        .fa-solid:hover, .fa-regular:hover{
            color:#e298ff;
            text-shadow: 0 0 5px #ff00ff, 0 0 8px #00a2ff;
            cursor: pointer;
        }

        .nav_list {
            display: block;
            position: fixed;
            top: 100%;
            box-shadow: 5px 9px 9px rgba(255, 255, 255, 0.2);
            padding: 10px;
            line-height: 1.5;
            background-color: rgba(10, 10, 10, 0.2);
        }

        .nav_list ul {
            padding: 0;
            margin: 0;
            list-style-type: none;
        }

        .nav_list ul li a{
            margin-bottom: 5px;
            text-decoration: none;
            color: rgb(243, 243, 243); 
        }

        #brand_logo a {
            flex-grow: 1;
            text-align: center;
            text-decoration: none;
            color: rgb(243, 243, 243); 
        }

        #account {
            display: flex;
            justify-content: flex-end;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <header>
       <div id="menu">
            <i class="fa-solid fa-bars"></i>
            <nav class="nav_list" style="margin-left: -10px">
                <ul>
                    <li><a href="../home/">HOME</a></li>
                    <li><a href="../collection/">COLLECTION</a></li>
                    <li><a href="../show/">SHOWS</a></li>
                    <li><a href="../about_us/">ABOUT US</a></li>
                </ul>
            </nav>
        </div>
        
        <div id="brand_logo"><a href = "../home/">EVANGELION</a></div>
        
        <div id="account">
            <i class="fa-solid fa-user"></i>
            <nav class="nav_list" style="margin-right: -10px">
                <ul>
                    <?php
                        if (isset($_SESSION['email'])) {
                            // User is logged in
                            echo '
                                <li style="color:white;">Hi! '.$_SESSION['mem_name'].'</li>
                                <li><a href="../logout.php">LOGOUT</a></li>
                                <li><a href="../cart/">MY CART</a></li>
                                <li><a href="../wardrobe/">MY META-WARDROBE</a></li>
                            ';
                        } else {
                            // User is not logged in
                            echo '<li><a href="../login/">LOGIN</a></li>';
                        }
                    ?>
                    </ul>
            </nav>        
        </div>
    </header>

    <script>
        $(document).ready(function(){
            $(".nav_list").hide(); // Hide the nav_list initially
            $("#menu, #account").click(function(){
                $(this).find(".nav_list").toggle();
            });
        });
    </script>
</body>
</html>