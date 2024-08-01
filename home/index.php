<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="home_style.css">
    <link href="https://fonts.cdnfonts.com/css/venus-rising" rel="stylesheet">
        
    <!--This is for the icon-->
    <script src="https://kit.fontawesome.com/3e15daf571.js" crossorigin="anonymous"></script>
        
    <!--This event listener is mainly used for homepage background scrolling mini animation -->
    <script>
    window.addEventListener('scroll',function(){
        const scrollY = window.scrollY;
        const backgroundPic = document.querySelector(".bg-main");
        backgroundPic.style.transform = `translateY(${-scrollY * 0.3}px)`
    });
    </script>

    <title>HOME | EVANGELION</title>
    <style>
        body{
            background-color: rgb(0, 0, 0);
        }
    </style>
</head>

<body>

<!-- Header and sidebar is included on every page -->
<?php include('../include/evan_header.php');?>

<!-- The main page -->
<div class="bg-main">
    <h2>DIGITAL FAHSION MARKETPLACE</h2>
    <br>
    <div><a href="../collection/" target="_blank">SHOP NOW</a></div>
</div>

<!-- Main page content -->
<div class = "bg-second">    
    <h2>EXTRAVAGANZA</h2>
            
    <p>Digital Fashion Show Fall 2024. <br>
    The Future of Fashion. <br>
    The Fashion Metaverse. </p>
    <a href="../show/" target="_blank">STAY TUNE</a>
</div>   

<!-- 3rd division represent the content of featured brands -->
<div class = "bg-third">
    <h3>FEATURED BRANDS</h3>
    <div class ="showcase-grid">
        <a href="https://www.thefabricant.com/" target="_blank" class = "showcase_pic" style = "background-image: url('../picturecol/TheFabricant.jpg');">
           THE FABRICANT
        </a>
        <a href="https://www.coeval-magazine.com/coeval/mengze-zheng" target="_blank" class = "showcase_pic" style = "background-image: url('../picturecol/SUPERMOLECULE/14.jpg');">
             SUPERMOLECULE
        </a>
        <a href="https://www.auroboros.co.uk/" target="_blank" class = "showcase_pic" style = "background-image: url('../picturecol/AUROBOROS/MANDRAKE2.jpg');">
           AUROBOROS
        </a>
    </div>
</div>

<?php include('../include/evan_footer.php');?>

</body>

</html>