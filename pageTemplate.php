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
<html>
    <head>
        <meta charset="UTF-8">
        <title>WENEEDTOCHOOSEANAME | Home</title>

        <!--Preset data provided by Netbeans-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--Link for tab icon-->
        <link rel="icon" href="../resources/../media/tabIcon.png">
        
        <!--Link for stylesheet-->
        <link rel="stylesheet" type="text/css" href="resources/style.css">
    </head>
    <body>
        <div>
            <!--Display the Header (via external php file)-->
            <?php require("resources/header.php"); show();?>

            <!--Collapsible Paragraph Content-->
            <button class="collapsible" type="button">About our products...</button>
            <div class="collapsibleContent">
                Paragraph 1
                <br><br>
                Paragraph 2
                <br><br>
                <b>Note</b>: here is the last paragraph.
                <br><br>
            </div>
            
            <main>
                This is the where the main content for this page goes.
                <?php
                    displayProducts();
                ?>
            </main>
            
            <!--Display the Footer (via external htm file)-->
            <?php require("resources/footer.htm"); ?>
        </div>
    </body>
</html>