<?php
//Display error message when payment request fails
$html = file_get_contents("templates/paypalfail.html");
//Redirect to home afte delay
header("Refresh: 5; URL=index.php");
echo $html;

?>