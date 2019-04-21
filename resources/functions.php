<!--
Lauren Lee and Micah Higashi
ITM 352
19 April 2019
Professor Kazman

Assignment 3: Generates an eCommerce Web application

This page documents all the functions used in our Assignment 3 web application.
-->
<?php
    /* encryptForCSV
       purpose: hashes strings for use in CSV
       input(s): (string) "val" => string to be hashed (and modified to ensure no CSV characters persist)
       output: (string) $retVal => hashed string form of plaintext input string
    */
    function encryptForCSV($val) {
        //Initialize retVal to the encrypted version of the provided password within an array
        $retVal = str_split(password_hash($val,PASSWORD_DEFAULT),1);

        //Loop through each of the array's/hashed code's characters
        for($i=0; $i<sizeOf($retVal); $i++) {
            //Check if this index's character is a comma
            if($retVal[$i] == ',') {
                //If it is a comma, change it to a 'C' character so as to not break the CSV format
                $retVal[$i] == 'C';
            }
            //Check if this index's character is a semi-colon
            else if($retVal[$i] == ';') {
                //If it is a semi-colon, change it to a 's' character so as to not break the CSV format
                $retVal[$i] == 's';
            }
        }

        //Convert retVal array back to a single retVal string
        $retVal = implode($retVal);

        //Return the modified encyrpted string
        return $retVal;
    }
    
    /* countItemsInDatabase
        purpose: counts the number of products in a given database (either usernames or products)
        input(s): (string) $filename => the name of the database file
        output: (integer) $counter => the number of products in the database
    */
    function countItemsInDatabase($filename) {
        //Opens the file pointer
        $fp = fopen($filename,"r") or die("Unable to open $filename file!");
        //Checks the filesize
        $fs = filesize($filename);
        //initialize counter
        $counter = 0;
        //Read the contents and set it to a string variable, "contents" 
        $contents = fread($fp, $fs);
        
        //Loop throughout all characters of "contents"
        for($i=0; $i<$fs; $i++) {
            //Check if the character at the given index is a semi-colon
            if ($contents[$i] == ";") {
                //Semi-colon (in CSV format) means that there is a distinct product. Thus increment counter variable.
                $counter++;
            }
        }
        
        //Close the file pointer
        fclose($fp);

        //Return the integer amount of products in aforementioned database
        return $counter;
    }
    
    /* populateArrayFromDatabase
        purpose: populates the products array from the products database
        input(s): (string) $filename => the name of the database file
        output: (array) $array => resulting array from database file
    */
    function populateArrayFromDatabase($filename) {
        //Initialize array return value
        $array = null;
        $numItems = countItemsInDatabase($filename);

        $fp = fopen($filename, "r") or die("Unable to open $filename file!");
        $fs = filesize($filename);

        $contents = fread($fp, $fs);

        $arrayItem = explode(";", $contents);

        for ($i = 0; $i < $numItems; $i++) {
            $arrayItemData = explode(",", $arrayItem[$i]);

            for ($j = 0; $j < sizeOf($arrayItemData); $j++) {
                $array[$i][$j] = trim($arrayItemData[$j]);
            }
        }

        fclose($fp);

        return $array;
    }
    
    /* convertToProductsArray
        purpose: converts the basic array (populated from the database) into an associative array tailored to products
        input(s): (array) $array => the raw data array
        output: (array) $retArr => resulting array from data cleansing
    */
    function ConvertToProductsArray($array) {
        //Initialize return array to null
        $retArr = null;
        
        //loop through all of the items in the parameter array (excluding the first entry which is an example format)
        for ($i = 0; $i < sizeOf($array); $i++) {
            $retArr[$i]['type'] = $array[$i][0];
            $retArr[$i]['name'] = $array[$i][1];
            $retArr[$i]['image'] = 'resources/media/'.$array[$i][2].'.jpg';
            $retArr[$i]['description'] = $array[$i][3];
            $retArr[$i]['price'] = (int) $array[$i][4];
        }
        
        return $retArr;
    }
    
    /* convertToUsersArray
        purpose: converts the basic array (populated from the database) into an associative array tailored to users
        input(s): (array) $array => the raw data array
        output: (array) $retArr => resulting array from data cleansing
    */
    function ConvertToUsersArray($array) {
        //Initialize return array to null
        $retArr = null;
        
        //loop through all of the items in the parameter array
        for ($i = 0; $i < sizeOf($array); $i++) {
            $retArr[$i]['username'] = $array[$i][0];
            $retArr[$i]['password'] = $array[$i][1];
            $retArr[$i]['email'] = $array[$i][2];
        }
        
        return $retArr;
    }
    
    /* displayProducts
        purpose: displays the products according to whatever page this function is called on
        input(s): (string) $type => the type of product: airFryer, slowCooker, or pressureCooker
        output: n/a
    */
    function displayProducts($type) {
        //Initialize the products array
        $products = convertToProductsArray(populateArrayFromDatabase('resources/products.dat'));
        print '<main style="margin-bottom: -.75em;">';
        print "<form action=".$_SERVER['PHP_SELF']." method='POST'>";
        
        //Product Display        
        for($i=0; $i<sizeOf($products); $i++) {
            if($products[$i]['type'] == trim($type)) {
                printf('<div class="backgroundPlatform product">
                            <div style="grid-column: 1;">
                                <img src="%s" alt="%s">
                            </div>
                            <div style="grid-column: 2; text-align: left; ">
                                <h2>%s</h2>
                                <h6>$%.2f</b></h6>
                                %s
                            </div>'
                    ,$products[$i]['image']//path to the image of the product
                    ,$products[$i]['name']//Alternative text if image doesn't display
                    ,$products[$i]['name']//Title of product
                    ,$products[$i]['price']//Price of product
                    ,$products[$i]['description']//Short description of the product
                );
            
                //Offers the chance for logged usres (but not guest) to select items
                if(isset($_COOKIE['username'])) {
                    if($_COOKIE['username'] !== "guest") {
                        $value = 0;
                        if(isset($_SESSION['cart_quantities'][$i])) {
                            $value = $_SESSION['cart_quantities'][$i];
                            echo '<script>console.log("from SESSION");</script>';
                        }
                        else if(isset($_POST['submit_button'])) {
                            $value = $_POST["productOrder"][$i];
                            echo '<script>console.log("from POST");</script>';
                        }
                        if($products[$i]['type'] == trim($type)) {
                            printf('    <div style="grid-column: 3; text-align: center;">
                                            <h6>How many?</h6>
                                            <input type="number" min="0" pattern="\d+" value="%d" style="width: 6em;" name="productOrder[%d]">
                                        </div>
                                    </div>'
                                ,$value
                                ,$i
                            );
                        }
                        else {
                            $_POST["productOrder[$i]"] = 4;
                        }
                    }
                    else {
                        print "<div style='grid-column: 3; text-align: center;'>
                                   Want to buy something?<br><br>
                                   <a href='login.php'>Log me in!</a><br>
                                   or<br>
                                   <a href='registration.php'>Sign me up!</a>
                               </div>";
                    }
                }
                else {
                    echo '<div></div>';
                }
            }
            //close the div
            print '</div>';
            if($products[$i]['type'] == trim($type)) {
                print '<br>';
            }
        }
        
        //Post current values
        if(isset($_COOKIE['username']) && $_COOKIE['username'] !== "guest")
            echo '<center><input type="submit" name="submit_button" value="Update my cart!" style="margin-top: -0.25em; margin-bottom: 1em;"></center>';
        print "</form></main>";
    }

    /* redirectFromLogin
      purpose: Redirects to another page* after a successful log-in (either the last of my pages that the user was on OR if they have no "history", to the index)
      input(s): n/a
      output: n/a (but will actively redirect if valid user information is submitted)
     */
    function redirectFromLogin() {
        if(isset($_POST['username'])) {
            //Optimistically assume login is successful (initialize loginSuccessful to true)
            $loginSuccessful = true;

            //Verifies that provided username is valid for login
            if (!validUsernameForLogin()) {
                $loginSuccessful = false;
            }

            //Verifies that provided password is valid for login
            if (!validPasswordForLogin()) {
                $loginSuccessful = false;
            }

            //Checks to see if the login succeeded
            if ($loginSuccessful) {
                //Redirect to the index
                header('location:index.php');

                //As a precaution, terminate the remainder of the processes on this page
                die();
            }
        }
    }

    /* validLogin
      purpose: checks that both username and password were valid
      input(s): n/a
      output: (boolean) $validated =>
     */
    function validLogin() {
        //Initialize validated to true
        $validated = true;
        
        if(!validUsernameForLogin()) {
            $validated = false;
        }
        
        if(!validPasswordForLogin()) {
            $validated = false;
        }

        //Submit finalized determination whether or not username is valid
        return $validated;
    }

    /* validUsernameForLogin
      purpose: Validates the username after a login attempt
      input(s): n/a
      output: (boolean) $validated =>
      (also actively assigns error messages for respective problems)
     */
    function validUsernameForLogin() {
        //Initialize validated to true
        $validated = true;

        //Verify that provided username is not just an empty string
        if ($_POST['username'] === "") {
            //Add error message to errors array (empty string)
            $GLOBALS['errors']['username']['empty'] = "No username was provided.";
            
            //Reset validated to false
            $validated = false;
        }
        //Only display further error(s) if a password was attempted (not an empty string)
        else {
            //Verify that provided username exists in the database
            if (!usernameExists($_POST['username'])) {
                //Add error message to errors array (already in use)
                $GLOBALS['errors']['username']['nonexistant'] = "This username isn't currently in our database.";

                //Reset validated to false
                $validated = false;
            }
        }

        //Submit finalized determination whether or not username is valid
        return $validated;
    }

    /* validPasswordForLogin
      purpose: Validates the password after a login attempt
      input(s): n/a
      output: (boolean) $validated =>
      (also actively assigns error messages for respective problems)
     */
    function validPasswordForLogin() {
        //Initialize validated to true
        $validated = true;

        //Determine the correct password
        $correct = "";
        $users = populateArrayFromDatabase('resources/users.dat');
        for ($i = 0; $i < sizeOf($users); $i++) {
            if (trim($users[$i][0]) == $_POST['username']) {
                $correct = $users[$i][1];
            }
        }

        //Verify that provided username is not just an empty string
        if (isset($_POST['password']) && $_POST['password'] === "") {
            //Add error message to errors array (empty string)
            $GLOBALS['errors']['password']['empty'] = "No password was provided.";

            //Reset validated to false
            $validated = false;
        }
        //Only display further error(s) if a password was attempted (not an empty string)
        else {
            //Verify that provided password corresponds to encrypted password on file
            if (isset($_POST['password']) && !password_verify($_POST['password'], $correct)) {
                //Add error message to errors array
                $GLOBALS['errors']['password']['incorrect'] = "The provided password is incorrect.";

                //Reset validated to false
                $validated = false;
            }
        }

        //Submit finalized determination whether or not username is valid
        return $validated;
    }

    /* usernameExists
      purpose: Verifies that the provided username exists
      input(s): n/a
      output: (boolean) $exists => whether or not the username is in the users database
     */
    function usernameExists($provided) {
        //Initialize exists to false
        $exists = false;

        //Initialize a file pointer
        $fp = fopen("resources/users.dat", "r");

        //Convert file to string
        $contents = stream_get_contents($fp);

        //Check if there are any words in contents that match the provided username
        if (preg_match("/\b$provided\b/i", $contents)) {
            $exists = true;
            if ($provided === "") {
                $exists = false;
            }
        }

        //Close the file pointer
        fclose($fp);

        //Return whether or not the value is currently in the database
        return $exists;
    }
    
    /* redirectFromRegistration
      purpose: Redirects to index after a successful registration
      input(s): n/a
      output: n/a (but will actively redirect if valid user information is submitted)
     */
    function redirectFromRegistration($modify) {
        //Optimistically assume registration is successful (initialize registrationSuccessful to true)
        $registrationSuccessful = true;
        
        //For account modification, check to make sure the correct current user password is provided
        

        //Verifies that provided username is valid for registration
        if (!validUsernameForRegistration($modify)) {
            $registrationSuccessful = false;
        }

        //Verifies that provided password is valid for registration
        if (!validPasswordForRegistration()) {
            $registrationSuccessful = false;
        }

        //Verifies that provided email is valid for registration
        if (!validEmailForRegistration()) {
            $registrationSuccessful = false;
        }

        //Checks to see if the registration succeeded
        if ($registrationSuccessful) {
            //If this is an account modification, edit the database file
            modifyAccountInformation($_COOKIE['username'],$_POST['username'],password_hash($_POST['password_1'],PASSWORD_DEFAULT),$_POST['email_1']);
            
            //Redirect to the index
            header('location:index.php');

            //As a precaution, terminate the remainder of the processes on this page
            die();
        }
    }

    /* validRegistration
      purpose: checks that username, password, and email were all valid
      input(s): n/a
      output: (boolean) $validated =>
     */
    function validRegistration() {
        //Initialize validated to true
        $validated = true;
        
        if(!validUsernameForRegistration()) {
            $validated = false;
        }
        
        if(!validPasswordForRegistration()) {
            $validated = false;
        }
        
        if(!validEmailForRegistration()) {
            $validated = false;
        }

        //Submit finalized determination whether or not username is valid
        return $validated;
    }
    
    /* validUsernameForRegistration
    purpose: Validates the username after a registration attempt
    input(s): n/a
    output: (boolean) $validated =>
            (also actively assigns error messages for respective problems)
    implemented: redirectToStoreFromRegistration function
    */
    function validUsernameForRegistration($modify="true") {
        if(isset($_POST['username'])) {
            //Initialize validated to true
            $validated = true;

            //Verify that the user actually provided a username
            if($_POST['username'] === "") {
                //Add error message to errors array
                $GLOBALS['errors']['username']['nothing'] = "A potential username must be submitted.";

                //Reset validated to false
                $validated = false;
            }
            //Only display further error(s) if a password was attempted (not an empty string)
            else {
                //Verify that provided username is of suitable length (4-11 characters)
                if(((strlen($_POST['username'])) < 4 || (strlen($_POST['username']) > 11))) {
                    //Add error message to errors array (between 4-11 chars)
                    $GLOBALS['errors']['username']['length'] = "Username must be of suitable length (4 to 11 characters).";

                    //Reset validated to false
                    $validated = false;
                }

                //Verify that provided username only contains alphanumeric characters
                if(!ctype_alnum($_POST['username'])) {
                    //Add error message to errors array (alphanumeric characters only)
                    $GLOBALS['errors']['username']['alphanumeric'] = "Username must consist of alphanumeric characters only.";

                    //Reset validated to false
                    $validated = false;
                }

                //Verify that provided username isn't already in use
                if (usernameExists($_POST['username']) && !$modify) {
                    //Add error message to errors array (already in use)
                    $GLOBALS['errors']['username']['repeated'] = "This username is already currently in use.";

                    //Reset validated to false
                    $validated = false;
                }
            }

            //Submit finalized determination whether or not username is valid
            return $validated;
        }
    }

    /* validPasswordForRegistration
        purpose: Validates the password after a registration attempt
        input(s): n/a
        output: (boolean) $validated =>
                (also actively assigns error messages for respective problems)
        implemented: redirectToStoreFromRegistration function
    */
    function validPasswordForRegistration() {
        if(isset($_POST['password_1']) || isset($_POST['password_2'])) {
            //Initialize validated to true
            $validated = true;

            //Verify that the user actually provided a username
            if(($_POST['password_1'] === "") || ($_POST['password_2'] === "")) {
                //Add error message to errors array
                $GLOBALS['errors']['password']['nothing'] = "A potential password must be submitted in both fields.";

                //Reset validated to false
                $validated = false;
            }
            //Only display further error(s) if a password was attempted (not an empty string)
            else {
                //Verify that provided username is of suitable length (4-11 characters)
                if((strlen($_POST['password_1']) < 6) || (strlen($_POST['password_2']) < 6)) {
                    //Add error message to errors array (between 4-11 chars)
                    $GLOBALS['errors']['password']['length'] = "Both password fields must be at least six characters long.";

                    //Reset validated to false
                    $validated = false;
                }

                //Verify that provided passwords match each other
                if($_POST['password_1'] !== $_POST['password_2']) {
                    //Add error message to errors array (repeat)
                    $GLOBALS['errors']['password']['equivalent'] = "Both initial and confirmation password fields must match each other.";

                    //Reset validated to false
                    $validated = false;
                }

                //Verify that provided usernames don't contain any commas (,) or semi-colons (;)
                //I realize that the deliverables technically mention that all characters should be acceptable, but these will break my csv-based database file
                if(((strpos($_POST['password_1'], ',') !== false) || (strpos($_POST['password_1'], ';') !== false)) || ((strpos($_POST['password_2'], ',') !== false) || (strpos($_POST['password_2'], ';') !== false))) {
                    //Add error message to errors array (alphanumeric characters only)
                    $GLOBALS['errors']['password']['commaorsemi'] = "Username cannot contain any commas ',' or semi-colons ';'.";

                    //Reset validated to false
                    $validated = false;
                }
            }

            //Submit finalized determination whether or not password is valid
            return $validated;
        }
    }

    /* validEmailForRegistration
        purpose: Validates the email after a registration attempt
        input(s): n/a
        output: (boolean) $validated =>
                (also actively assigns error messages for respective problems)
    */
    function validEmailForRegistration() {
        if(isset($_POST['email_1']) || isset($_POST['email_2'])) {
            //Initialize validated to true
            $validated = true;

            //Verify that the user actually provided an email address
            if(($_POST['email_1'] === "") || ($_POST['email_2'] === "")) {
                //Add error message to errors array
                $GLOBALS['errors']['email']['nothing'] = "Your email must be submitted in both fields.";

                //Reset validated to false
                $validated = false;
            }
            //Only display further error(s) if an email address was attempted (not an empty string)
            else {
                //Verify that provided username in not the correct general format (X@Y.Z)
                if(!(filter_var($_POST['email_1'],FILTER_VALIDATE_EMAIL) !== false)) {
                    //Add error message to errors array (must be correct format)
                    $GLOBALS['errors']['email']['format'] = "Email address fields must contain suitable format (i.e. [string]@[string].[string]).";

                    //Reset validated to false
                    $validated = false;
                }

                //Break email string into parts (before/after the @/. symbols) (i.e. X@Y.Z)
                $X = @explode('@', $_POST['email_1'])[0];
                $Y = @explode('@', explode('.', $_POST['email_1'])[0])[1];
                $Z = @explode('@', explode('.', $_POST['email_1'])[1])[0];

                //Verify that X (user address) is alphanumeric and/or contains periods and/or underscors
                if(preg_match('/a-zA-Z\.0-9_/',$X)) {
                    //Add error message to errors array (alphanumeric or period or underscores)
                    $GLOBALS['errors']['email']['user'] = "Email host machines must only consist of alphanumeric or '.' or '_' characters";

                    //Reset validated to false
                    $validated = false;
                }

                //Verify that Y (host machine) is alphanumeric and/or contains periods
                if(preg_match('/a-zA-Z\.0-9/',$Y)) {
                    //Add error message to errors array (alphanumeric or period)
                    $GLOBALS['errors']['email']['host'] = "Email host machines must only consist of alphanumeric or '.' characters";

                    //Reset validated to false
                    $validated = false;
                }

                //Verify that Z (domain name) is either two or three characters long
                if(strlen($Z)<2 || strlen($Z)>3) {
                    //Add error message to errors array (two or three characters in domain)
                    $GLOBALS['errors']['email']['domain'] = "Email domain names must be two or three characters long.";

                    //Reset validated to false
                    $validated = false;
                }
            }

            //Submit finalized determination whether or not password is valid
            return $validated;
        }
    }

    /* setUserCookie
      purpose: sets the username cookie
      input(s): (string) $page => t
      output: sets the username cookie
     */
    function setUserCookie($timeout=3600) {
        if(isset($_COOKIE['username'])) {
            if(isset($_POST['username']) && $_POST['username'] !== "guest") {
                if(isset($_SESSION['timeout'])) {
                    setcookie('username',$_POST['username'],time()+$_SESSION['timeout']);
                }
                else {
                    setcookie('username',$_POST['username'],time()+$timeout);
                }
            }
            else {
                setcookie('username',$_COOKIE['username'],time()+3600);
            }
        }
        else if(isset($_POST['username'])) {
            if(validLogin() || validRegistration() || $_POST['username'] == "guest") {
                if(isset($_SESSION['timeout'])) {
                    setcookie('username',$_POST['username'],time()+$_SESSION['timeout']);
                }
                else {
                    setcookie('username',$_POST['username'],time()+$timeout);
                }
            }
        }
    }

    /* logout
      purpose: logs user off and deletes the username cookie
      input(s): n/a
      output: deletes the username cookie
     */
    function logout() {
        if(isset($_POST['logout'])) {
            setcookie('username','',time()-1234567890);
            header('location:account.php');
            die();
        }
    }

    /* modifyAccountInformation()
      purpose: modifies the account information
      input(s): n/a
      output: rewrites the database
     */
    function modifyAccountInformation($oldUsername,$newUsername,$newPassword,$newEmail) {
        $filename = 'resources/users.dat';

        //Initialize array return value
        $array = populateArrayFromDatabase('resources/users.dat');
        $numItems = countItemsInDatabase($filename);

        //write the array to the file
        $fp = fopen($filename, "a") or die("Unable to open users database file!");
        $fs = filesize($filename);

        for ($i = 0; $i < $numItems; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if($array[$i][0] == $oldUsername) {
                    continue;
                }
                else if($array[$i][0] != $oldUsername) {
                    fwrite($fp,trim($array[$i][$j]));
                }
                if ($j < 2) {
                    fwrite($fp,',');
                }
                else if ($j == 2) {
                    fwrite($fp,";\n");
                }
            }
        }
        fwrite($fp,"$newUsername,$newPassword,$newEmail;\n");

        fclose($fp);
    }
    
    /* updateSessionValues
      purpose: updates the values in sessions from post array
      input(s): n/a
      output: session's cart_quantities values are updated
     */
    function updateSessionValues() {
        $products = ConvertToProductsArray(populateArrayFromDatabase('resources/products.dat'));
        if($_COOKIE['username'] != "guest") {
            // if the session is new, register a shopping cart
            if (!isset($_SESSION['cart_quantities'])) {
                // start the cart out with no items selected by placing 0 in each product quantity slot
                $cart = array_fill(0, count($products), 0);
                // put the cart in this users session
                $_SESSION['cart_quantities'] = $cart;
            }
            
            for($i=0; $i<countItemsInDatabase('resources/products.dat'); $i++) {
                if(isset($_POST['productOrder'][$i])) {
                    $_SESSION['cart_quantities'][$i] = $_POST['productOrder'][$i];
                }
            }
        }
    }
    
    /* redirectToInvoiceFromCart
      purpose: 
      input(s): 
      output: 
     */
    function redirectToInvoiceFromCart() {
        if(isset($_POST['checked'])) {
            $counter = array();
            for($i=0; $i<countItemsInDatabase("resources/products.dat"); $i++) {
                if(isset($_POST['checked'][$i])) {
                    if($_POST['checked'][$i] == "true") {
                        $counter[] = true;
                    }
                    else if($_POST['checked'][$i] == "false") {
                        $counter[] = false;
                    }
                }
                else
                    continue;
            }
            
            //If the cart has no revisions, redirect to the invoice
            if(array_sum($counter)==sizeOf($_POST['checked'])) {
                header("location:resources/invoice.php");
                die();
            }
        }
    }
    
    /* clearCart
      purpose: clears the cart (session destroy is giving me a hard time)
      input(s): n/a
      output: clears the cart
     */
    function clearCart() {
        for($i=0; $i<countItemsInDatabase('products.dat'); $i++) {
            $_SESSION['cart_quantities'][$i] = 0;
        }
    }
    
    /* show
      purpose: personal diagnostic tool so as to not have to type the annoying
               underscores as well as have it in a nice, more readable format
      input(s): n/a
      output: the var_dump() of an element in a viewable format (set to $_POST by default)
     */
    function show($x="") {
        echo "<pre>";
        if($x==="") {
            var_dump($_POST);
        }
        else {
            var_dump($x);
        }
        echo "</pre>";
    }
?>