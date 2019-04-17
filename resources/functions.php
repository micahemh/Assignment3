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
                if($arrayItemData[0] == 'airFryer') {
                    $array[$i][$j] = $arrayItemData[$j];
                }
                else if($arrayItemData[0] == 'crockPot') {
                    $array[$i][$j] = $arrayItemData[$j];
                }
                else {
                    $array[$i][$j] = $arrayItemData[$j];
                }
            }
        }

        fclose($fp);

        return $array;
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