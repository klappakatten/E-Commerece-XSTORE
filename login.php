<?php
//Login page with validation

session_start();

include("connectdb.php");

$html = file_get_contents("templates/login.html");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $problem = false;
    $errormessage = "";

    //Handle the login form submission
    if (!empty($_POST['email'])) {
        $email = trim(strip_tags($_POST['email']));
    } else {
        $errormessage .= 'Please enter a email! ';
        $problem = true;
    }
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $errormessage .= 'Please enter a password! ';
        $problem = true;
    }

    //Verify login form submission
    if ($problem === false) {
        $result = getUserByEmail($email); //function dbconnect.php
        if ($result->num_rows > 0) {
            try {
                $obj = $result->fetch_object();
                $hashedpassword = $obj->password;

                //Check if password matches password stored in database
                if (password_verify($password, $hashedpassword)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = $email;
                    header("Location: index.php");
                    die();
                } else {
                    $errormessage .= 'Wrong password! ';
                }
            } catch (mysqli_sql_exception $e) {
                $errormessage .= $e->getMessage();
            }
        } else {
            $errormessage .= 'Please enter a valid email address! ';
        }
    }
}
if ($errormessage != "") {
    $html = str_replace("<!--ERROR-->", $errormessage, $html);
}
echo $html;

?>