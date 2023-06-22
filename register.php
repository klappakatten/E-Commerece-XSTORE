<?php

session_start();

$html = file_get_contents("templates/register.html");
$problem = false;
$errormessage = "";

//Handle register form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connectdb.php';
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $errormessage .= 'Please enter a valid password! ';
        $problem = true;
    }
    if (!isset($_POST['repeatpassword'])) {
        $errormessage .= 'Please enter a repeat password! ';
        $problem = true;
    } else if ($password != $_POST['repeatpassword']) {
        $errormessage .= 'Repeat password needs to match password! ';
        $problem = true;
    }
    if (isset($_POST['email'])) {
        $email = trim(htmlspecialchars(strip_tags($_POST['email'])));
        $result = getUserByEmail($email);
        if ($result->num_rows > 0) {
            $errormessage .= 'This email is already registered, please choose another emailadress! ';
            $problem = true;
        }
    } else {
        $errormessage .= 'Please enter email address! ';
        $problem = true;
    }
    if (!isset($_POST['repeatemail'])) {
        $errormessage .= 'Please enter a repeat email! ';
    } else if ($email != trim(htmlspecialchars(strip_tags($_POST['repeatemail'])))) {
        $errormessage .= 'Email didnt match! ';
        $problem = true;
    }

    if (isset($_POST['fname'])) {
        $fname = trim(htmlspecialchars(strip_tags($_POST['fname'])));
    } else {
        $errormessage .= 'Please enter first name! ';
        $problem = true;
    }
    if (isset($_POST['lname'])) {
        $lname = trim(htmlspecialchars(strip_tags($_POST['lname'])));
    } else {
        $errormessage .= 'Please enter Last name! ';
        $problem = true;
    }
    if (isset($_POST['adress'])) {
        $adress = trim(htmlspecialchars(strip_tags($_POST['adress'])));
    } else {
        $errormessage .= 'Please enter address! ';
        $problem = true;
    }
    if (isset($_POST['postcode'])) {
        $postcode = trim(htmlspecialchars(strip_tags($_POST['postcode'])));
    } else {
        $errormessage .= 'Please enter postcode! ';
        $problem = true;
    }
    if (isset($_POST['country'])) {
        $country = trim(htmlspecialchars(strip_tags($_POST['country'])));
    } else {
        $errormessage .= 'Please enter country! ';
        $problem = true;
    }
    if (isset($_POST['city'])) {
        $city = trim(htmlspecialchars(strip_tags($_POST['city'])));
    } else {
        $errormessage .= 'Please enter city! ';
        $problem = true;
    }
    if (isset($_POST['phone'])) {
        $phone = trim(htmlspecialchars(strip_tags($_POST['phone'])));
    } else {
        $errormessage .= 'Please enter phone number! ';
        $problem = true;
    }
    if ($problem) {
        echo str_replace("<!--ERROR-->", $errormessage, $html);
        die();
    } else {
        $admin = 0;

        //Hash password to store in database
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        //prepared SQL statement to add new user to the database
        try {
            addUserToDB($email, $hashedPassword, $fname, $lname, $adress, $postcode, $country, $city, $phone, $admin);
        } catch (mysqli_sql_exception $e) {
            echo str_replace("<!--ERROR-->", $e->getMessage(), $html);
            die();
        }

        //Send Welcome Email
        if (mail($email, "Welcome to XSTORE!", "Welcome! We are glad that you are here, Your account has successfully been created!", 'kleptokatten@gmail.com')) {
            //SUCCESS
        } else {
            //FAILURE
        }

        //Login user
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        header('Location: index.php');
    }

} else {
    echo $html;
}


?>