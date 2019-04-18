<!DOCTYPE html>
<!--
Lauren Lee and Micah Higashi
ITM 352
18 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page is the Air Fryers page for our website.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Airfryers And Things | Air Fryers</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.jpg">

        <!--Link to functions-->
        <?php require("resources/functions.php"); ?>
    </head>
    <body>
        <div>
            <!--Display the Header (via external php file)-->
            <?php require("resources/header.php"); ?>
            
            <!--Display the main content (i.e. Air Fryer products)-->
            <main style="margin-bottom: -.75em;">
                <?php
                    displayProducts("airFryer");
                ?>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
    </body>
</html>