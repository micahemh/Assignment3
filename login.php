<!DOCTYPE html>
<!--
Brayden Kubota and Micah Higashi
ITM 352
04 April 2019
Professor Kazman

Assignment 2: Generates an eCommerce Web application with login functionality

This page prompts user to create a new account, log in, or sign on as a guest. 
On initial start up of index page, users are redirected automatically to this
login form so that current users don't have to click on a link to be transferred
here. People who are not currently registered are given a link to a registration
page.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dem Gems | Login</title>

        <!--Preset data provided by Netbeans-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.png">
        
        <!--Link for stylesheet-->
        <link rel="stylesheet" type="text/css" href="resources/style.css">

        <!--Link to functions-->
        <?php require("resources/functions.php"); ?>
        
        <!--Initialize data if none exists-->
        <?php
            if(!isset($_POST['loginAttempted'])) {
                $_POST['username'] = null;
                $_POST['password'] = null;
            }
        ?>
        
        <!--Advance to the index/Home/Store page if valid credentials are supplied-->
        <?php redirectToStoreFromLogin(); ?>
    </head>
    <body>
        <div></div>
        <div>
            <!--Display the Header (via external htm file)-->
            <?php require("resources/header.php"); ?>
            
            <!--Display the main content (i.e. login form/options)-->
            <main class="backgroundPlatform" id="loginOptions">
                <!--Option 1: Register for a new account-->
                <div>
                    Don't have log in credentials?<br>
                    Make your own account with us!<br><br>
                    <a href="registration.php"><button>Register a new account</button></a>
                </div>
                
                <!--Option 2: Login with current information-->
                <div>
                    Please log in below.
                    <br>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
                        <input type="text" name="username" value="<?php echo $_POST['username']; ?>" placeholder="Enter your username" style="margin-bottom: .25em;">
                        <?php
                            if(isset($_POST['loginAttempted']) && isset($GLOBALS['errors']['username'])) {
                                print "<br>";
                                foreach($GLOBALS['errors']['username'] as $key => $val) {
                                    print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                                }
                            }
                        ?>
                        <br>
                        <input type='password' name="password" placeholder="Enter your password" style="margin-bottom: .25em;">
                        <?php
                            if(isset($_POST['loginAttempted']) && isset($GLOBALS['errors']['password'])) {
                                print "<br>";
                                foreach($GLOBALS['errors']['password'] as $key => $val) {
                                    print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                                }
                            }
                        ?>
                        <br>
                        <button type='submit' name='loginAttempted' value="">Log in</button>
                    </form>               
                </div>
                
                <!--Option 3: Login with guest account-->
                <div>
                    Don't want to purchase anything today?<br>
                    Peruse our products as a VIP guest!<br><br>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="guestSuccessful" value="true">
                        <input type="submit" name="username" value="Sign in with a guest account">
                    </form>
                </div>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
        <div></div>
    </body>
    <div></div>
    FYI for the grader... Preset passwords are as follows:<br><br>
    itm352   :::   grader<br>
    asdf   :::   asdf<br>
    asdfasdf   :::   asdfasdf<br>
    rick   :::   kazman<br>
    austin   :::   itamoto<br>
    brayden   :::   kubota<br>
    micah   :::   higashi<br>
</html>