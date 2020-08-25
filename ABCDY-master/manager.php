<?php

/* Date         Name            Changes
 * 10/27/2019   Yuan         Coding page
 * 10/28/2019   Dmitriy         Code Cleanup
 *
 *
 *
 */
//include('wrapper/Header.php');
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Manager") {
    header("location: index.php");
    exit;
}
if (isset($_SESSION["Test_Question"])){
    unset($_SESSION["Test_Question"]);
}
?>
<?php include('wrapper/Header.php'); ?>
    <title>Welcome</title>
</head> 
<body>   
<?php include('wrapper/Logo.php'); ?>
<div class="page-header">
    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?>.
           <br>
        </b> Welcome to the Manager Portal.</h1>

    <br>
    <br>
    <br>
    <a href="./showAllTrainings.php" class="row">Show All Trainings</a>
    <br>
    <a href="./showAllAssignedTrainings.php" class="row">Show All Assigned Trainings</a>
    <br>
    <a href="./ShowAllTest.php" class="row">Show All Tests</a></li>
    <br>
    <a href="./ShowAllAssignedTest.php" class="row">Show All Assigned Tests</a>
    <br>
    <a href="./addNewTraining.php" class="row">Create New Training</a>
    <br>
    <a href="./addNewTest.php" class="row">Create New Test</a>
    <br>
    <p>
        <a href="./php_scripts/sign_out.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</div>
<?php
include('wrapper/Footer.php');
?>