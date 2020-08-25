<?php
/* Date         Name            Changes
 *  9/28/2019   Charles         initial coding
 * 10/27/2019   Andrey,Yuan         Coding page
 * 10/28/2019   Dmitriy         Code Cleanup
 * 12/10/2019   Andrey,Yuan          Some rewritings
 *
 *
 *
 */

session_start();
require_once "./includes/open_conn.inc";
$username = "";
$password = "";
$role = "";
$username_err = "";
$password_err = "";
$enabled = b'0';
$user_id = -1;
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	    if(empty(trim($_POST["username"]))){
	        $username_err = "Please enter username.";
	    } else{
	        $username = trim($_POST["username"]);
	    }
	    if(empty(trim($_POST["password"]))){
	        $password_err = "Please enter password.";
	    } else{
	        $password = trim($_POST["password"]);
	    }
	    
	    
	    if(trim($username_err)=="" && trim($password_err)==""){
	        $select_query = "SELECT id, username, password, role, enabled FROM users WHERE username='" .mysqli_real_escape_string($link, $username)."'";
	       // echo $select_query;
	       
	        if ($res = mysqli_query($link, $select_query)) {
              if (mysqli_num_rows($res) > 0) {
                  while ($row = mysqli_fetch_array($res)) {
							$enabled = $row['enabled'];                  	
                  	$username = $row['username'];
							$password = $row['password'];
							$role = $row['role'];
                  	$user_id = $row['id'];
                  	
                  	
                  	if($enabled==b'1'){ // checking if users account is not disabled
                        		changeLastLogInTime($user_id, $link);
	                           session_start();
	                           $_SESSION["loggedin"] = true;
	                           $_SESSION["user_id"] = $user_id;
	                           $_SESSION["username"] = $username;    
										$_SESSION["role"] = $role; 	
										switch($role){
											case "HR":
												header("location: hr.php");
												break;
											case "Trainee":
												header("location: welcome.php");
												break;
											case "Manager":
												header("location: manager.php");
												break;
											default:
												header("location: welcome.php");
												break;
										}
									}
                  }
              }else {
               //echo "No matching records are found.";
           	  }
          } else {
              echo "ERROR: Could not able to execute query: "
                        . mysqli_error($link);
                    
               return false;
          }
	       
	       
	       
	    }   
             
	  
	}

	function changeLastLogInTime($u_id, $link){
		$update_query = "UPDATE users SET last_login=CURRENT_TIMESTAMP WHERE id=".$u_id."";
		mysqli_query($link, $update_query);
	}
	include_once("./includes/close_conn.inc"); //closing connection to db
?>
	<?php include('./wrapper/Header.php'); ?>
    <title>Login</title>
	</head>    
    <body>
	<?php include('./wrapper/Logo.php'); ?>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
<?php
include('wrapper/Footer.php');
?>