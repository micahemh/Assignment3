<!DOCTYPE html>
<!--
Lauren Lee and Micah Higashi
ITM 352
19 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page is the invoice display page. It offers its users to send their receipt
to their email address on file. If there is time, it will also provide the option
to save their receipt as a PDF to their computer.
-->
<?php
    require("functions.php");
    
    //Initialize products array
    $products = convertToProductsArray(populateArrayFromDatabase('products.dat'));
    //Initialize users array
    $users = convertToUsersArray(populateArrayFromDatabase('users.dat'));
    
    if(isset($_COOKIE['username'])) {
        session_save_path('sessions/.');
        session_id($_COOKIE['username']);
        session_start();
    }
    
    // Process the selected quantities into a string so it can be both printed and used in the body of the email
    $message = 'Your order is:<br>';
    for ($i = 0; $i < count($products); $i++) {
        if($_SESSION['cart_quantities'][$i] != 0) {
            $quantity = $_SESSION['cart_quantities'][$i];
            $message .= sprintf('You ordered %d %s<br>', $quantity, $products[$i]['name']);
        }
    }

    ini_set("SMTP","mail.hawaii.edu");
    $origin = "From: micahemh@hawaii.edu";
    ini_set("sendmail_from",$origin);
    $destination = null;
    for($i=0; $i<sizeOf($users); $i++) {
        if($_COOKIE['username'] == $users[$i]['username']) {
            $destination = $users[$i]['email'];
        }
    }
    $message = "testing message";
    if(mail($destination,"Your Purchase",$message)) {
        echo "Message sent! to ".$_COOKIE['username']."!";
    }
    else {
        echo "Message send failure!";
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Airfryers And Things | Your Invoice</title>

        <!--Preset data provided by Netbeans-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--Link for tab icon-->
        <link rel="icon" href="media/tabIcon.png">

        <!--Link for stylesheet-->
        <link rel="stylesheet" type="text/css" href="style.css">

        <!--Link to navbar's stylesheet (copied from W3 Schools)-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div>
        <header class="backgroundPlatform">
            <div style="text-align: left;">
                <a href="<?= $_SERVER['PHP_SELF'] ?>">      
                    <img src="media/banner.png" height="100">
                </a>
            </div>
            <div style="text-align: right; justify-items: top;">
                <?php
                    date_default_timezone_set('HST');
                    print "Today is ".date("l, F jS, Y \a\\t h:i A");
                    if(isset($_COOKIE['username'])) {
                        print "<hr>Welcome, ".ucfirst($_COOKIE['username']).'!<br><hr>Congrats; you completed your purchase!';
                    }
                ?>
            </div>
            <div class="navbar" style="grid-column: 1 / span 2">
                <a class="active" href="../index.php"><i class="fa fa-fw fa-home"></i>Home</a>
                <a href="../airFryers.php">Air<small> </small>Fryers</a>
                <a href="../slowCookers.php">Slow<small> </small>Cookers</a>
                <a href="../pressureCookers.php">Pressure<small> </small>Cookers</a>
                <a href="../about.php"><i class="fa fa-fw fa-question"></i>About<small> </small>Us</a>
                <a href="../cart.php"><i class="fa fa-fw fa-shopping-cart"></i><small> </small>Cart</a>
                <a href="../account.php"><i class="fa fa-fw fa-lock"></i><small> </small>My<small> </small>Account</a>
            </div>
        </header>
            
            <!--Display the main content (i.e. invoice)-->
            <main>
                <center>
                    <div style="margin-top: 1em;">
                        <div style="border:1px solid black; width: 352px; background: #f6f6f6;" id="receipt">
                            <img src="media/banner.png" alt="banner" width="350">
                            <tt>
                                Airfryers And Things
                                <br>
                                Your one-stop shop for air fryers, slow cookers, and pressure cookers
                                <p id="date" style="margin-bottom: 0;"></p>
                                <p id="time" style="margin: 0;"></p>
                                <hr width="50%">
                                <?php
                                //initialize subtotal value to zero
                                $subtotal = 0;

                                //Loop through all the indicies
                                for ($i = 0; $i < countItemsInDatabase('products.dat'); $i++) {
                                    if ($_SESSION['cart_quantities'][$i] != 0) {
                                        printf("<span style='float: left'> &nbsp %s %s</span><span style='float: right'>$%s &nbsp </span><br>"
                                                , number_format($_SESSION['cart_quantities'][$i], 0, '', ',')
                                                , $products[$i]['name']//pluralize($products[$i][0])
                                                , number_format($products[$i]['price'] * $_SESSION['cart_quantities'][$i], 2, '.', ',')
                                        );
                                    }
                                    $subtotal += ($products[$i]['price'] * $_SESSION['cart_quantities'][$i]);
                                }
                                //Add two subtotal lines
                                echo"<hr width=90%><hr width=90%>";

                                //Output totals data
                                printf("<span style='float: left'> &nbsp <b>Subtotal</b>:</span><span style='float: right'><b>%s</b> &nbsp </span><br>
                            <span style='float: left'> &nbsp 5%% Tax:</span><span style='float: right'>%s &nbsp </span><br>
                            <span style='float: left'> &nbsp <b>Grand Total</b>:</span><span style='float: right'><b>%s</b> &nbsp </span>"
                                        , '$' . number_format($subtotal, 2, '.', ',')
                                        , '$' . number_format($subtotal * .05, 2, '.', ',')
                                        , '$' . number_format($subtotal * 1.05, 2, '.', ',')
                                );
                                ?>
                                <br>
                                <hr width="50%">
                                Thank you for your purchase, <?php echo ucfirst($_COOKIE['username']); ?>!
                                <br>
                                We hope to see you again soon!
                                <br>
                                <br>
                            </tt>
                        </div>
                    </div>
                </center>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("footer.htm"); clearCart(); ?>
        </div>
    </body>
</html>