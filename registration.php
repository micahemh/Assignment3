<!DOCTYPE html>
<!--
Brayden Kubota and Micah Higashi
ITM 352
04 April 2019
Professor Kazman

Assignment 2: Generates an eCommerce Web application with login functionality

This page prompts user to register a new account. Upon entry of data, this self-
processing form will redirect to the index if and only if the data is valid.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dem Gems | Registration</title>

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
            if(!isset($_POST['registrationAttempted'])) {
                $_POST['username'] = null;
                $_POST['password_1'] = null;
                $_POST['password_2'] = null;
                $_POST['email_1'] = null;
                $_POST['email_2'] = null;
            }
        ?>

        <!--Redirect page to store if valid data was entered-->
        <?php redirectToStoreFromRegistration(); ?>
    </head>
    <body>
        <div></div>
        <div>
            <!--Display the Header (via external htm file)-->
            <?php require("resources/header.php"); ?>
            
            <!--Display the main content (i.e. registration form)-->
            <main class="backgroundPlatform">
                
                To create a new Dem Gems account, please enter the following information below:<br>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <br>Username<br>
                    <input type='text' name="username" value="<?php echo $_POST['username']; ?>" placeholder="Create a username" style="margin-bottom: .25em;"><br>
                    <?php
                        if(isset($_POST['registrationAttempted']) && isset($GLOBALS['errors']['username'])) {
                            foreach($GLOBALS['errors']['username'] as $key => $val) {
                                print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                            }
                        }
                    ?>
                    <br>Password<br>
                    <input type='password' name="password_1" placeholder="Enter your password" style="margin-bottom: .25em;"><br>
                    <input type='password' name="password_2" placeholder="Confirm your password" style="margin-bottom: .5em;"><br>
                    <?php
                        if(isset($_POST['registrationAttempted']) && isset($GLOBALS['errors']['password'])) {
                            foreach($GLOBALS['errors']['password'] as $key => $val) {
                                print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                            }
                        }
                    ?>
                    <br>Email<br>
                    <input type='text' name="email_1" value="<?php echo $_POST['email_1']; ?>" placeholder="Enter your email" style="margin-bottom: .25em;"><br>
                    <input type='text' name="email_2" value="<?php echo $_POST['email_2']; ?>" placeholder="Confirm your email" style="margin-bottom: .5em;"><br>
                    <?php
                        if(isset($_POST['registrationAttempted']) && isset($GLOBALS['errors']['email'])) {
                            foreach($GLOBALS['errors']['email'] as $key => $val) {
                                print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                            }
                        }
                    ?>
                    <br>
                    <input type="submit" name="registrationAttempted" value="Create your new account!">
                </form>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
        <div></div>
    </body>
</html>