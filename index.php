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
        <title>Airfryers And Things | Home</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.jpg">
    </head>
    <body>
        <div>
            <!--Display the Header (via external php file)-->
            <?php require("resources/header.php"); ?>
            
            <!--Display the main content-->
            <main class="backgroundPlatform" style='text-align: center;'>
                <h1>Welcome!</h1>
                Welcome to the home of the Airfryers And Things online website!
                We are proud the wide variety of kitchenware appliances we are
                able to offer—including air fryers, slow cookers, and pressure
                cookers—to you here on our website. Take a look below to get
                started!
                <hr width="75%">
                
                <h3><u>Air Fryers</u></h3>
                According to Taste of Home, "The air fryer is essentially an
                amped-up countertop convection oven. Its compact space
                facilitates even faster cooking. The top of the unit holds a
                heating mechanism and a fan. Hot air rushes down and around food
                placed in a fryer-style basket. This rapid circulation makes the
                food crisp, much like deep frying. Cleanup is super easy too,
                and most units have a dishwasher safe basket."<br>
                <br>
                Interested? Shop for your new air fryer today!<br>
                <a href="airFryers.php" style="text-decoration: none;"><button>Shop for air fryers!</button></a>
                <hr width="75%">
                
                <h3><u>Slow Cookers</u></h3>
                According to Digital Trends, "This small electric appliance, a
                staple of many homes for more than 30 years, is based on the
                principles of slow cooking. The concept of slow cooking is
                simple: Put food into some sort of container or contained area
                and let it cook slowly. It's a method used in barbecue pits and
                pig roasts, where low temperatures and a lot of time allow meat
                to become tender. Slow cooking can be done via dry heat, as in
                an oven or roaster, or it can be moist, by involving liquid
                during the cooking process. Slow cookers use moisture in a
                unique way because they remain sealed during the cooking
                process. As food cooks and lets off steam, the condensation
                collects inside the device and acts as a baster."<br>
                <br>
                Interested? Shop for your new slow cooker today!<br>
                <a href="slowCookers.php" style="text-decoration: none;"><button>Shop for slow cookers!</button></a>
                <hr width="75%">
                
                <h3><u>Pressure Cookers</u></h3>
                According to Digital Trends, "A pressure cooker is an airtight
                cooking device that cooks food quickly, thanks to the steam
                pressure that builds up inside. The steam also makes the food
                moist, which is why this device is perfect for meat stews,
                cheesecakes, and much more."<br>
                <br>
                Interested? Shop for your new pressure cooker today!<br>
                <a href="pressureCookers.php" style="text-decoration: none;"><button>Shop for pressure cookers!</button></a>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
    </body>
</html>