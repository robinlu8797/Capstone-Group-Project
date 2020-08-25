<?php

include('wrapper/Header.php');
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
    <meta charset="UTF-8">
    <title>ForkLift Training</title>
	 <link rel="stylesheet" href="./design/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif;
            text-align: center;
            background-image: url("wrapper/Background.jpeg");
            background-repeat: no-repeat;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .page-header{
            background-color: white;
            margin-left: -17%;
            width: 60%;
            margin-top: -30px;
            display:block;
            overflow: scroll;
            height: 800px;
        }
    </style>

    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to for forklift training.</h1>
		<br><br>
		 <iframe width="560" height="315" src="https://www.youtube.com/embed/fc0JWk19Z7I" >
		</iframe>
    <br>
        <br>
    <p>
    </p>
    </div>
<?php
include('wrapper/Footer.php');
?>