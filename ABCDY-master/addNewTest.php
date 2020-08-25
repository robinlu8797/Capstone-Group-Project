<?php

include('wrapper/Header.php');
session_start();//session is starting
//checking if logged in session is set, and role is Manager, if not rederecting to main page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Manager") {
    header("location: index.php");
    exit;
}
if(isset($_POST['Add'])){
    add_row();
   
}
if(isset($_POST['Submit'])){
    submit();
}
if(isset($_POST['Reset'])){
    unset($_POST);
    $_SESSION["Test_Question"] = 1;
    $testQuestion = 1;
}
if(isset($_POST['Back'])){
    header("../manager.php");
}
function add_row()
{
    if (isset($_SESSION["Test_Question"])) {
        $_SESSION["Test_Question"]++;
    }
    else {
        $_SESSION["Test_Question"] = 1;
        
    }
}
function submit( )
{
    $count = $_SESSION['total_count'];

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'demo');
    /* Attempt to connect to MySQL database */
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    $insert_test_query = "INSERT INTO test (test_title,created_by) values ('". $_POST['test_title'] ."',".$_SESSION['user_id'] .")";
    if (mysqli_query($link, $insert_test_query)) {
        $test_id = mysqli_insert_id($link);
    } else {
        echo "ERROR: Could not able to execute sql. "
            . mysqli_error($link);
        
    }
    for ($x= 0; $x < $count ; $x++){
        if( isset($_POST['ques_'.$x]) ){
            $insert_test_query = "INSERT INTO test_question (test_id,question ) values
                    (". $test_id  .",'".$_POST['ques_'.$x]."')";
            if (mysqli_query($link, $insert_test_query)) {
                //echo "test added successfully";
                $question_id = mysqli_insert_id($link);
            } else {
                echo "ERROR: Could not able to execute sql. "
                    . mysqli_error($link);
        
            }
        }
        if(isset($_POST['ans_'.$x] )){
            $insert_test_query = "INSERT INTO test_answer (question_id , answer, correct ) values
                    (". $question_id  .",'".$_POST['ans_'.$x]."', b'1' )";
            if (mysqli_query($link, $insert_test_query)) {
               // echo $insert_test_query;
                 mysqli_insert_id($link);
                 
            } else {
                echo "ERROR: Could not able to execute sql. "
                    . mysqli_error($link);
            
            }
        }
        if(isset($_POST['wrong1_'.$x] )){
            $insert_test_query = "INSERT INTO test_answer (question_id , answer, correct ) values
                    (". $question_id  .",'".$_POST['wrong1_'.$x]."', b'0' )";
            if (mysqli_query($link, $insert_test_query)) {
               // echo $insert_test_query;
                mysqli_insert_id($link);
            } else {
                echo "ERROR: Could not able to execute sql. "
                    . mysqli_error($link);
            
            }
        }
        if(isset($_POST['wrong2_'.$x] )){
            $insert_test_query = "INSERT INTO test_answer (question_id , answer, correct ) values
                    (". $question_id  .",'".$_POST['wrong2_'.$x]."',b'0' )";
            if (mysqli_query($link, $insert_test_query)) {
                //echo "test added successfully";
                mysqli_insert_id($link);
            } else {
                echo "ERROR: Could not able to execute sql. "
                    . mysqli_error($link);
            
            }
        }
        if(isset($_POST['wrong3_'.$x] ) ){
            $insert_test_query = "INSERT INTO test_answer (question_id , answer, correct ) values
                    (". $question_id  .",'".$_POST['wrong3_'.$x]."', b'0')";
            if (mysqli_query($link, $insert_test_query)) {
                echo $insert_test_query;
                 mysqli_insert_id($link);
            } else {
                echo "ERROR: Could not able to execute sql. "
                    . mysqli_error($link);
            
            }
        }
    }
   header("Location: manager.php");
}
 if(!isset($_SESSION["Test_Question"])){
        $testQuestion =1;
        $_SESSION["Test_Question"] = $testQuestion;
    }
    else {
        $testQuestion = $_SESSION["Test_Question"];
    }


$answer = null;
$answer1 = null;
$answer2 = null;
$answer3 = null;
$question = null;


?>
<?php include('wrapper/Logo.php'); ?>
    <script type="text/javascript" src="./js_scripts/addMoreLinks.js"></script>
    <title>Add New Training</title>
    <link rel="stylesheet" href="./design/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
            text-align: center;
            background-image: url("wrapper/Background.jpeg");
            background-repeat: no-repeat;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        #mainbox{
            border: 2px solid #dddddd;
            height: 300px;
            
        }
        #question{
            width: 100%;
            margin-top: 50px;
            
        }
        
        #answer{
            float: left;
            clear: right;
            width: 100%;
        }
        .inbox{
            float: left;
            border: 1px solid #dddddd;
            text-align: left;
            padding: 10px;
            margin 10px;
            clear: right;
        }
        .title{
            float: left;
            text-align: left;
            margin: 10px;
            
        }
        .outbox{
            float: left;
            margin-left: 30px;
            margin-top: 10px;
            
        }
        #quest{
            width: 75%;
        }
        .wrapper{

            width: 60%;
            margin-top: -30px;
            display:block;
            overflow: scroll;
            height: 650px;
            display:block;
        }
        form{
            margin-left: 20px;
            margin-right: 20px;
        }
    </style>
<div class = "page-header">
    <h1><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>
    </h1>

        <form action="addNewTest.php" method="post">
           
            <div  class = 'title' id='question'>Test Title
                <br>
                <?php
                if(isset($_POST['test_title'])) {
                    $title = $_POST['test_title'];
                }
                else
                    $title = "";
                ?>
                <input type='text' name='test_title' id='quest' class='inbox' value='<?php echo htmlspecialchars($title) ?>' required>
            </div>
            <br><br><br><br><br><br><br><br>
            <div >
                    <?php
                    $count = 0;
                      echo "<div  id='mainbox'>";
                        echo "<div class = 'title' id='question' >";
                            echo "Test Question 1";
                            echo "<br>";
                            if(isset($_POST['ques_0']) ) {
                                $question =  $_POST['ques_0'];
                            }
                            else
                            $question = "";
                            echo "<input type='text' name='ques_0' id='quest' class='inbox' value='".htmlspecialchars($question)."' required>";
                            
                        echo "</div>";
                        echo "<div class='title' id='answer'>";
                            echo "Correct answer";
                            echo "<br>";
                            if(isset($_POST['ans_0']) ) {
                                $answer =  $_POST['ans_0'];
                            }
                            else
                                $answer = "";
                            echo "<input type='text' name='ans_0' class='inbox' value='".htmlspecialchars($answer)."' required >";
                        echo "</div>";
                            echo "<br>";
                        echo "<div class='title' id='answer'>";
                            echo "Other possible choices";
                            echo "<br>";
                            if(isset($_POST['wrong1_0']) ) {
                                $wrong1 =  $_POST['wrong1_0'];
                            }
                            else
                                $wrong1 = "";
                            if(isset($_POST['wrong2_0']) ) {
                                $wrong2 = $_POST['wrong2_0'];
                            }
                            else
                                $wrong2 = "";
                            if(isset($_POST['wrong3_0']) ) {
                                $wrong3 = $_POST['wrong3_0'];
                            }
                            else
                                $wrong3 = "";
                            echo "<input type='text' name='wrong1_0' class='inbox' value='".htmlspecialchars($wrong1)."' required >";
                            echo "<input type='text' name='wrong2_0' class='inbox' value='".htmlspecialchars($wrong2)."'>";
                            echo "<input type='text' name='wrong3_0' class='inbox' value='".htmlspecialchars($wrong3)."'>";
                        echo "</div>";
                    echo "</div>";
                for($x = 1; $x<$testQuestion; $x++){
                    $number = $x + 1;
                    echo "<div  id='mainbox'>";
                        echo "<div class = 'title' id='question'>";
                            echo "Test Question ".$number;
                            echo "<br>";
                            if(isset($_POST['ques_'.$x]) ) {
                                $question =  $_POST['ques_'.$x];
                            }
                            else
                            $question = "";
                            echo "<input type='text' name='ques_".$x."' id='quest' class='inbox' value='".htmlspecialchars($question)."' required >";
                            
                        echo "</div>";
                        echo "<div class='title' id='answer'>";
                            echo "Correct answer";
                            echo "<br>";

                            if(isset($_POST['ans_'.$x])) {
                                $answer =$_POST['ans_'.$x];
                            }
                            else   {
                                $answer = "";}
                            echo "<input type='text' name='ans_".$x."' class='inbox' value='".htmlspecialchars($answer)."' required >";
                        echo "</div>";
                            echo "<br>";
                        echo "<div class='title' id='answer'>";
                            echo "Other possible choices";
                            echo "<br>";
                            if(isset($_POST['wrong1_'.$x]) ) {
                                $wrong1 =  $_POST['wrong1_'.$x];
                            }
                            else
                                $wrong1 = "";
                            if(isset($_POST['wrong2_'.$x]) ) {
                                $wrong2 = $_POST['wrong2_'.$x];
                            }
                            else
                                $wrong2 = "";
                            if(isset($_POST['wrong3_'.$x]) ) {
                                $wrong3 = $_POST['wrong3_'.$x];
                            }
                            else
                                $wrong3 = "";
                    echo "<input type='text' name='wrong1_".$x."' class='inbox' value='".htmlspecialchars($wrong1)."' required>";
                    echo "<input type='text' name='wrong2_".$x."' class='inbox' value='".htmlspecialchars($wrong2)."'>";
                    echo "<input type='text' name='wrong3_".$x."' class='inbox' value='".htmlspecialchars($wrong3)."'>";
                        echo "</div>";
                    echo "</div>";

                    
                }
                    $_SESSION['total_count'] = $x;
                ?>
            <div class="outbox">
                <input type="Submit" name="Add" class="btn btn-default" value="Add Question">
                <input  type="Submit" name = "Submit"class="btn btn-default" >
                <a href="./manager.php" type="reset" class="btn btn-default" value="Back">Back</a>
              
            </div>
            

        </form>
</div>


























