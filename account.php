<?php
//Account page - User can update password & contactinformation

session_start();

//Check if user is logged in
if (isset($_SESSION['loggedin']) && isset($_SESSION['email']) && $_SESSION['loggedin'] == true) {

    include("connectdb.php");

    $html = file_get_contents("templates/account.html");

    $email = $_SESSION['email'];

    //Prepared statement to get user from the database
    $result = getUserByEmail($email);

    //store variables
    if ($obj = $result->fetch_object()) {
        $email = $obj->email;
        $hashedPassword = $obj->password;
        $fname = $obj->fname;
        $lname = $obj->lname;
        $adress = $obj->adress;
        $postcode = $obj->postcode;
        $country = $obj->country;
        $city = $obj->city;
        $phone = $obj->phone;
        $admin = $obj->admin;
    }

    //Display account information
    if (!$admin) {
        $html = str_replace("FNAME", $fname, $html);
        $html = str_replace("LNAME", $lname, $html);
        $html = str_replace("ADRESS", $adress, $html);
        $html = str_replace("CITY", $city, $html);
        $html = str_replace("18432", $postcode, $html);
        $html = str_replace("EMAIL", $email, $html);
        $html = str_replace("0739758810", $phone, $html);


        //Valdiate and get the form input data
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['password']) && $_POST['password'] != "") {
                if (!isset($_POST['repeatpassword'])) {
                    $errormessage .= 'Please enter a repeat password';
                    $problem = true;
                } else if ($_POST['password'] == $_POST['repeatpassword'] && $password) {
                    $newpassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                } else {
                    $errormessage .= 'Password needs to match';
                    $problem = true;
                }
            }
            if (isset($_POST['adress']) && $_POST['adress'] != $adress) {
                $newAdress = trim(htmlspecialchars(strip_tags($_POST['adress'])));
            }

            if (isset($_POST['postcode']) && $_POST['postcode'] != $postcode) {
                $newPostcode = trim(htmlspecialchars(strip_tags($_POST['postcode'])));
            }

            if (isset($_POST['city']) && $_POST['city'] != $city) {
                $newCity = trim(htmlspecialchars(strip_tags($_POST['city'])));
            }

            if (isset($_POST['country']) && $_POST['country'] != $country) {
                $newCountry = trim(htmlspecialchars(strip_tags($_POST['country'])));
            }

            //Update account information in the database if new data is inputted
            $changes = "";
            if (isset($newpassword)) {
                updateUserPassword($newpassword, $email);
                $changes .= "Password updated successfully! ";
            }
            if (isset($newAdress)) {
                updateUserAdress($newAdress, $email);
                $changes .= "Address updated successfully! ";
            }
            if (isset($newPostcode)) {
                updatePostcode($newPostcode, $email);
                $changes .= "Postcode updated successfully! ";
            }
            if (isset($newCity)) {
                updateUserCity($newCity, $email);
                $changes .= "City updated successfully! ";
            }
            if (isset($newCountry)) {
                updateUserCountry($newCountry, $email);
                $changes .= "Country updated successfully! ";
            }
            if ($changes != "") {
                $html = str_replace("<!--SUCCESS-->", $changes, $html);
            }


        }
        //Display error message if there was an error
        if (isset($problem) && $problem) {
            $html = str_replace("<!--ERROR-->", $errormessage, $html);
        }

        echo $html;

    } else { //Send admin to adminpage
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit();
    }

} else { //Prevent non users to access this page
    header('HTTP/1.0 403 Forbidden');
    echo str_replace("<!--ERROR-->", "Access denied! You do not have access to this page, please login first", file_get_contents("templates/fail.html"));
}
?>