<?php
	/* 
	 *	This script is for adding Training Information into DB, 
	 *	information which is processed is sent through session variables.
	 * 
	 *
	 */

	session_start();

	
	$training_title = "";
	$training_video_link = "";
	$training_document_link="";
	$training_local_file_link = "";
	$training_local_file_name = "";
	$training_text = "";
	$training_id = -1;	
	$arr_video_links = array();
	$arr_document_links = array();	
	$arr_documents = array();
	
	if(isset($_SESSION['title'])){
		$training_title = sanitizeString($_SESSION['title']);
		unset($_SESSION['title']);
	}
	if(isset($_SESSION['text']) && trim($_SESSION['text'])!==""){
		$training_text = sanitizeString($_SESSION['text']);
		unset($_SESSION['text']);
	}
	if(isset($_SESSION['video_links'])){
		$arr_video_links = $_SESSION['video_links'];
		unset($_SESSION['video_links']);
	}
	if(isset($_SESSION['document_links'])){
		$arr_document_links = $_SESSION['document_links'];
		unset($_SESSION['document_links']);
	}
	if(isset($_SESSION['local_documents'])){
		$arr_documents=$_SESSION['local_documents'];
		unset($_SESSION['local_documents']);
	}
	

	if($training_title!="")	{ //Checking if training title is exist(title is required to create Training)
		include_once "./../includes/open_conn.inc";
		$insert_training_query = "INSERT INTO training (created_by, training_title) values (". mysqli_real_escape_string($link, $_SESSION['user_id']) .",'". mysqli_real_escape_string($link,$training_title) ."')";
		
		if (mysqli_query($link, $insert_training_query)) {
		      //echo "Training added successfully";
				$training_id = mysqli_insert_id($link);//getting last inserted training id
	   } else {
	       echo "ERROR: Could not able to execute sql. "
	           . mysqli_error($link);
	      
	   }
	   
	   if($training_id!=-1){//if training was added successfully and training_id was returned.
	   	
	   	$training_directory_name = createDirectory($training_id); // this is created new directory for training and return path to it
	  
	   	
	   	if(isset($_SESSION['tmp_folder']) && trim($_SESSION['tmp_folder'])!==""){// checking if temporary directory exists, which is mean files were uploaded
		   	foreach (scandir(("./../".$_SESSION['tmp_folder'])) as $item) { // moving all upload temporary documents into training folder
	        		if ($item == '.' || $item == '..') {
	            	continue;
	        		}
					
	        		rename("./../".$_SESSION['tmp_folder'].$item , "./../training_documents/".$training_directory_name."/".$item); // move file from temp to training directory
	        		
	    		}
		   	deleteDirectory(("./../".$_SESSION['tmp_folder'])); // removing temporary directory
	   	}
	   
	   
	   	if(trim($training_text)!=""){
	   		$insert_training_query = "INSERT INTO training_document (training_id, training_doc_text) values (" .mysqli_real_escape_string($link,$training_id).",'".mysqli_real_escape_string($link,$training_text)."')";
	   		//echo $insert_training_query;
	   		if (mysqli_query($link, $insert_training_query)) {
		    
	   		} else {
	       		echo "ERROR: Could not able to execute sql. "
	           	. mysqli_error($link);
	      
	   		}
	   	} 
	   	
	   	
	   	foreach($arr_video_links as $training_video_link)
		   	if($training_video_link!=""){
					  $insert_training_query = "INSERT INTO training_link (training_id, training_link, training_link_type) values (" .$training_id.",'".mysqli_real_escape_string($link,$training_video_link)."', 'YV')"; 	
		   		  if (mysqli_query($link, $insert_training_query)) {
			    
		   		  } else {
		       		  echo "ERROR: Could not able to execute sql. "
		              . mysqli_error($link);
		      
		   		}
		   	}
	   	
	   	
	   	foreach($arr_document_links as $training_document_link)
		   	if($training_document_link!=""){
					  $insert_training_query = "INSERT INTO training_link (training_id, training_link, training_link_type) values (" .$training_id.",'".mysqli_real_escape_string($link,$training_document_link)."', 'EL')"; 	
		   		  if (mysqli_query($link, $insert_training_query)) {
			    
		   		  } else {
		       		  echo "ERROR: Could not able to execute sql. "
		              . mysqli_error($link);
		      
		   		}
		   	}
	   	
	   	
	   	foreach($arr_documents as $doc){
	   		$training_local_file_link = $training_id."/".$doc['name'];
		   	if($training_local_file_link!=""){
					  $insert_training_query = "INSERT INTO training_link (training_id, training_link, training_link_type) values (" .$training_id.",'".mysqli_real_escape_string($link,$training_local_file_link)."', 'IL')"; 	
		   		  if (mysqli_query($link, $insert_training_query)) {
			    
		   		  } else {
		       		  echo "ERROR: Could not able to execute sql. "
		              . mysqli_error($link);
		      
		   		}
		   	}
		   }
   	}
		include_once "./../includes/close_conn.inc";
		
		
		
   }
   
   clearSession();//calling function to clear all variables and session variables
	header("location: ./../showAllTrainings.php");	// redirecting to show ALL Trainings page
	
		
	
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
	
	function createDirectory($training_id){  //function which is creating folder to hold training documents
		$main_folder = "./../training_documents";
		if(is_dir($main_folder)===false){
			mkdir($main_folder);		
		}
		if(is_dir($main_folder."/".$training_id)===false){
			mkdir($main_folder."/".$training_id);	
		}
		return $training_id;
	}
	
	//function to delete temp files directory
	function deleteDirectory($dir) { //this function is taken from https://stackoverflow.com/questions/1653771/how-do-i-remove-a-directory-that-is-not-empty
	    if (!file_exists($dir)) {
	        return true;
	    }
	
	    if (!is_dir($dir)) {
	        return unlink($dir);
	    }
	
	    foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') {
	            continue;
	        }
	
	        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
	            return false;
	        }
	
	    }
	
	    return rmdir($dir);
	}
	
	function clearSession(){ //this function is for clearing all variables and session variables
		unset($_SESSION['video_links']);
		unset($_SESSION['document_links']);
		unset($_SESSION['local_documents']);
		unset($_SESSION['title']);
		unset($_SESSION['text']);
		unset($_SESSION['tmp_folder']);
		unset($_POST);
		$training_title = "";
		$training_video_link = "";
		$training_document_link="";
		$training_local_file_link = "";
		$training_local_file_name = "";
		$training_text = "";
		$training_id = -1;	
		$arr_video_links = array();
		$arr_document_links = array();	
		$arr_documents = array();
	}
	
?>