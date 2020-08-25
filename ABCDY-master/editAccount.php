<?php




session_start();//session is starting
//checking if loggedin session is set, and role is Manager, if not rederecting to main page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "HR") {
    header("location: index.php");
    exit;
}
$last_name = "";
$first_name = "";
$address = "";
$city = "";
$state = "";
$zip = "";
$email = "";
$role = "";
$user_name = "";

$user_firstName_err ="";
$user_lastName_err ="";
$user_address_err ="";
$user_city_err ="";
$user_zip_err ="";
$user_state_err ="";
$user_email_err ="";


if ($_GET) {
    
    $u_id = $_GET['id'];
    include_once("./includes/open_conn.inc");
    
    $select_query = "SELECT lastName, firstName, address, city, state, zip, email FROM user_info WHERE user_id=" . $u_id;
    $select_query2 = "SELECT username, role FROM users WHERE id=" . $u_id;
    if ($result = mysqli_query($link, $select_query) AND $result2 = mysqli_query($link, $select_query2)) {
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $last_name = $row['lastName'];
                $first_name = $row['firstName'];
                $address = $row['address'];
                $city = $row['city'];
                $state = $row['state'];
                $zip = $row['zip'];
                $email = $row['email'];
                
            }
            mysqli_free_result($result);
        } else {
            //echo "No matching records are found.";
        }
        
        if (mysqli_num_rows($result2) > 0) {
            while ($row2 = mysqli_fetch_array($result2)) {
                $role = $row2['role'];
                $user_name = $row2['username'];
                
            }
            mysqli_free_result($result2);
        } else {
            //echo "No matching records are found.";
        }
    } else {
        echo "ERROR: Could not able to execute sql. "
            . mysqli_error($link);
        return false;
    };
    include_once("./includes/close_conn.inc");
}
?>
<?php include('wrapper/Header.php'); ?>
    <title>Edit User Account</title>
   
</head>
<body>
<?php include('wrapper/Logo.php'); ?>
<div class="page-header">
    <h1><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> please make needed changes.</h1>
</div>    <br><br>
    <div>
        <form action="./php_scripts/editUsersInfoInDB.php" method="post">

            <table align="center">
                <tr>
                    <th>Field Name</th>
                    <th>Current Value</th>
                    <th>New Value</th>
                </tr>
                <tr>
                    <th>id</th>
                    <td><?php echo $u_id; ?></td>
                    <td><input type="hidden" name="editing_user_id" value="<?php echo $u_id; ?>"></td>
                </tr>
                <tr>
                    <th>username</th>
                    <td><?php echo $user_name; ?></td>
                </tr>
                <tr>
                    <th><label>Role</label></th>
                    <td><?php echo $role; ?></td>
                    <td>
                        <div class="form-group">
                            <select name="role" size="1">
                                <option value="HR">HR</option>
                                <option value="Manager">Manager</option>
                                <option value="Trainee">Trainee</option>
                            </select>
                        </div>
                    </td>
                    
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?php echo $first_name; ?></td>
                    <td>
                        <div class="form-group <?php echo (!empty($user_firstName_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="user_firstName" class="form-control"
                                   value="<?php echo $first_name; ?>">
                            <span class="help-block"><?php echo $user_firstName_err; ?></span>
                        </div>
                    </td>
                
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo $last_name; ?></td>
                    <td>
                        <div class="form-group <?php echo (!empty($user_lastName_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="lastName" class="form-control" value="<?php echo $first_name; ?>">
                            <span class="help-block"><?php echo $user_lastName_err; ?></span>
                        </div>
                    </td>
               
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo $address; ?></td>
                    <td>
                        <div class="form-group <?php echo (!empty($user_address_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                            <span class="help-block"><?php echo $user_address_err; ?></span>
                        </div>
                    </td>
                   
                </tr>
                <tr>
                    <th>City</th>
                    <td><?php echo $city; ?></td>
                    <td>
                        <div class="form-group <?php echo (!empty($user_city_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="city" class="form-control" value="<?php echo $city; ?>">
                            <span class="help-block"><?php echo $user_city_err; ?></span>
                        </div>
                    </td>
                   
                </tr>
                <tr>
                    <th>State</th>
                    <td><?php echo $state; ?></td>
                    <td>
                        <div class="form-group <?php echo (!empty($user_state_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="state" class="form-control" value="<?php echo $state; ?>">
                            <span class="help-block"><?php echo $user_state_err; ?></span>
                        </div>
                    </td>
                    
                </tr>
                <tr>
                    <th>Zip</th>
                    <td><?php echo $zip; ?></td>
                    <td>
                        <div class="form-group <?php echo (!empty($user_zip_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="zip" class="form-control" value="<?php echo $zip; ?>">
                            <span class="help-block"><?php echo $user_zip_err; ?></span>
                        </div>
                    </td>
                  
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $email; ?></td>
                    <td>
                        <div class="form-group <?php echo (!empty($user_email_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $user_email_err; ?></span>
                        </div>
                    </td>
              
                </tr>
            </table>
            <div class="form-group">
                <input type="submit" name="" class="btn btn-warning" value="Make Changes">
            </div>
        </form>
        <p>
            <a href="./showAllUsers.php" type="reset" class="btn btn-default" value="Back">Back</a>
            <a href="./php_scripts/sign_out.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p>
     </div>
</body>
</html>