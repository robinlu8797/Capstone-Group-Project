<?php


include('wrapper/Header.php');
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Trainee") {
    header("location: index.php");
    exit;
}
$_SESSION['TestCompleted'] = true;
$test_id = $_SESSION['test_id'];


include_once("./includes/open_conn.inc"); //opening connection to db
$test_title = "select test_title from test where test_id = " . $test_id;
$test_title_value = mysqli_query($link, $test_title);
$right = 0;
$total = 0;
foreach ($_POST as $stuff) {
    if ($stuff != 'Submit Test') {
        if ($stuff == 1) {
            $right++;
            $total++;
        } else if ($stuff == 0)
            $total++;
        else
            echo "ERROR: Occurred during Test Please contact Administrator  ";
    }
}
$percent = ($right / $total) * 100;
$insert_test_query = "INSERT INTO test_scores (test_id , correct,questions, percent, user_id ) values(" . $test_id . "," . $right . "," . $total . "," . $percent . "," . $_SESSION['user_id'] . ")";
if (mysqli_query($link, $insert_test_query)) {
    //echo "test scores added successfully";
    mysqli_insert_id($link);
} else {
    echo "ERROR: Could not able to execute sql. "
        . mysqli_error($link);
}

$update_training_assigned = "UPDATE training_assigned SET completed_dt = '". date("Y-m-d h:i:sa")."' WHERE assigned_user_id = '".$_SESSION['user_id'] ."' and test_id = " . $test_id ." and completed_dt is NULL";
if (mysqli_query($link, $update_training_assigned)) {
    //echo "test scores added successfully";
mysqli_insert_id($link);
} else {
    echo "ERROR: Could not able to execute sql. "
    . mysqli_error($link);

}
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

<?php while ($test = mysqli_fetch_assoc($test_title_value)) { ?>
        <br>
        <br>
        <br>
        
    <h1>Your score for, <b><?php echo $test['test_title'] ?></b></h1>
    <br><br>
    <form action="welcome.php">
        <?php
        echo "<h4>Score:  " . $right . "/" . $total . "</h4><br>";
        echo "<Br>";
        echo "<h4>Percentage " . ($right / $total) * 100 . "% </h4>";
        ?>
        <br>
        <br>
        <p>
            <input type="Submit" name="Submit" class="btn btn-primary" value="Done">
            <a href="./php_scripts/sign_out.php" class="btn btn-danger">Sign Out of Your Account</a>

        </p>
    </form>
    </div>
    <?php
}
include('wrapper/Footer.php');
?>

