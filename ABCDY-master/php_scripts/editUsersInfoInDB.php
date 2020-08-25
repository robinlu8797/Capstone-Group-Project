<?php
	/* 
	 * 
	 *
	 */

	session_start();

	$user_id = "";
	$user_role = "";
	$user_firstName = "";
	$user_lastName = "";
	$user_address = "";
	$user_city = "";
	$user_state = "";
	$user_zip = "";
	$user_email = "";
	
	
	
	if(isset($_POST['editing_user_id'])){
		$user_id = $_POST['editing_user_id'];
	}
	
	if(isset($_POST['role'])){
		$user_role = sanitizeString($_POST['role']);
		unset($_POST['role']);
	}
	if(isset($_POST['user_firstName'])){
		$user_firstName = sanitizeString($_POST['user_firstName']);
		unset($_POST['user_firstName']);
	}
	if(isset($_POST['lastName'])){
		$user_lastName = sanitizeString($_POST['lastName']);
		unset($_POST['lastName']);
	}	
	if(isset($_POST['address'])){
		$user_address = sanitizeString($_POST['address']);
		unset($_POST['address']);
	}
	if(isset($_POST['city'])){
		$user_city = sanitizeString($_POST['city']);
		unset($_POST['city']);
	}
	if(isset($_POST['state'])){
		$user_state = sanitizeString($_POST['state']);
		unset($_POST['state']);
	}
	if(isset($_POST['zip'])){
		$user_zip = sanitizeString($_POST['zip']);
		unset($_POST['zip']);
	}
	if(isset($_POST['email'])){
		$user_email = sanitizeString($_POST['email']);
		unset($_POST['email']);
	}
	
	function sanitizeString($str){
		if(get_magic_quotes_gpc()) $str = stripslashes($str);	
		$str= htmlentities($str);
		$str=strip_tags($str);
		return $str;
	}	
	
	function sanitizeMySQL($link, $str){
		$str = mysqli_real_escape_string($link, $str);
		$str = sanitizeString($str);
		return $str;
	}
	
	
	include_once "./../includes/open_conn.inc"; //opening connection to DB
	$update_user_role_query = "UPDATE users SET role='".mysqli_real_escape_string($link, $user_role)."' WHERE id=".$user_id;
	if (mysqli_query($link, $update_user_role_query)) {

	} else {
	   echo "ERROR: Could not able to execute sql. "
	           . mysqli_error($link);      
	}	
	
	
	
	$update_user_info_query = "UPDATE user_info SET lastName ='".mysqli_real_escape_string($link, $user_lastName)."', firstName='".mysqli_real_escape_string($link, $user_firstName)."', address='".mysqli_real_escape_string($link, $user_address)."', city='".mysqli_real_escape_string($link, $user_city)."', state='".mysqli_real_escape_string($link, $user_state)."', zip='".mysqli_real_escape_string($link, $user_zip)."',email='".mysqli_real_escape_string($link, $user_email)."' WHERE user_id=".$user_id ;
	
	if (mysqli_query($link, $update_user_info_query)) {

	} else {
	   echo "ERROR: Could not able to execute sql. "
	           . mysqli_error($link);
	      
	}	
	
	
	include_once "./../includes/close_conn.inc"; //closing connection to DB
	clearVariables();
	header("location: ./.././editAccount.php?id=$user_id");// going back to editUsers Account Page
	
	function clearVariables(){
		$user_role = "";
		$user_firstName = "";
		$user_lastName = "";
		$user_address = "";
		$user_city = "";
		$user_state = "";
		$user_zip = "";
		$user_email = "";	
	
	}
	
?>
















