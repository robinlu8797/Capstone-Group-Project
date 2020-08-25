<?php

require_once "./includes/open_conn.inc";
$username = "";
$password = "";
$confirm_password = "";
$role = "";
$username_err = "";
$password_err = "";
$confirm_password_err = "";
$role_err = "";
$user_lastName = "";
$user_lastName_err = "";
$user_firstName = "";
$user_firstName_err = "";
$user_address = "";
$user_address_err = "";
$user_city = "";
$user_city_err = "";
$user_state = "";
$user_state_err = "";
$user_zip = "";
$user_zip_err = "";
$user_email = "";
$user_email_err = "";
$user_id = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    if (empty(trim($_POST["role"]))) {
        $role_err = "Please enter a role.";
    } else {
        $role = trim($_POST["role"]);
//        if(empty($role_err) && ($role != "HR") || ($role != "Manager") || ($role != "Trainee")){
//            $role_err = "Please enter a role of HR, Manager, or Trainee";
//        }
    }
    
    //First Name:
    if (empty(trim($_POST["user_firstName"]))) {
        $user_firstName_err = "Please enter a First Name.";
    } elseif (strlen(trim($_POST["user_firstName"])) <= 0) {
        $user_firstName_err = "Please Enter First Name";
    } else {
        $user_firstName = trim($_POST["user_firstName"]);
    }
    //Last Name:
    if (empty(trim($_POST["user_lastName"]))) {
        $user_lastName_err = "Please enter a Last Name.";
    } elseif (strlen(trim($_POST["user_lastName"])) <= 0) {
        $user_lastName_err = "Please Enter Last Name";
    } else {
        $user_lastName = trim($_POST["user_lastName"]);
    }
    //Address:
    if (empty(trim($_POST["user_address"]))) {
        $user_address_err = "Please enter Address.";
    } elseif (strlen(trim($_POST["user_address"])) <= 0) {
        $user_address_err = "Please Enter Address";
    } else {
        $user_address = trim($_POST["user_address"]);
    }
    //City:
    if (empty(trim($_POST["user_city"]))) {
        $user_city_err = "Please enter City.";
    } elseif (strlen(trim($_POST["user_city"])) <= 0) {
        $user_city_err = "Please Enter City";
    } else {
        $user_city = trim($_POST["user_city"]);
    }
    //State:
    if (empty(trim($_POST["user_state"]))) {
        $user_state_err = "Please enter State.";
    } elseif (strlen(trim($_POST["user_state"])) < 2 || strlen(trim($_POST["user_state"])) > 2) {
        $user_state_err = 'Please Enter State as Two Letters';
    } else {
        $user_state = trim($_POST["user_state"]);
    }
    //Zip:
    if (empty(trim($_POST["user_zip"]))) {
        $user_zip_err = "Please enter Zip";
    } elseif (strlen(trim($_POST["user_zip"])) < 5 || strlen(trim($_POST["user_zip"])) > 5) {
        $user_zip_err = 'Please Enter Zip as Five numbers';
    } else {
        $user_zip = trim($_POST["user_zip"]);
        
    }
    //Email:
    if (empty(trim($_POST["user_email"]))) {
        $user_email_err = "Please enter Email";
    } elseif (strlen(trim($_POST["user_email"])) <= 0) {
        $user_email_err = 'Please Enter Email';
    } else {
        $user_email = trim($_POST["user_email"]);
    }
    
    //this function is to adding users info into user_info table
    function addUsers_Info($link, $user_id, $username, $user_lastName, $user_firstName, $user_address, $user_city, $user_zip, $user_state, $user_email)
    {
        $select_query = 'SELECT id FROM users WHERE username = "' . $username . '"';
        if ($res = mysqli_query($link, $select_query)) {
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_array($res)) {
                    $user_id = $row['id'];
                }
                mysqli_free_result($res);
            } else {
                //echo "No matching records are found.";
            }
        } else {
            echo "ERROR: Could not able to execute $sql. "
                . mysqli_error($link);
            return false;
        };
        $insert_user_info_query = "INSERT INTO user_info(user_id, lastName, firstName, address, city, state, zip, email) VALUES(" .
            $user_id . "," . '"' . $user_lastName . '"' . "," . '"' . $user_firstName . '"' . "," . '"' .
            $user_address . '"' . "," . '"' . $user_city . '"' . "," . '"' .
            $user_state . '"' . "," . '"' . $user_zip . '"' . "," . '"' . $user_email . '"' . ")";
        // echo $insert_user_info_query;
        if (mysqli_query($link, $insert_user_info_query)) {
            //echo "Users Information added successfully";
        } else {
            echo "ERROR: Could not able to execute $sql. "
                . mysqli_error($link);
            return false;
            
        };
        return true;
    }//end of addUsers_Info function
    //this function is for checking if all input data is set
    //function returns TRUE if all inputs are OK, and FALSE other wisely .
    function checkInputData($user_id, $username, $user_lastName, $user_firstName, $user_address, $user_city, $user_zip, $user_state, $user_email)
    {
        if (!(strlen(trim($user_zip)) == 5)) {
            return false;
        } elseif (!(strlen(trim($username)) > 0)) {
            return false;
        } elseif (!(strlen(trim($user_id)) > 0)) {
            return false;
        } elseif (!(strlen(trim($user_lastName)) > 0)) {
            return false;
        } elseif (!(strlen(trim($user_firstName)) > 0)) {
            return false;
        } elseif (!(strlen(trim($user_address)) > 0)) {
            return false;
        } elseif (!(strlen(trim($user_city)) > 0)) {
            return false;
        } elseif (!(strlen(trim($user_state)) == 2)) {
            return false;
        } elseif (!(strlen(trim($user_email)) > 0)) {
            return false;
        };
        
        return true;
    }
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err)) {
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_role);
            $param_username = $username;
            $param_role = $role;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            
            //checking input and executing mysqli query
            if (checkInputData($user_id, $username, $user_lastName, $user_firstName, $user_address, $user_city, $user_zip, $user_state, $user_email) && mysqli_stmt_execute($stmt)) {
                //calling function to add users info into user_info table
                if (addUsers_Info($link, $user_id, $username, $user_lastName, $user_firstName, $user_address, $user_city, $user_zip, $user_state, $user_email)) { // added by Andrey
                    echo "Successfully registered user.";
                } else {
                    echo "Something went wrong. Please try again later.";
                }
                
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Something's wrong with the query: " . mysqli_error($link);
        }
    }
    
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="./design/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
            <label>Role</label>
            <select class="form-control" name="role" size="1">
                <option value="HR">HR</option>
                <option value="Manager">Manager</option>
                <option value="Trainee">Trainee</option>
            </select>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control"
                       value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($user_firstName_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="user_firstName" class="form-control" value="<?php echo $user_firstName; ?>">
                <span class="help-block"><?php echo $user_firstName_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($user_lastName_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="user_lastName" class="form-control" value="<?php echo $user_lastName; ?>">
                <span class="help-block"><?php echo $user_lastName_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($user_address_err)) ? 'has-error' : ''; ?>">
                <label>Address</label>
                <input type="text" name="user_address" class="form-control" value="<?php echo $user_address; ?>">
                <span class="help-block"><?php echo $user_address_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($user_city_err)) ? 'has-error' : ''; ?>">
                <label>City</label>
                <input type="text" name="user_city" class="form-control" value="<?php echo $user_city; ?>">
                <span class="help-block"><?php echo $user_city_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($user_state_err)) ? 'has-error' : ''; ?>">
                <label>State as Two Letters</label>
                <input type="text" name="user_state" class="form-control" value="<?php echo $user_state; ?>">
                <span class="help-block"><?php echo $user_state_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($user_zip_err)) ? 'has-error' : ''; ?>">
                <label>Zip</label>
                <input type="text" name="user_zip" class="form-control" value="<?php echo $user_zip; ?>">
                <span class="help-block"><?php echo $user_zip_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($user_email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="user_email" class="form-control" value="<?php echo $user_email; ?>">
                <span class="help-block"><?php echo $user_email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="index.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>