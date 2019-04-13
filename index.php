<!DOCTYPE html>
<!--
Brayden Kubota and Micah Higashi
ITM 352
04 April 2019
Professor Kazman

Assignment 2: Generates an eCommerce Web application with login functionality

Note: This project inspired by the work of Christie Mattos and Sheridan Wu's
example of an Online Bread Store

This page is the store display. It provides a loop for the product's displays as
well as a self-processing form which will only be redirected to the receipt if
the values are valid. Furthermore, it also automatically redirects to the login
page if no username is set. People can use the guest account if they prefer to
not log in. 

-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dem Gems | Home</title>

        <!--Preset data provided by Netbeans-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.png">
        
        <!--Link for stylesheet-->
        <link rel="stylesheet" type="text/css" href="resources/style.css">

        <!--Link to functions-->
        <?php require("resources/functions.php"); ?>
        
        <!--Redirect to Invoice if valid data was entered-->
        <?php redirectToInvoiceFromStore();?>
        
        <!--Redirect to Login if nobody is logged in-->
        <?php redirectToLoginFromStore(); ?>
    </head>
    <body>
        <div></div>
        <div>
            <!--Display the Header (via external htm file)-->
            <?php require("resources/header.php"); global $user; ?>
            
            <!--Display the main content (i.e. store products)-->
            <main>
                <?php
                    //Set values if not already set
                    if(!isset($_POST['productOrder'])) {
                        $_POST['productOrder'] = array(0,0,0,0,0);
                    }
                    
                    //Populate the products array
                    $productsArray = populateArrayFromDatabase('resources/products.dat');
                    
                    //Open form if somebody (...valid, not guest acccount) is logged in
                    if(isset($_POST['username']) && $_POST['username']!=="Sign in with a guest account") {
                    ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <?php    
                    }
                    
                    //Loop through products array
                    for($i=0; $i<sizeOf($productsArray); $i++) {
                        
                        //Display each product
                        printf('<div class="backgroundPlatform productDisplayContainer" style="margin-bottom: 0.5em;">
                                    <div style="grid-column: 1;">
                                        <img src="resources/media/%s.png" onmouseover="this.src=\'resources/media/%s.gif\'" onmouseout="this.src=\'resources/media/%s.png\'" alt="%s" height="125">
                                    </div>
                                    <div style="grid-column: 2; text-align: left; padding: 0.5em;">
                                        <big><big><strong>%s</strong></big></big><br>
                                        <small><small><b>$%.2f</b></small></small><br>
                                        %s
                                    </div>'
                            ,trim(strtolower($productsArray[$i][0]))//Initial png image
                            ,trim(strtolower($productsArray[$i][0]))//Mouseover gif image
                            ,trim(strtolower($productsArray[$i][0]))//Reinitialize png image
                            ,trim($productsArray[$i][0])//Alternate text
                            ,trim($productsArray[$i][0])//Name of product
                            ,number_format(markupPrice($productsArray[$i][2]),2,'.',',')//Price of product
                            ,trim($productsArray[$i][1])//Description of product
                        );
                        
                        //Offer the chance for logged users to select items
                        if(isset($_POST['username']) && $_POST['username']!=="Sign in with a guest account") {
                            printf('<div style="grid-column: 3;">
                                        <small><small><small>How many?</small></small></small>
                                        <input type="number" min="0" pattern="\d+" value="%s" style="width: 6em;" class="input" name="productOrder[%d]">'
                                ,$_POST["productOrder"][$i]//sticky value
                                ,$i//Index to product for productOrder
                            );
                            if(isset($GLOBALS['errors'][$i])) {
                                foreach($GLOBALS['errors'][$i] as $key => $val) {
                                    print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                                }
                            }
                            print "</div>";
                        }
                        
                        //For the guest user, incessantly offer the chance to be sign up/in
                        else {
                            print "<div>Change your mind and want to buy something?<br><br>
                                       <a href='login.php'>Log me in!</a><br>
                                       or<br>
                                       <a href='registration.php'>Sign me up!</a>
                                   </div>";
                        }
                        
                        //close the div
                        print '</div>';
                    }
                    
                    //Dispaly errors if no purchase was made
                    if(isset($GLOBALS['errors']['overall'])) {
                        foreach($GLOBALS['errors']['overall'] as $key => $val) {
                            print "<span style='color: red; font-weight: bold;' class='backgroundPlatform'>$val</span><br>";
                        }
                    }
                    
                    //For the users who are logged in (excepting guests), users can submit their forms (i.e. a submit form is displayed)
                    if(isset($_POST['username']) && $_POST['username']!=="Sign in with a guest account") {
                        $user = $_POST['username'];
                        printf("<input type='hidden' name='username' value=%s>
                               <input type='submit' value='Purchase these items' name='purchaseAttempted'>
                        </form>"
                            ,$user
                        );
                    }
                ?>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
        <div></div>
    </body>
</html>