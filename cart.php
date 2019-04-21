<!DOCTYPE html>
<!--
Lauren Lee and Micah Higashi
ITM 352
19 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page is the shopping cart page. Customers can reference this when they want
to know what they are tentatively purchasing from us.
-->
<?php
    require("resources/functions.php");
    
    if(isset($_COOKIE['username'])) {
        session_save_path('resources/sessions/.');
        session_id($_COOKIE['username']);
        session_start();
        updateSessionValues();
    }
    
    redirectToInvoiceFromCart();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Airfryers And Things | About Us</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.jpg">
    </head>
    <body>
        <div>
            <!--Display the Header (via external php file)-->
            <?php require("resources/header.php"); ?>
            
            <!--Display the main content-->
            <main>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <?php
                        //products array
                        $products = convertToProductsArray(populateArrayFromDatabase('resources/products.dat'));

                        //Handle shopping cart here
                        $cart = $_SESSION['cart_quantities'];

                        //If the session is new, register a shopping cart
                        if(!isset($_SESSION['cart_quantities'])) {
                            //start the cart out with no items selected by placing 0 in each product quantity slot
                            $cart = array_fill(0,countItemsInDatabase('resources/products.dat'),0);
                            //put the cart in this user's session
                            $_SESSION['cart_quantities'] = $cart;
                        }

                        //get the cart array from the current user's session

                        //Check that select_button submit was pressed. We must look for this because we want to be sure the user put in quantities for all the items they are interested in
                        //if (array_key_exists('select_button', $_POST)) {
                            // grab the quanities array from the form and replace previous cart quantities with new values
                            //$cart = $_POST['quantities'];

                            // put updated cart into the session (replaces old cart)
                            $_SESSION['cart_quantities'] = $cart;
                        //}

                        //Check if there's anything in the array
                        if(array_sum($cart) > 0) {
                            for($i=0; $i<countItemsInDatabase('resources/products.dat'); $i++) {
                                if(isset($cart[$i])) {
                                    if($cart[$i] == 0) {
                                        continue;
                                    }
                                    else {
                                        printf('<div class="backgroundPlatform product">
                                                    <div style="grid-column: 1;">
                                                        <img src="%s" alt="%s">
                                                    </div>
                                                    <div style="grid-column: 2; text-align: left; ">
                                                        <h2>%s</h2>
                                                        <h6>$%.2f</h6>
                                                        %s
                                                    </div>
                                                    <div style="grid-column: 3; text-align: center; ">
                                                        <h6 style="margin-top: 1em; margin-bottom: 1em;">You\'ve currently ordered</h6>
                                                        %d
                                                        <h6 style="margin-top: 1em; margin-bottom: 1em;">of this product.</h6><br>'
                                            ,$products[$i]['image']//path to the image of the product
                                            ,$products[$i]['name']//Alternative text if image doesn't display
                                            ,$products[$i]['name']//Title of product
                                            ,$products[$i]['price']//Price of product
                                            ,$products[$i]['description']//Short description of the product
                                            ,$cart[$i]//Amount currently ordereed
                                        );
                                        
                                        if(isset($_POST['checked'][$i]) && $_POST['checked'][$i] != "true") {
                                            printf('    <h6 style="margin-top: 1em; margin-bottom: 1em;">Enter your modified amount below:</h6>
                                                        <input type="number" min="0" pattern="\d+" value="%d" style="width: 6em;" name="productOrder[%d]">
                                                    </div>
                                                </div><br>'
                                                ,$_SESSION['cart_quantities'][$i]
                                                ,$i//current index
                                            );
                                        }
                                        else {
                                            printf('    <h6 style="margin-top: 1em; margin-bottom: 1em;">Is this value correct?</h6>
                                                        Yes! <input type="hidden" name="checked[%d]" value="false">
                                                        <input type="checkbox" name="checked[%d]" value="true" checked> 
                                                    </div>
                                                </div><br>'
                                                ,$i//current index
                                                ,$i//current index
                                            );
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            print "<div class='backgroundPlatform' style='text-align: center;'>
                                       You have no items in your cart!<br><br>
                                       Go ahead, live a little, and purchase some items!
                                   </div>";
                        }
                        if(isset($_SESSION['cart_quantities']) && !empty($_SESSION['cart_quantities'])) {
                            echo '<center><input type="submit" name="SUBMIT" value="Continue to your invoice!" style="margin-top: 0em; margin-bottom: -1em;"></center>';
                        }
                    ?>
                </form>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
    </body>
</html>