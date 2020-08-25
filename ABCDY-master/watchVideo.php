<?php


// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

	function getTrainingName($training_id){//this function is returning training title(aka name) from the training id
		include_once("./includes/open_conn.inc"); //opening connection to db
		$select_title_query = "SELECT training_title from training where training_id='".$training_id."'";	
		$answr = "UNKNOWN ERROR";	
		if ($result = mysqli_query($link, $select_title_query)) {
	        if (mysqli_num_rows($result) > 0) {
	            while ($row = mysqli_fetch_array($result)) {
	            	$answr = $row['training_title'];
	            }
	        }
      }
		include_once("./includes/close_conn.inc"); //closing connection to db
		return $answr;
	}
	
	function getEmbedVideoLink($url){
		$answr = str_replace("watch?v=","embed/",$url);
		
		
		return $answr;	
	
	}
?>
    <?php include('wrapper/Header.php'); ?>
    <title>Training</title>
	 </head>
	<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to for <?php echo getTrainingName($_GET['training_id']);?> training Video.</h1>
	</div>	<br><br>
	
	<div>
		 <iframe width="560" height="315" src="<?php echo getEmbedVideoLink(htmlspecialchars($_GET['video_link'])); ?>" >
		</iframe>
    <br>
        <br>
    <p>
    </p>
		<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-info">Back</a>
    </div>
    
<?php
include('wrapper/Footer.php');
?>