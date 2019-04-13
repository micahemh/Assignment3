<!--
Brayden Kubota and Micah Higashi
ITM 352
04 April 2019
Professor Kazman

Assignment 2: Generates an eCommerce Web application with login functionality

This page documents all the functions used in this web application.
-->
<?php
/* redirectToLoginPageFromStore
    purpose: Redirects to the login page if there is nobody logged in
    input(s): n/a
    output: n/a (but will actively redirect if there is nobody logged in)
    implemented: index page
*/
function redirectToLoginFromStore() {
    //Checks to see if the username index is filled (aka: somebody is logged in)
    if(!isset($_POST['username']) || ($_POST['username'] == "")) {
        //Redirect; pass empty strings as user credentials
        echo "<form id='autoSubmit' action='login.php' method='POST'>
                <input type='hidden' name='username' value=''>
                <input type='hidden' name='password' value=''>
              </form>
              <script type='text/javascript'>
                  document.getElementById('autoSubmit').submit();
              </script>";
        
        //As a precaution, terminate the remainder of the processes on this page
        die();
    }
}

/* redirectToLoginPageFromInvoice
    purpose: Redirects to the login page if there is nobody logged in
    input(s): n/a
    output: n/a (but will actively redirect if there is nobody logged in)
    implemented: invoice page
*/
function redirectToLoginFromInvoice() {
    //Checks to see if the username index is filled (aka: somebody is logged in)
    if(!isset($_POST['username']) || ($_POST['username'] == "")) {
        //Redirect; pass empty strings as user credentials
        echo "<form id='autoSubmit' action='../login.php' method='POST'>
                <input type='hidden' name='username' value=''>
                <input type='hidden' name='password' value=''>
              </form>
              <script type='text/javascript'>
                  document.getElementById('autoSubmit').submit();
              </script>";
        
        //As a precaution, terminate the remainder of the processes on this page
        die();
    }
}

/* redirectToStoreFromRegistration
    purpose: Redirects to the index/home/store page in the event of successful account creation
    input(s): n/a
    output: n/a (but will actively redirect if valid user information is entered)
    implemented: Registration page
*/
function redirectToStoreFromRegistration() {
    //Optimistically assume login is successful (initialize loginSuccessful to true)
    $registrationSuccessful = true;
    
    //Verifies that provided username is valid for registry
    if(!validUsernameForRegistration()) {
        $registrationSuccessful = false;
    }
    
    //Verifies that provided password is valid for registry
    if(!validPasswordForRegistration()) {
        $registrationSuccessful = false;
    }
    
    //Verifies that provided email is valid for registry
    if(!validEmailForRegistration()) {
        $registrationSuccessful = false;
    }
    
    //Checks to see if the registration succeeded
    if($registrationSuccessful) {
        //Append the new username, password, and email files to the database
        appendDataFromRegistration();
        
        //Redirect; pass user credentials
        $user = $_POST["username"];
        $pass = $_POST["password"];
        $email = $_POST["email"];
        echo "<form id='autoSubmit' action='index.php' method='POST'>
                <input type='hidden' name='username' value=$user>
                <input type='hidden' name='password' value=$pass>
                <input type='hidden' name='email' value=$email>
                <input type='hidden' name='registrationSuccessful' value='true'>
              </form>
              <script type='text/javascript'>
                  document.getElementById('autoSubmit').submit();
              </script>";
        
        //As a precaution, terminate the remainder of the processes on this page
        die();
    }
}

/* redirectToInvoiceFromStore
    purpose: Redirects to the the invoice in the event somebody (logged user) made a purchase
    input(s): n/a
    output: n/a (but will actively redirect if valid purchase was made)
    implemented: Store page
*/
function redirectToInvoiceFromStore() {
    //Check that an attempt was made at purchase
    if(isset($_POST["purchaseAttempted"])) {
        //Optimistically assume purchase will be successful (initialize purchaseSuccessful to true)
        $purchaseSuccessful = true;
        
        //Initialize summation counter
        $sum = 0;
        $stopCount = false;
        
        //Loop through all possible products
        for($i=0; $i<countItemsInDatabase('resources/products.dat'); $i++) {
            //Verify that the requested amount of products is numeric
            if (!ctype_digit($_POST["productOrder"][$i])) {
                //Add error message to errors array
                $GLOBALS['errors'][$i]['digits'] = "This input must consist solely of whole numeric quantity";

                //Reset purchaseSuccessful to false
                $purchaseSuccessful = false;
                $stopCount = true;
            }
            
            //Stop the counter if there was an invalid entry
            if(!$stopCount) {
                $sum += ((int) $_POST["productOrder"][$i]);
            }
            else {
                $sum = null;
            }
        }
        //Verify that the counter is still equal to 0
        if ($sum == 0) {
            //Add error message to errors array
            $GLOBALS['errors']['overall']['digits'] = "A purchase of at least one item is required to continue";

            //Reset purchaseSuccessful to false
            $purchaseSuccessful = false;
        }
        
        //Checks to see if the registration succeeded
        if ($purchaseSuccessful) {
            
            $user = $_POST["username"];
            
            //Assemble array for submission
            for($i=0; $i<countItemsInDatabase('resources/products.dat'); $i++) {
                $productOrder[$i] = $_POST['productOrder'][$i];
            }
            
            //Redirect; pass username and purchase information
            echo "<form id='autoSubmit' action='resources/invoice.php' method='POST'>
                <input type='hidden' name='username' value=$user>
                <input type='hidden' name='purchaseAttempted' value='yes'>
                <input type='hidden' name='productOrder0' value=$productOrder[0]>
                <input type='hidden' name='productOrder1' value=$productOrder[1]>
                <input type='hidden' name='productOrder2' value=$productOrder[2]>
                <input type='hidden' name='productOrder3' value=$productOrder[3]>
                <input type='hidden' name='productOrder4' value=$productOrder[4]>
              </form>
              <script type='text/javascript'>
                  document.getElementById('autoSubmit').submit();
              </script>";
            
            //As a precaution, terminate the remainder of the processes on this page
            die();
        }
    }
}

/* validUsernameForRegistration
    purpose: Validates the username after a registration attempt
    input(s): n/a
    output: (boolean) $validated =>
            (also actively assigns error messages for respective problems)
    implemented: redirectToStoreFromRegistration function
*/
function validUsernameForRegistration() {
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
        if((strlen($_POST['username'])) < 4 || (strlen($_POST['username']) > 11)) {
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
        if (usernameExists($_POST['username'])) {
            //Add error message to errors array (already in use)
            $GLOBALS['errors']['username']['repeated'] = "This username is already currently in use.";

            //Reset validated to false
            $validated = false;
        }
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
function validPasswordForRegistration() {
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

/* validEmailForRegistration
    purpose: Validates the email after a registration attempt
    input(s): n/a
    output: (boolean) $validated =>
            (also actively assigns error messages for respective problems)
    implemented: redirectToStoreFromRegistration function
*/
function validEmailForRegistration() {
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

/* redirectToStoreFromLogin
    purpose: Redirects to the index/home/store page in the event of successful log-in
    input(s): n/a
    output: n/a (but will actively redirect if valid user information is entered)
    implemented: Login page
*/
function redirectToStoreFromLogin() {
    //Optimistically assume login is successful (initialize loginSuccessful to true)
    $loginSuccessful = true;
    
    //Verifies that provided username is valid for login
    if(!validUsernameForLogin()) {
        $loginSuccessful = false;
    }
    
    //Verifies that provided password is valid for login
    if(!validPasswordForLogin()) {
        $loginSuccessful = false;
    }
    
    //Checks to see if the login succeeded
    if($loginSuccessful) {
        $value = $_POST['username'];
        //Redirect; pass username data
        echo "<form id='autoSubmit' action='index.php' method='POST'>
                <input type='hidden' name='loginSuccessful' value='true'>
                <input type='hidden' name='username' value='$value'>
              </form>
              <script type='text/javascript'>
                  document.getElementById('autoSubmit').submit();
              </script>";
        
        //As a precaution, terminate the remainder of the processes on this page
        die();
    }
}

/* validUsernameForLogin
    purpose: Validates the username after a login attempt
    input(s): n/a
    output: (boolean) $validated =>
            (also actively assigns error messages for respective problems)
    implemented: redirectToStoreFromLogin function
*/
function validUsernameForLogin() {
    //Initialize validated to true
    $validated = true;
    
    //Verify that provided username is not just an empty string
    if ($_POST['username']==="") {
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
    implemented: redirectToStoreFromLogin function
*/
function validPasswordForLogin() {
    //Initialize validated to true
    $validated = true;
    
    //Determine the correct password
    $correct = "";
    $users = populateArrayFromDatabase('resources/users.dat');
    for($i=0; $i<sizeOf($users); $i++) {
        if(trim($users[$i][0]) == $_POST['username']) {
            $correct = $users[$i][1];
        }
    }

    //Verify that provided username is not just an empty string
    if ($_POST['password']==="") {
        //Add error message to errors array (empty string)
        $GLOBALS['errors']['password']['empty'] = "No password was provided.";
        
        //Reset validated to false
        $validated = false;
    }
    //Only display further error(s) if a password was attempted (not an empty string)
    else {
        //Verify that provided password corresponds to encrypted password on file
        if(!password_verify($_POST['password'],$correct)) {
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
    implemented: headers, validUsernameForRegistration and validUsernameForLogin functions
*/
function usernameExists($provided) {
    //Initialize exists to false
    $exists = false;
    
    //Initialize a file pointer
    $fp = fopen("resources/users.dat","r");
    
    //Convert file to string
    $contents = stream_get_contents($fp);

    //Check if there are any words in contents that match the provided username
    if(preg_match("/\b$provided\b/i",$contents)) {
        $exists = true;
        if($provided==="") {
            $exists = false;
        }
    }
    
    //Close the file pointer
    fclose($fp);
    
    //Return whether or not the value is currently in the database
    return $exists;
}

/* appendDataFromRegistration
    purpose: Appends the provided data to the users database (for future logins)
    input(s): n/a
    output: n/a (but actively appends to the users database file)
    implemented: redirectToStoreFromRegistration function
*/
function appendDataFromRegistration() {
    $fp = fopen("resources/users.dat","a") or die("unable to open the requested file");
    fwrite($fp,"\n".strtolower($_POST['username']).','.encryptForCSV($_POST['password_1']).','.$_POST['email_1'].';');
    fclose($fp);
}

/* populateArrayFromDatabase
    purpose: populates the products array from the products database
    input(s): (string) $filename => the name of the database file
    output: (array) $array => resulting array from database file
    implemented: Index/Home/Store page
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
            $array[$i][$j] = $arrayItemData[$j];
        }
    }

    fclose($fp);
    
    return $array;
}

/* countItemsInDatabase
    purpose: populates the products array from the products database
    input(s): (string) $filename => the name of the database file
    output: (integer) $counter => the number of products in the database
    implemented: populateProductsArrayFromDatabase function
*/
function countItemsInDatabase($filename) {
    $fp = fopen($filename,"r") or die("Unable to open $filename file!");
    $fs = filesize($filename);

    $counter = 0;
    $contents = fread($fp, $fs);

    for($i=0; $i<$fs; $i++) {
        if ($contents[$i] == ";") {
            $counter++;
        }
    }

    fclose($fp);

    return $counter;
}
   
/* markupPrice
   purpose: marks up the value to selling price
   input(s): (string) "val" => string to be casted to float, multipied to markup
   output: (float) *marked up price (25% increase)*
   implemented: 
*/
function markupPrice($val) {
    //Increase initial value by 25%
    return (float) ($val*1.25);
}   

/* encryptForCSV
   purpose: marks up the value to selling price
   input(s): (string) "val" => string to be casted to float, multipied to markup
   output: (string) $retVal => encrypted string
   implemented: 
*/
function encryptForCSV($val) {
    //Initialize retVal to the encrypted version of the provided password within an array
    $retVal = str_split(password_hash($val,PASSWORD_DEFAULT),1);
    
    //Modify retVal to ensure it doesn't contain any commas or semi-colons
    for($i=0; $i<sizeOf($retVal); $i++) {
        if($retVal[$i] == ',') {
            $retVal[$i] == 'C';
        }
        else if($retVal[$i] == ';') {
            $retVal[$i] == 's';
        }
    }
    
    //Convert retVal back to a single string
    $retVal = implode($retVal);
    
    //Return the modified encyrpted string
    return $retVal;
}

/* pluralize
  purpose: pluralizes words based on last letter
  input(s): (string) "name" => string to be pluralized
  output: (string) $retVal => pluralized name
  implemented: invoice page
 */
function pluralize($name) {
    $retVal = $name;
    switch (substr($name, -1)) {
        case 'z':
            $retVal = $name.'es';
            break;
        case 'y':
            $retVal = substr_replace($name,'',-1).'ies';
            break;
        default:
            $retVal = $name.'s';
    }
    return $retVal;
}

/* show
  purpose: personal diagnostic tool so as to not have to type the annoying
           underscores as well as have it in a nice, more readable format
  input(s): n/a
  output: the var_dump() of an element in a viewable format (set to $_POST by default)
  implemented: wherever one would use a var_dump() function command
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