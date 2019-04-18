<!DOCTYPE html>
<!--
Lauren Lee and Micah Higashi
ITM 352
18 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page is the registration form.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Airfryers And Things | Invoice</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="media/tabIcon.jpg">
    </head>
    <body>
        <div>
            <!--Display the Header (via external php file)-->
            <?php require("resources/header.php"); ?>
            
            <!--Display the main content (i.e. registration form)-->
            <main class="backgroundPlatform">
                <center>
                    To create a new Airfryers And Things account, please enter the following information below:<br>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <br>Username<br>
                        <input type='text' name="username" value="<?php echo $_POST['username']; ?>" placeholder="Create a username" style="margin-bottom: .25em;"><br>
                        <?php
                        if (isset($_POST['registrationAttempted']) && isset($GLOBALS['errors']['username'])) {
                            foreach ($GLOBALS['errors']['username'] as $key => $val) {
                                print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                            }
                        }
                        ?>
                        <br>Password<br>
                        <input type='password' name="password_1" placeholder="Enter your password" style="margin-bottom: .25em;"><br>
                        <input type='password' name="password_2" placeholder="Confirm your password" style="margin-bottom: .5em;"><br>
                        <?php
                        if (isset($_POST['registrationAttempted']) && isset($GLOBALS['errors']['password'])) {
                            foreach ($GLOBALS['errors']['password'] as $key => $val) {
                                print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                            }
                        }
                        ?>
                        <br>Email<br>
                        <input type='text' name="email_1" value="<?php echo $_POST['email_1']; ?>" placeholder="Enter your email" style="margin-bottom: .25em;"><br>
                        <input type='text' name="email_2" value="<?php echo $_POST['email_2']; ?>" placeholder="Confirm your email" style="margin-bottom: .5em;"><br>
                        <?php
                        if (isset($_POST['registrationAttempted']) && isset($GLOBALS['errors']['email'])) {
                            foreach ($GLOBALS['errors']['email'] as $key => $val) {
                                print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                            }
                        }
                        ?>
                        <br>
                        <input type="submit" name="registrationAttempted" value="Create your new account!">
                    </form>
                </center>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
    </body>
</html>