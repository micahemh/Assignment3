<!--External Header page for Assignment 3

Lauren Lee and Micah Higashi
ITM 352
18 April 2019
Professor Kazman
-->
<header class="backgroundPlatform">
    <div style="text-align: left;">
        <a href="<?= $_SERVER['PHP_SELF'] ?>">      
            <img src="resources/media/banner.png">
        </a>
    </div>
    <div style="text-align: right;">
        <a href="index.php" style="text-decoration: none;">
            <img src="resources/media/home.png" alt="Home Page" width="50" height="50" style="margin-right: .5em;">
        </a>
        <a href="login.php" style="text-decoration: none;">
            <img src="resources/media/login.png" alt="Login Page" width="75" height="50">
        </a>
        <?php
            $user = "username";
            if(isset($user)) {
                print "<br><br>Welcome, $user!";
            }
        ?>
    </div>
    <div style="grid-column: 1 / span 2">
        <span>Air Fryers</span>
        <span>Crock Pots</span>
        <span>Pressure Cookers</span>
    </div>
</header>

<!--Link to functions-->
<?php require("resources/functions.php"); ?>