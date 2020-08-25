<?php
	/*
	 *	This script is for making training complete 
	 * 
	 *
	 */

	session_start();

	$user_id = "";
	$training_id = "";
	
	
	if(isset($_SESSION['user_id'])){
		$user_id = sanitizeString($_SESSION['user_id']);
	}
	
	if(isset($_GET['training_id'])){
		$training_id = sanitizeString($_GET['training_id']);
		unset($_GET['training_id']);
	}

	
	function sanitizeString($str){
		if(get_magic_quotes_gpc()) $str = stripslashes($str);	
		$str= htmlentities($str);
		$str=strip_tags($str);
		return $str;
	}	
	
	function sanitizeMySQL($link, $str){
		$str = mysqli_real_escape_string($link, $str);
		$user_firstName = "";
		$user_lastName = "";
		$user_address = "";
		$user_city = "";
		$user_state = "";
		$user_zip = "";
		$user_email = "";
		$str = sanitizeString($str);
		return $str;
	}
	
	
	include_once "./../includes/open_conn.inc"; //opening connection to DB
	
	
	$update_training_query = "UPDATE training_assigned set completed_dt=CURRENT_TIMESTAMP WHERE assigned_user_id='".mysqli_real_escape_string($link, $user_id)."' AND training_id='".mysqli_real_escape_string($link, $training_id)."' AND completed_dt IS NULL";
	echo $update_training_query;

	if (mysqli_query($link, $update_training_query)) {

	} else {
	   echo "ERROR: Could not able to execute sql. "
	           . mysqli_error($link);      
	}	
	
	
	
	
	
	
	include_once "./../includes/close_conn.inc"; //closing connection to DB
	clearVariables();
	header("location: ./.././welcome.php");// going back to editUsers Account Page
	
	function clearVariables(){
		$user_id="";
		$training_id = "";	
	
	}
	
?>
















