<!--External Header page for Assignment 3

Lauren Lee and Micah Higashi
ITM 352
19 April 2019
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
            date_default_timezone_set('HST');
            print "Today is ".date("l, F jS, Y \a\\t h:i A");
            if(isset($_COOKIE['username'])) {
                print "<hr>Welcome, ".ucfirst($_COOKIE['username']).'!<br><hr>';
                
                if($_COOKIE['username'] !== 'guest') {
                    if(isset($_SESSION['cart_quantities']) && array_sum($_SESSION['cart_quantities']) > 0) {
                        print "You have ".array_sum($_SESSION['cart_quantities'])." item(s) in your cart";
                    }
                }
            }
        ?>
    </div>
    <div class="navbar" style="grid-column: 1 / span 2">
        <a class="active" href="index.php"><i class="fa fa-fw fa-home"></i>Home</a>
        <a href="airFryers.php">Air<small> </small>Fryers</a>
        <a href="slowCookers.php">Slow<small> </small>Cookers</a>
        <a href="pressureCookers.php">Pressure<small> </small>Cookers</a>
        <a href="about.php"><i class="fa fa-fw fa-question"></i>About<small> </small>Us</a>
        <?php
            //If a user is logged in
            if(isset($_COOKIE['username'])) {
                if($_COOKIE['username'] !== 'guest') {
                    echo '<a href="cart.php"><i class="fa fa-fw fa-shopping-cart"></i><small> </small>Cart</a>';
                }
                echo '<a href="account.php"><i class="fa fa-fw fa-lock"></i><small> </small>My<small> </small>Account</a>';
            }
            //If no user is logged in
            else {
                echo '<a href="account.php"><i class="fa fa-fw fa-unlock"></i><small> </small>Login</a>';
            }
        ?>
    </div>
</header>