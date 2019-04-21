<!DOCTYPE html>
<!--
Lauren Lee and Micah Higashi
ITM 352
19 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page is the Air Fryers page for our website.
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
        <title>Airfryers And Things | Air Fryers</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.jpg">
    </head>
    <body>
        <!--Display the Header (via external php file)-->
        <?php require("resources/header.php"); ?>

        <!--Display the main content (i.e. Air Fryer products)-->
        <?php displayProducts("airFryer"); ?>

        <!--Display the Footer (via external htm file)-->
        <?php require("resources/footer.htm"); ?>
    </body>
</html>