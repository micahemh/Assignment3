<!--External Header page for Assignment 3

Lauren Lee and Micah Higashi
ITM 352
18 April 2019
Professor Kazman
-->

<!--Preset data provided by Netbeans-->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--Link to navbar's stylesheet (copied from W3 Schools)-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!--Link for stylesheet-->
<link rel="stylesheet" type="text/css" href="resources/style.css">

<header class="backgroundPlatform">
    <div style="text-align: left;">
        <a href="<?= $_SERVER['PHP_SELF'] ?>">      
            <img src="resources/media/banner.png" height="100">
        </a>
    </div>
    <div style="text-align: right; justify-items: top;">
        <?php
            if(isset($user)) {
                $user = "username";
                print "<br><br>Welcome, $user!";
            }
        ?>
    </div>
    <div class="navbar" style="grid-column: 1 / span 2">
        <a class="active" href="index.php"><i class="fa fa-fw fa-home"></i>Home</a>
        <a href="airFryers.php">Air Fryers</a>
        <a href="slowCookers.php">Slow Cookers</a>
        <a href="pressureCookers.php">Pressure Cookers</a>
        <a href="cart.php"><i class="fa fa-fw fa-shopping-cart"></i> Your Cart</a>
        <a href="login.php"><i class="fa fa-fw fa-lock"></i> Login</a>
        <a href="about.php"><i class="fa fa-fw fa-question"></i> About Us</a>
    </div>
</header>