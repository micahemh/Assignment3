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
        for ($i = 1; $i < sizeOf($array); $i++) {
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
                                <small><small><small>How many?</small></small></small>
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