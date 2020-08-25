<?php



session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true  ||!isset($_SESSION["role"]) || $_SESSION["role"]!=="HR"){
    header("location: index.php");
    exit;
}
?>
<?php include('wrapper/Header.php'); ?>
    <title>Welcome</title>
	</head>	 

<?php include('wrapper/Logo.php'); ?>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the HR Page	.</h1>
		<br><br>

		<table align="center">
		  <tr>
			<tr>
			<a href="./register.php" class="row">Add New User Into System</a>
			</tr>
			<tr>
			 <a href="./showAllUsers.php" class="row">Show All Users of System</a>
			</tr>			
			
		</table>

        <p>
            <a href="./php_scripts/sign_out.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p>
    </div>
<?php
include('wrapper/Footer.php');
?>