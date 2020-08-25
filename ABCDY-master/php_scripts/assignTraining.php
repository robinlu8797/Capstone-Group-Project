<?php
	/* 
	 *	This script is for assign Training To Multiple Users.
	 * 
	 *
	 */

	session_start();
	print_r($_POST);
	print_r($_POST['checked_trainiees']);
	
	if(!isset($_POST) || !isset($_POST['checked_trainiees']) || !isset($_POST['training_id'])){
		header("location: ./../manager.php");	// if is coming not from assignTraingTo page( means form post not set) redirecting to Manager Page
		exit;
	}	
	include_once "./../includes/open_conn.inc"; //opening connection to db
	foreach($_POST['checked_trainiees'] as $trainee_id ){
		$insert_assined_trainins_query = "insert into training_assigned(assigned_by, assigned_user_id, training_id) values (".$_SESSION['user_id'].",".$trainee_id.",".$_POST['training_id'].")";
		//echo $insert_assined_trainins_query."<br />";
		
		if (mysqli_query($link, $insert_assined_trainins_query)) {
		    
	   } else {
	       echo "ERROR: Could not able to execute sql. "
	           . mysqli_error($link);
	      
	   }
	}
	
	
	
	include_once "./../includes/close_conn.inc";//closing connection to db
	
	
	header("location: ./../showAllAssignedTrainings.php");	// redirecting to show ALL Assigned Trainings page
	
?>