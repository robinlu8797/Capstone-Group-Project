<?php

include('wrapper/Header.php');
session_start();//session is starting
//checking if loggedin session is set, and role is Manager, if not rederecting to main page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Manager") {
    header("location: index.php");
    exit;
}

function getUserName($link, $u_id){
    $select_query = "SELECT username from users where id =" .$u_id;
    if ($result = mysqli_query($link, $select_query)) {
        while ($row = mysqli_fetch_array($result)) {
            return $row['username'];break;
        }
    }
    return "UNKNOWN";
}
?>
<?php include('wrapper/Logo.php'); ?>
<script type="text/javascript" src="./js_scripts/addMoreLinks.js"></script>
<title>Trainings</title>
<link rel="stylesheet" href="./design/bootstrap.css">
<style type="text/css">
 
    
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 95%;
    }
    
    td, th {
        text-align: center;
        padding: 8px;
    }
    

</style>
<div class="page-header">
    <h1>Available tests for <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
    <br><br>
    <div>
        <form action="./php_scripts/NONE.php" method="get">
            <table align="center">
                <tr>
                    <th>Training ID</th>
                    <th>Title</th>
                    <th>Date Created</th>
                    <th>Created By</th>
                    <th>Assign Training To User/s</th>
                
                </tr>
                 <?php
                include_once("./includes/open_conn.inc"); //opening connection to db
                $web_string = "";  // variable to write on the web page
                $select_query = "SELECT test_id, created_by, test_title,create_dt from test";
                if ($res = mysqli_query($link, $select_query)) {
                    if (mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_array($res)) {
                            $web_string = '<tr>';
                            $web_string .= "<td>".htmlspecialchars($row['test_id'])."</td>";
                            $web_string .= '<td>'.htmlspecialchars($row['test_title']).'</a></td>';
                            $web_string .= "<td>".htmlspecialchars($row['create_dt'])."</td>";
                            $web_string .= "<td>".getUserName($link, htmlspecialchars($row['created_by']))."</td>";
                            $web_string .= "<td><a href='./assignTestTo.php?test_id=".htmlspecialchars($row['test_id'])."' class='btn btn-info' title='Click this button to assign test ".htmlspecialchars($row['test_id'])." to users'>Assign To</a></td>";
                            
                            echo $web_string; // printing to the web page.
                        }
                        mysqli_free_result($res);
                    } else {
                        //echo "No matching records are found.";
                    }
                } else {
                    echo "ERROR: Could not able to execute $sql. "
                        . mysqli_error($link);
                    
                    return false;
                };
                
                
                include_once("./includes/close_conn.inc"); //closing connection to db
                ?>
            </table>
        </form>
        <p>
            <a href="./manager.php" type="reset" class="btn btn-default" value="Back">Back</a>
        </p>
    </div>
</div>
<?php
include('wrapper/Footer.php');
?>


























