<!DOCTYPE html>
<!--
Brayden Kubota and Micah Higashi
ITM 352
04 April 2019
Professor Kazman

Assignment 2: Generates an eCommerce Web application with login functionality

This page is the invoice. It documents the purchases made, as well as the prices
for each, and the overall subtotal. It offers the chance to go back to the store
page as well. The save to PDF is disabled as I hadn't had time to implement it.
-->
<html>
    <head>
        <title>Dem Gems | <?php echo ucfirst($_POST['username']); ?>'s Receipt</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="media/tabIcon.png">

        <!--Link to Functions PHP Page-->
        <?php require("functions.php"); ?>
        
        <!--Redirect to Login if nobody is logged in-->
        <?php redirectToLoginFromInvoice(); ?>
    </head>
    <body style="width: 353px; margin: auto;">
        <!--Return Home Button-->
        <form action="../index.php" method="POST">
            <button type="submit" name="username" value="<?php echo $_POST['username'];?>" style="margin-top: 0.5em; float: left;">Return Home</button>
        </form>
        
        <button id="cmd" style="margin-top: 0.5em; float: right;" disabled="disabled">Save Receipt</button>
        <br>
        <center>
            <div style="margin-top: 1em;">
                <div style="border:1px solid black; width: 352px; background: #f6f6f6;" id="receipt">
                    <img src="media/banner.png" alt="banner" width="350">
                    <tt>
                        Dem Gems Online
                        <br>
                        The internet's <i>finest</i> mediocre gems
                        <p id="date" style="margin-bottom: 0;"></p>
                        <p id="time" style="margin: 0;"></p>
                        <hr width="50%">
                        <?php
                            //Initialize products array
                            $products = populateArrayFromDatabase('products.dat');
                        
                            //initialize subtotal value to zero
                            $subtotal = 0;
                            
                            //Loop through all the indicies
                            for($i=0; $i<countItemsInDatabase('products.dat'); $i++) {
                                //Cast monetary values in the products array to markedup values of floats
                                $products[$i][2] = (float) markupPrice($products[$i][2]);

                                //Cast numeric values in the POST  array (productOrder)s to integers
                                $_POST["productOrder$i"] = (int) $_POST["productOrder$i"];
                                
                                if($_POST["productOrder$i"] != 0) {
                                    printf("<span style='float: left'> &nbsp %s %s</span><span style='float: right'>$%s &nbsp </span><br>"
                                       ,number_format($_POST["productOrder$i"],0,'',',')
                                       ,pluralize($products[$i][0])
                                       ,number_format($products[$i][2]*$_POST["productOrder$i"],2,'.',',')
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
                               ,'$'.number_format($subtotal,2,'.',',')
                               ,'$'.number_format($subtotal*.05,2,'.',',')
                               ,'$'.number_format($subtotal*1.05,2,'.',',')
                            );
                        ?>
                        <br>
                        <hr width="50%">
                        Thank you for your purchase, <?php echo ucfirst($_POST['username']);?>!
                        <br>
                        We hope to see you again soon
                        <br>
                        <br>
                    </tt>
                </div>
            </div>
        </center>

        <!--Link to external javascript file-->
        <script src="resources/scripts.js"></script>
    </body>
</html>