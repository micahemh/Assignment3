<!DOCTYPE html>
<!--
Lauren Lee and Micah Higashi
ITM 352
19 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page is the general format of a website. This page will be filled with a
general welcome, as well as links to our products pages.
-->
<?php
    require("resources/functions.php");
    
    if(isset($_COOKIE['username'])) {
        session_save_path('resources/sessions/.');
        session_id($_COOKIE['username']);
        session_start();
        updateSessionValues();
    }
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
            <main class="backgroundPlatform" style='text-align: center;'>
                <h1>Why, hello there!</h1>
                We are here to serve you in all your kitchenware appliance
                hunting needs in regards to air fryers, slow cookers, and
                pressure cookers!!<br><br>
                We will be updating our About Us page soon. In the meanwhile,
                please peruse through our products! You may just find that
                perfect machine for your home or business!
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
    </body>
</html>