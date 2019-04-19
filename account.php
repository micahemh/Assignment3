<!DOCTYPE html>
<!--
Lauren Lee and Micah Higashi
ITM 352
18 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page is the general format of a website. This page will be filled with a
general welcome, as well as links to our products pages.
-->
<?php
    //Initialize Functions
    require('resources/functions.php');
    
    //Initialize Username Cookie
    setUserCookie();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Airfryers And Things | Login</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.jpg">

        <!--Redirect-->
        <?php if(isset($_POST['username'])) redirectFromLogin('index.php'); ?>
    </head>
    <body>
        <div>
            <!--Display the Header (via external php file)-->
            <?php require("resources/header.php"); ?>
            
            <!--Display the main content (i.e. acount options---login or logout)-->
            <main class="backgroundPlatform" id="accountOptions">
                <?php if(!isset($_COOKIE['username'])) {?>
                <!--Option 1: Register for a new account-->
                <div>
                    Don't have log in credentials?<br>
                    Make your own account with us!<br><br>
                    <a href="registration.php"><button>Register a new account</button></a>
                </div>
                <?php } else {?>
                <!--Option 1: Modify Account Details-->
                <div>
                    Need to update your credentials?<br>
                    Modify your account below!<br><br>
                    <a href="registration.php"><button>Modify your account</button></a>
                </div>
                <?php }?>
                
                <!--Option 2: Login with current information-->
                <div>
                    Please log in below.
                    <br>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
                        <input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; else echo ""; ?>" placeholder="Enter your username" style="margin-bottom: .25em;">
                        <?php
                        if (isset($_POST['loginAttempted']) && isset($GLOBALS['errors']['username'])) {
                            print "<br>";
                            foreach ($GLOBALS['errors']['username'] as $key => $val) {
                                print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                            }
                        }
                        ?>
                        <br>
                        <input type='password' name="password" placeholder="Enter your password" style="margin-bottom: .25em;">
                        <?php
                        if (isset($_POST['loginAttempted']) && isset($GLOBALS['errors']['password'])) {
                            print "<br>";
                            foreach ($GLOBALS['errors']['password'] as $key => $val) {
                                print "<span style='color: red; font-weight: bold;'>$val</span><br>";
                            }
                        }
                        ?>
                        <br>
                        <button type='submit' name='loginAttempted' value="">Log in</button>
                    </form>               
                </div>
                <?php if(!isset($_COOKIE['username'])) {?>
                <!--Option 3: Login with guest account-->
                <div>
                    Don't want to buy anything today?<br>
                    Peruse our products as a VIP guest!<br><br>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="username" value="guest">
                        <input type="submit" name="SUBMIT" value="Sign in with a guest account!">
                    </form>
                </div>
                <?php } else {?>
                <!--Option 1: Logout for now-->
                <div>
                    Done shopping for today?<br>
                    Don't forget to log out!<br><br>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="logout" value="true">
                        <input type="submit" name="SUBMIT" value="Log out">
                    </form>
                </div>
                <?php }?>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
    </body>
</html>
<center>
FYI for the grader... Preset passwords will be as follows:<br><br>
itm352   :::   grader<br>
asdf   :::   asdf<br>
asdfasdf   :::   asdfasdf<br>
rick   :::   kazman<br>
austin   :::   itamoto<br>
lauren   :::   lee<br>
micah   :::   higashi
</center>