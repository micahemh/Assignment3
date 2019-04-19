<!--
Lauren Lee and Micah Higashi
ITM 352
18 April 2019
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
    
    /* displayProducts
        purpose: displays the products according to whatever page this function is called on
        input(s): (string) $type => the type of product: airFryer, slowCooker, or pressureCooker
        output: n/a
    */
    function displayProducts($type) {
        //Initialize the products array
        $products = convertToProductsArray(populateArrayFromDatabase('resources/products.dat'));
        
        //Product Display        
        for($i=1; $i<sizeOf($products); $i++) {
            if($products[$i]['type'] == trim($type)) {
                printf('<div class="backgroundPlatform product">
                            <div style="grid-column: 1;">
                                <img src="%s" alt="%s">
                            </div>
                            <div style="grid-column: 2; text-align: left; ">
                                <h2>%s</h2>
                                <h6>$%.2f</b></h6>
                                %s
                            </div>
                            <div style="grid-column: 3; text-align: center;">
                                <h6>How many?</h6>
                                <input type="number" min="0" pattern="\d+" value="0" style="width: 6em;" name="productOrder[%d]">
                            </div>
                        </div>
                        <br>'
                    ,$products[$i]['image']//path to the image of the product
                    ,$products[$i]['name']//Alternative text if image doesn't display
                    ,$products[$i]['name']//Title of product
                    ,$products[$i]['price']//Price of product
                    ,$products[$i]['description']//Short description of the product
                    ,0
                );
            }
        }
    }

    /* redirectFromLogin
      purpose: Redirects to another page* after a successful log-in (either the last of my pages that the user was on OR if they have no "history", to the index)
      input(s): (string) $destination => the last page that the user was on (if empty string, there is no history and destination is automatically set to index.php)
      output: n/a (but will actively redirect if valid user information is submitted)
     */

    function redirectFromLogin($destination) {
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
            //Redirect to the previous location or the index
            header('location:index.php');

            //As a precaution, terminate the remainder of the processes on this page
            die();
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
      implemented: redirectToStoreFromLogin function
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
      implemented: redirectToStoreFromLogin function
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
        if ($_POST['password'] === "") {
            //Add error message to errors array (empty string)
            $GLOBALS['errors']['password']['empty'] = "No password was provided.";

            //Reset validated to false
            $validated = false;
        }
        //Only display further error(s) if a password was attempted (not an empty string)
        else {
            //Verify that provided password corresponds to encrypted password on file
            if (!password_verify($_POST['password'], $correct)) {
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

    /* setUserCookie
      purpose: sets the username cookie
      input(s): n/a
      output: sets the username cookie
     */
    function setUserCookie() {
        if(isset($_COOKIE['username'])) {
            setcookie('username',$_COOKIE['username']);
        }
        else if(isset($_POST['username']) && (validLogin() || validLogin())) {
            setcookie('username',$_POST['username']);
        }
    }

    /* logout
      purpose: logs user off and deletes the username cookie
      input(s): n/a
      output: deletes the username cookie
     */
    function logout() {
        if(isset($_POST['logout'])) {
            setcookie('username',$_POST['username'],time()-808);
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