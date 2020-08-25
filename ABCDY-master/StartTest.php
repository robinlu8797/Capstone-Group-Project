<?php


include('wrapper/Header.php');
	// Initialize the session
	session_start();
	
	// Check if the user is logged in, if not then redirect him to login page
	if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Trainee") {
        header("location: index.php");
        exit;
    }
if (isset($_SESSION['TestCompleted'])){
    header("location: welcome.php");
    unset($_SESSION['TestCompleted']);
    exit;
}

include_once("./includes/open_conn.inc"); //opening connection to db
$test_id = $_GET['test_id'];
$_SESSION['test_id'] = $test_id ;
$test_question ="select question_id, question from test_question where test_id = ". $test_id ;

$test_title = "select test_title from test where test_id = ". $test_id;
$test_title_value= mysqli_query($link, $test_title);


?>
    <title>Test</title>
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
        
     
    </style>
<?php include('./wrapper/Logo.php'); ?>
    <div class="page-header">
        
        <?php while($test = mysqli_fetch_assoc($test_title_value)) { ?>
            <h1>Test, <b><?php echo $test['test_title'], $test_id ?></b></h1>
            <br><br>
        <form action="TestScore.php" method="post">
            <?php
        }
        $result = mysqli_query($link, $test_question);
        if (mysqli_num_rows($result) > 0) {
            $x = 0;
            while ($rows = mysqli_fetch_array($result)) {
                $x++;
                echo "<div  id='mainbox'>";
                echo "<h4 style='margin-bottom: -20px; margin-top: 20px;'>Question " . $x. "</h4>";
                echo "<h2>" . htmlspecialchars($rows['question']) . "</h2>";
                $test_answers = "select answer , correct from test_answer where question_id = " . $rows['question_id'];
                $result2 = mysqli_query($link, $test_answers);
                $ans = array();
                while ($answer = mysqli_fetch_array($result2)) {
                    $ans[]= array(
                        'answer' => $answer['answer'],
                        'correct' =>  $answer['correct']
                    );
                }
                shuffle($ans);
                foreach ($ans as $answ) {
                    echo "<input type='radio' name='".$rows['question_id']."' value='" . htmlspecialchars($answ['correct']) . "'>" . htmlspecialchars($answ['answer']) . "<br>";
                }
                echo "</div>";
            }
        }
        ?>
        <br>
        <br>
        <p>
            <input type="Submit" name="Submit" class="btn btn-primary" value="Submit Test">
            <a href="./php_scripts/sign_out.php" class="btn btn-danger">Sign Out of Your Account</a>
           
        </p>
        </form>
    </div>
<?php
include('wrapper/Footer.php');
?>