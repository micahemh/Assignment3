<!--External Header page for Assignment 2

Brayden Kubota and Micah Higashi
ITM 352
04 April 2019
Professor Kazman
-->
<header class="backgroundPlatform">
    <div style="text-align: left;">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <input type="hidden" name="username" value="$_POST['username']">
            <input type="hidden" name="loginSuccessful" value="$_POST['loginSuccessful']">
            <input type="hidden" name="registrationSuccessful" value="$_POST['registrationSuccessful']">
            <input type="hidden" name="guestSuccessful" value="$_POST['guestSuccessful']">
            <input type="image" alt="Submit" src="resources/media/banner.png" height="100">
        </form>
    </div>
    <div style="text-align: right;">
        <?php  ?>
        <form action="index.php" method="POST">
            <input type="hidden" name="username" value="$_POST['username']">
            <input type="hidden" name="loginSuccessful" value="$_POST['loginSuccessful']">
            <input type="hidden" name="registrationSuccessful" value="$_POST['registrationSuccessful']">
            <input type="hidden" name="guestSuccessful" value="$_POST['guestSuccessful']">
            <input type="image" alt="Submit" src="resources/media/home.png" width="50" height="50">
        </form>
        <form action="login.php" method="POST">
            <input type="hidden" name="username" value="$_POST['username']">
            <input type="hidden" name="loginSuccessful" value="$_POST['loginSuccessful']">
            <input type="hidden" name="registrationSuccessful" value="$_POST['registrationSuccessful']">
            <input type="hidden" name="guestSuccessful" value="$_POST['guestSuccessful']">
            <input type="image" alt="Submit" src="resources/media/login.png" width="75" height="50">
        </form>
        <?php


            if(array_key_exists('loginSuccessful', $_POST) || array_key_exists('successfulRegistration',$_POST) || array_key_exists('guestSuccessful',$_POST)) {
                print "Welcome, ";
                if(($_POST['username'] == "Sign in with a guest account")) {
                    print "guest";
                }
                else {
                    print ucfirst($_POST['username']);
                }
                print "!";
            }
        ?>
    </div>
</header>