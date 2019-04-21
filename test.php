<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="foo" value="false">
    <input type="checkbox" name="foo" value="true" checked> 
    <input type="submit" value="submit" name="submit">
</form>

<?php
    require("resources/functions.php");
    
    //Initialize products array
    $users = convertToUsersArray(populateArrayFromDatabase('resources/users.dat'));
    
    if(isset($_COOKIE['username'])) {
        session_save_path('resources/sessions/.');
        session_id($_COOKIE['username']);
        session_start();
    }
    
    $ini_set("SMTP","mail.hawaii.edu");
    $origin = "micahemh@hawaii.edu";
    $destination = null;
    for($i=0; $i<sizeOf($users); $i++) {
        if($_COOKIE['username'] == $users[$i]['username']) {
            $destination = $users[$i]['email'];
        }
    }
    
?>
<?php
    //if(isset($_POST['foo'])) {
    //    var_dump($_POST['foo']);
    //}
    //$a = array();
    //$a[] = true;
    //print array_sum($a);
    //require('resources/functions.php');
    //setcookie('username','asdfasdf');
    //var_dump($_COOKIE['username']);
    //modifyAccountInformation("asdfasdf",'asdfasdf','asdfasdf@asdf.gh');
    //setcookie('username','',time()-1234567890);
    
    /* redirectToInvoiceFromCart
      purpose: 
      input(s): 
      output: 
     
    function redirectToInvoiceFromCart() {
        if(isset($_POST['checked'])) {
            $counter = array();
            $continue = true;
            for($i=0; $i<sizeOf($_POST['checked']); $i++) {
                if((isset($_POST['checked'][$i]) && $_POST['checked'][$i] == "true")) {
                    if(isset($_POST['productOrder'][$i])) {
                        $_SESSION['cart_quantities'][$i] = $_POST['productOrder'][$i];
                    }
                }
                if(array_key_exists("false",$_POST['checked'][$i])) {
                    $continue = false;
                    continue;
                }
            }
            //If the cart has no revisions, redirect to the invoice
            if(sizeOf($counter)==sizeOf($_POST['checked'])) {
                header("location:resources/invoice.php");
                die();
            }
        }
    }*/
?>
