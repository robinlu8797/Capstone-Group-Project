<?php
/*
 *
 *
 */

session_start();
print_r($_POST);
print_r($_POST['checked_trainiees']);

if(!isset($_POST) || !isset($_POST['checked_trainiees']) || !isset($_POST['test_id'])){
    header("location: ./../manager.php");	// if is coming not from assignTraingTo page( means form post not set) redirecting to Manager Page
    exit;
}
include_once "./../includes/open_conn.inc"; //opening connection to db
foreach($_POST['checked_trainiees'] as $trainee_id ){
    $insert_assined_trainins_query = "insert into training_assigned(assigned_by, assigned_user_id, test_id) values (".$_SESSION['user_id'].",".$trainee_id.",".$_POST['test_id'].")";
    
    if (mysqli_query($link, $insert_assined_trainins_query)) {
    
    } else {
        echo "ERROR: Could not able to execute sql. "
            . mysqli_error($link);
        
    }
}



include_once "./../includes/close_conn.inc";//closing connection to db


header("location: ./../ShowAllAssignedTest.php");	// redirecting to show ALL Assigned Trainings page

?>