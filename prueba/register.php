<?php

session_start();

if (isset($_SESSION['account_loggedin'])) {
    header('location: successful.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <title>Register</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <?php
            require('./util/connection.php');
        ?>
    </head>
    <body>
        <?php
            function debug(string $input) : string {
                $output = htmlspecialchars($input);
                $output = trim($output);
                $output = stripslashes($output);
                $output = preg_replace('!\s+!', ' ', $output);
                return $output;
            }
        ?>
        <div class="login">
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_user = $_POST["username"];
            $tmp_new_password = debug($_POST["new_password"]);
            $tmp_new_password_2 = debug($_POST["new_password_2"]);
            $tmp_email = debug($_POST["email"]);
            
            $sql = "SELECT * FROM users WHERE username = '$tmp_user'";
                $result = $_conexion -> query($sql);

            if ($tmp_user == '') {
                $err_user = "User is required";
            }
            else {
                if($result -> num_rows != 0) {
                    $err_user = "The user $tmp_user already exists.";
                } 
                else {
                    if (strlen($tmp_user) < 3 || strlen($tmp_user) > 15) {
                        $err_user = "The user must have between 3 and 15 characters.";
                    }
                    else {
                        $pattern = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜñÑ ]{3,15}$/";
                        if (!preg_match($pattern, $tmp_user)) {
                            $err_user = "The user can only have letters and numbers.";
                        }
                        else {
                            $user = $tmp_user;
                        }
                    }
                }
            }

            if ($tmp_new_password == '') {
                $err_new_password = "This field is required.";
            }
            elseif ($tmp_new_password_2 == '') {
                $err_new_password_2 = "This field is required.";
            }
            else {
                if (strlen($tmp_new_password) < 8 || strlen($tmp_new_password) > 15) {
                    $err_new_password = "The password must be between 8 and 15 characters long.";
                }
                elseif (strlen($tmp_new_password_2) < 8 || strlen($tmp_new_password_2) > 15) {
                    $err_new_password_2 = "The password must be between 8 and 15 characters long.";
                }
                else {
                    $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,15}$/";
                    if (!preg_match($patron,$tmp_new_password)) {
                        $err_new_password = "The password must contain at least one lowercase letter, one uppercase letter, and one number.";
                    }
                    elseif (!preg_match($patron,$tmp_new_password_2)) {
                        $err_new_password_2 = "The password must contain at least one lowercase letter, one uppercase letter, and one number.";
                    }
                    else {
                        if (strcmp($tmp_new_password,$tmp_new_password_2) != 0) {
                            $err_new_password = "Passwords don't match";
                            $err_new_password_2 = "Passwords don't match";
                        }
                        else {
                            $correct_password = $tmp_new_password_2;
                            $encrypted_password = password_hash($correct_password, PASSWORD_DEFAULT);
                        }
                    }
                }
            }

            if ($tmp_email == '') {
                $err_email = "Email address is required.";
            }
            else {
                if (!filter_var($tmp_email, FILTER_VALIDATE_EMAIL)) {
                    $err_email = "The email format is invalid.";
                }
                else {
                    $sql_email = "SELECT * FROM users WHERE email = '$tmp_email'";
                    $result_email = $_connection -> query($sql_email);
                    if ($result_email->num_rows != 0) {
                        $err_email = "The email is already registered.";
                    } else {
                        $email = $tmp_email;
                    }
                }
            }
        }
        ?>
            <h1>Register</h1>

            <form  method="post" class="form login-form">

                <label class="form-label" for="username">Username</label>
                <div class="form-group">
                    <input class="form-input" type="text" name="username" placeholder="Username" id="username" required>
                </div>

                <label class="form-label" for="password">Password</label>
                <div class="form-group ">
                    <input class="form-input" type="password" name="password" placeholder="Password" id="password" required>
                </div>

                <label class="form-label" for="password_confirmation">Password confirmation</label>
                <div class="form-group ">
                    <input class="form-input" type="password" name="password_confirmation" placeholder="Confirm your password" id="password_confirmation" required>
                </div>

                <label class="form-label" for="email">Email</label>
                <div class="form-group mar-bot-5">
                    <input class="form-input" type="email" name="email" placeholder="Email Address" id="email" required>
                </div>

                <button class="btn blue" type="submit">Register</button>

                <p class="register-link">Have an account? <a href="index.php" class="form-link">Login</a></p>

            </form>

        </div>
    </body>
</html>