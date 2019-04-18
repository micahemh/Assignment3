<!DOCTYPE html>
<!--
Lauren Lee and Micah Higashi
ITM 352
18 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page is the invoice display page. It offers its users to send their receipt
to their email address on file. If there is time, it will also provide the option
to save their receipt as a PDF to their computer.
-->
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

        <!--Link to Functions PHP Page-->
        <?php include("functions.php"); ?>
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
                    if(isset($user)) {
                        $user = "username";
                        print "<br><br>Welcome, $user!";
                    }
                ?>
            </div>
            <div class="navbar" style="grid-column: 1 / span 2">
                <a class="active" href="../index.php"><i class="fa fa-fw fa-home"></i>Home</a>
                <a href="../airFryers.php">Air Fryers</a>
                <a href="../slowCookers.php">Slow Cookers</a>
                <a href="../pressureCookers.php">Pressure Cookers</a>
                <a href="../cart.php"><i class="fa fa-fw fa-shopping-cart"></i>Your Cart </a>
                <a href="../login.php"><i class="fa fa-fw fa-lock"></i>Login </a>
                <a href="../about.php"><i class="fa fa-fw fa-question"></i>About Us </a>
            </div>
        </header>
            
            <!--Display the main content (i.e. invoice)-->
            <main>
                <center>
                <div style="width: 353px; margin: auto;">
                    <!--Return Home Button-->
                    <form action="../index.php" method="POST">
                        <button type="submit" name="username" value="<?php echo $_POST['username']; ?>" style="margin-top: 0.5em; float: left;">Return Home</button>
                    </form>

                    <button id="cmd" style="margin-top: 0.5em; float: right;" disabled="disabled">Save Receipt</button>
                    <br>
                </div>
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
                                //Initialize products array
                                $products = populateArrayFromDatabase('products.dat');

                                //initialize subtotal value to zero
                                $subtotal = 0;

                                //Loop through all the indicies
                                for ($i = 1; $i < countItemsInDatabase('products.dat'); $i++) {
                                    //Cast monetary values in the products array to markedup values of floats
                                    $products[$i][2] = (float) $products[$i][2];

                                    //Cast numeric values in the POST  array (productOrder)s to integers
                                    $_POST["productOrder$i"] = (int) $_POST["productOrder$i"];

                                    if ($_POST["productOrder$i"] != 0) {
                                        printf("<span style='float: left'> &nbsp %s %s</span><span style='float: right'>$%s &nbsp </span><br>"
                                                , number_format($_POST["productOrder$i"], 0, '', ',')
                                                , pluralize($products[$i][0])
                                                , number_format($products[$i][2] * $_POST["productOrder$i"], 2, '.', ',')
                                        );
                                    }
                                    $subtotal += ($products[$i][2] * $_POST["productOrder$i"]);
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
                                Thank you for your purchase, <?php echo ucfirst($_POST['username']); ?>!
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
            <?php require("footer.htm"); ?>
        </div>
    </body>
</html>