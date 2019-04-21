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
        <title>Airfryers And Things | Crock Pots</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.jpg">

        <!--Link to functions-->
        <?php  ?>
    </head>
    <body>
        <!--Display the Header (via external php file)-->
        <?php require("resources/header.php"); ?>
            
        <!--Display the main content (i.e. Slow Cooker products)-->
        <?php displayProducts("slowCooker"); ?>
            
        <!--Display the Footer (via external htm file)-->
        <?php require("resources/footer.htm"); ?>
    </body>
</html>