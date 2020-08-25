<?php


//include('wrapper/Header.php');
session_start();//session is starting
//checking if loggedin session is set, and role is Manager, if not rederecting to main page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Manager") {
    header("location: index.php");
    exit;
}
	$write_to_page="";

if ($_GET) {
    
    $training_id = $_GET['id'];
    include_once("./includes/open_conn.inc");
    
    $select_training_query = "SELECT created_by, training_title, create_dt FROM training WHERE training_id=" . $training_id;
    $select_links_query = "SELECT training_link, training_link_type from training_link where training_id=". $training_id;
    $select_text_query = "SELECT training_doc_text from training_document where training_id=". $training_id;
    
  
    if ($result = mysqli_query($link, $select_training_query) AND $result2 = mysqli_query($link, $select_links_query) AND $result3 = mysqli_query($link, $select_text_query))  {
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
					$write_to_page.= "<h2>".htmlspecialchars($row['training_title'])." (created at: ".htmlspecialchars($row['create_dt']).") </h2>";
					
             
            }
        }
		  $write_to_page.= "<h3>Training Links:</h3>";
		  
                    
        if (mysqli_num_rows($result2) > 0) {
        		$write_to_page.='<table align="center">
                <tr>
                    <th>Link Type</th>
                    <th>Link</th>
                </tr>';
            while ($row2 = mysqli_fetch_array($result2)) {
            	
            	$write_to_page.="<tr>";
            	if($row2['training_link_type']=="IL"){ /// checking if this is internal link
            		$write_to_page.="<td>Local File</td>";
						$write_to_page.='<td><a href="./training_documents/'.htmlspecialchars($row2['training_link']).'">'.htmlspecialchars($row2['training_link']).'</a></td>';
					}else if($row2['training_link_type']=="YV"){ //checking if this is youtube link
						$write_to_page.="<td>Youtube Video:</td>";
						$write_to_page.='<td><a href="'.htmlspecialchars($row2['training_link']).'">'.htmlspecialchars($row2['training_link']).'</a></td>';
					} else if($row2['training_link_type']=="EL"){/// checking if this is external link
						$write_to_page.="<td>External Link</td>";
						$write_to_page.='<td><a href="'.htmlspecialchars($row2['training_link']).'">'.htmlspecialchars($row2['training_link']).'</a></td>';
					}          
            }
            $write_to_page.='</table>';
        }else{
				$write_to_page.="NONE";        
        }
        
        
        
		  $write_to_page.= "<h3>Training Text:</h3>";        
        if (mysqli_num_rows($result3) > 0) {
            while ($row3 = mysqli_fetch_array($result3)) {
            	
            $write_to_page.='<textarea rows="20" cols="100" name="training_text">'.$row3['training_doc_text'].'</textarea><br />';
					          
            }
        }else{
				$write_to_page.="NONE";        
        }
        
    } else {
        echo "ERROR: Could not able to execute sql. "
            . mysqli_error($link);
        return false;
    };
    include_once("./includes/close_conn.inc");
}
?>
<?php include('wrapper/Header.php'); ?>
    <title>Training Description</title>
   
</head>
<body>
<?php include('wrapper/Logo.php'); ?>
<div class="page-header">
    <h1><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> here you able to See Info About Training.</h1>

    <br><br>

        <?php echo $write_to_page; ?>
<br><br>
        <p>
            <a href="./showAllTrainings.php" type="reset" class="btn btn-default" value="Back">Back</a>
            <a href="./php_scripts/sign_out.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p>
</div>
</body>
</html>