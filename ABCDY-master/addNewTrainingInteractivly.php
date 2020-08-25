<?php

include('wrapper/Header.php');
	session_start();//session is starting 
	//checking if loggedin session is set, and role is Manager, if not rederecting to main page
	if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Manager") {
	    header("location: index.php");
	    exit;
	}

	$stepNumber = 1;
	$training_title ="";
	$message = "Here messages will be shown";
	$training_youtube_video ="";
	
	
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
    	
    	if(isset($_POST["training_title"]) && $stepNumber==1){
	    	if(empty(trim($_POST["training_title"])) ){
	        $message = "Please enter Title for new Training and Click Next, or hit back button to go back to Manager page";
	    	}else{
				$training_title = trim($_POST["training_title"]);
				$message = "Title for new training is: :".$training_title; 
				$stepNumber=2;   	
	    	}
    	
    	}
    	
    	if(isset($_POST["training_youtube_video"]) && $stepNumber==2){
    		if(empty(trim($_POST["training_youtube_video"])) ){
	        $message = "";
	    	}else{
				$training_youtube_video = trim($_POST["training_youtube_video"]);
				$message = "Youtube Video Links :".$training_youtube_video; 
				$stepNumber=3;   	
	    	}
    	
    	}
    	if(isset($_POST["training_youtube_video"]) && $stepNumber==3){
    	
    	}
	}
?>
    <script type="text/javascript" src="./js_scripts/addMoreLinks.js"></script>
    <meta charset="UTF-8">
    <title>Add New Training</title>
    <link rel="stylesheet" href="./design/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
            text-align: center;
            background-image: url("wrapper/Background.jpeg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 75%;
        }
        td, th {
            text-align: center;
            padding: 8px;
        }

        .page-header {
            background-color: white;
            margin-left: -10%;
            width: 40%;
            margin-top: -30px;
            display: block;
            overflow: auto;
            height: 800px;
            display: block;
        }
    </style>
	<div class="page-header">
    	<h1><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> here you are able to add New Training.</h1>
	  
    <h1>Please Follow Steps to  Add The New Training.</h1>
    <h2>Step


<?php echo htmlspecialchars($stepNumber);
    	$write_to_page = "";
    	if($stepNumber==1){
			echo " Add Title";
			//$message = "Please Enter Title for New Training, and hit next";    	
    	}else if($stepNumber==2){
    		echo " Add YouTube Link";
    	
    	}else if($stepNumber==3){
			echo " Add Internet documents Links";    	
    	}
    
		    
    
    ?>
    </h2>

    <br><br>
    
     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 		
     		<?php 
     		
     		if($stepNumber==1){
     		
	     		echo '
	     		<div class="form-group">
	                <label>Training Title: </label>
	                <input type="text" name="training_title" value="">
	         </div>';
         }else if ($stepNumber==2){
         	
         	$out = '<input type="hidden" id="training_title" name="training_title" value="';
         	$out.=$training_title.'"';
         	echo $out;
         	
         	
         	echo '
	     		<div class="form-group">
	                <label>You Tube Video Link: </label>
	                <input type="text" name="training_youtube_video" value="">
	         </div>';
         }else if ($stepNumber==3){
         	
         	$out = '<input type="hidden" id="training_title" name="training_title" value="';
         	$out.=$training_title.'"';
         	$out.= '<input type="hidden" id="training_youtube_video" name="training_youtube_video" value="';
         	$out.=$training_youtube_video.'"';
         	
         	
         	
         	echo $out;
         	
         	
         	echo '
	     		<div class="form-group">
	                <label>Internet Document Link:</label>
	                <input type="text" name="training_document_link" value="">
	         </div>';
         }
         
         
         ?>
         <div class="form-group">
               <input type="submit" class="btn btn-primary" value="Next">
         </div> 
     </form>
    <div>
       

        <div class="" id="message_box">
    		<textarea rows="10" cols="100" name="output_text">
	        <?php echo htmlspecialchars($message); ?>       
	   	</textarea>
        </div>
        
        
        
        <p>
        		<a href="./manager.php" class="btn btn-warning">Back To Manager Page</a>
				<a href="./php_scripts/sign_out.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p>

	</div>
    </div>
<?php
    include('wrapper/Footer.php');
    ?>




	






















