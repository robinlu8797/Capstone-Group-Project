<?php

	session_start();//session is starting
	//checking if loggedin session is set, and role is Manager, if not rederecting to main page
	if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Manager") {
	    header("location: index.php");
	    exit;
	}
	
	function getTrainingName($training_id, $link){//this function is returning training title(aka name) from the training id
		$select_title_query = "SELECT training_title from training where training_id='".$training_id."'";	
		$answr = "UNKNOWN ERROR";	
		if ($result = mysqli_query($link, $select_title_query)) {
	        if (mysqli_num_rows($result) > 0) {
	            while ($row = mysqli_fetch_array($result)) {
	            	$answr = $row['training_title'];
	            }
	        }
      }
		return $answr;
	}
	function getUserName($user_id, $link){//this function is returning training title(aka name) from the training id
		$select_user_name_query = "SELECT username from users where id='".$user_id."'";	
		$answr = "UNKNOWN ERROR";	
		if ($result = mysqli_query($link, $select_user_name_query)) {
	        if (mysqli_num_rows($result) > 0) {
	            while ($row = mysqli_fetch_array($result)) {
	            	$answr = $row['username'];
	            }
	        }
      }
		return $answr;
	}
	
	
?>


<?php include('wrapper/Header.php'); ?>

    
    <title>Trainings</title>
 </head>
 <body>   
<?php include('wrapper/Logo.php'); ?>
	<div class="page-header">
    <h1><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>, this is all assigned and completed trainings on system:</h1>
    <br><br>

        <form action="" method="get" > 
            <table align="center">
                <tr>
                    <th>Training ID</th>
                    <th>Training Title</th>
                    <th>Assigned To</th>
                    <th>Assigned By</th>
						  <th>Assigned Date</th>
                    <th>Completed Date</th>
                </tr>
					<?php 
					  include_once("./includes/open_conn.inc"); //opening connection to db
					  $select_query = "SELECT * FROM training_assigned where training_id is not NULL";
					  if ($res = mysqli_query($link, $select_query)) {
                    if (mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_array($res)) {
                        	echo "<tr>";
                        	echo "<td>". $row['training_id'] ."</td>";
                        	echo "<td>".getTrainingName($row['training_id'], $link)."</td>";
                        	echo "<td>".getUserName($row['assigned_user_id'], $link)."</td>";
                        	echo "<td>".getUserName($row['assigned_by'], $link)."</td>";
									echo "<td>".$row['assigned_dt']."</td>";
									echo "<td>".$row['completed_dt']."</td>";
                        	echo "</tr>";
                        }
                    }
                 }
					
					
					 include_once("./includes/close_conn.inc"); //closing connection to db
					?>
                
            </table>
        </form>
        <p>
            <a href="./manager.php" type="reset" class="btn btn-default" value="Back">Back</a>
        </p>
    </div>

<?php
	include('wrapper/Footer.php');
?>



























