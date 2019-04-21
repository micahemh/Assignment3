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
    //Initialize Functions
    require('resources/functions.php');
    
    //Initialize Username Cookie
    setUserCookie();
    
    //Initialize Cookie Timeout Cookie
    if(isset($_POST['timeout'])) {
        $_SESSION['timeout'] = $_POST['timeout']*60;
    
        //Initialize Username Cookie with Timeout
        setUserCookie($_POST['timeout']);
    }
    
    //Logout if necessary
    logout();

    //Redirect upon successful login
    redirectFromLogin();
    
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
        <title>Airfryers And Things | Login</title>
        
        <!--Link for tab icon-->
        <link rel="icon" href="resources/media/tabIcon.jpg">
    </head>
    <body>
        <div>
            <!--Display the Header (via external php file)-->
            <?php require("resources/header.php"); ?>
            
            <!--Display the main content (i.e. acount options---login or logout)-->
            <main class="backgroundPlatform" id="accountOptions">
                <!--Display if nobody is signed in-->
                <?php if(!isset($_COOKIE['username'])) { ?>
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
                            <input type="text" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; else echo ""; ?>" placeholder="Enter your username" style="margin-bottom: .25em;">
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
                    <!--Option 3: Login with guest account-->
                    <div>
                        Don't want to buy anything today?<br>
                        Peruse our products as a VIP guest!<br><br>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <input type="hidden" name="username" value="guest">
                            <input type="submit" name="SUBMIT" value="Sign in with a guest account!">
                        </form>
                    </div>
                <?php } ?>
                <!--Display if user is logged in as a guest-->
                <?php if(isset($_COOKIE['username']) && $_COOKIE['username'] === "guest") {?>
                    <!--Option 1: Register for a new account from guest-->
                    <div>
                        Want to have your own account?<br>
                        Make an account with us today!<br><br>
                        <a href="registration.php"><button>Register a new account</button></a>
                    </div>
                    <!--Option 2: Login with current information-->
                    <div>
                        Sign in if you want to shop.
                        <br>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
                            <input type="text" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; else echo ""; ?>" placeholder="Enter your username" style="margin-bottom: .25em;">
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
                    <!--Option 3: Log guest out-->
                    <div>
                        Done browsing for today?<br>
                        Don't forget to log out!<br><br>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <input type="hidden" name="logout" value="true">
                            <input type="submit" name="SUBMIT" value="Log out">
                        </form>
                    </div>
                <?php } ?>
                <!--Display if user is registered and logged in-->
                <?php if(isset($_COOKIE['username']) && $_COOKIE['username'] !== "guest") {?>
                    <!--Option 1: Modify Account Details-->
                    <div>
                        Want to update your credentials?<br>
                        Modify your account below!<br><br>
                        <form action="registration.php" method="POST">
                            <input type="hidden" name="modify" value="true">
                            <input type="submit" name="SUBMIT" value="Modify your current account">
                        </form>
                    </div>
                    <!--Option 2: Modify cookie timeout-->
                    <div>
                        How many minutes do you want to stay logged in before an automatic logout?<br>
                        <form action='<?php echo $_SERVER["PHP-SELF"]; ?>' method='POST'>
                            <input type='number' min='1' pattern='\d+' value='<?php if(isset($_SESSION['timeout'])) echo $_SESSION['timeout']; else echo 60;?>' style='width: 10em;' class='input' name='timeout'><br>
                            <input type='submit' name='submit' value='submit'>
                        </form>
                    </div>
                    <!--Option 3: Log user out-->
                    <div>
                        Done shopping for today?<br>
                        Don't forget to log out!<br><br>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
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