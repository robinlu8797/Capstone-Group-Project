<?php


	// Initialize the session
	session_start();
	
	// Check if the user is logged in, if not then redirect him to login page
	if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Trainee") {
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
	include_once("./includes/open_conn.inc"); //opening connection to db
	
	
	$write_to_page="";

	if ($_GET) {
    
    $training_id = $_GET['training_id'];
    include_once("./includes/open_conn.inc");
    
    $select_training_query = "SELECT created_by, training_title, create_dt FROM training WHERE training_id=" . $training_id;
    $select_links_query = "SELECT training_link, training_link_type from training_link where training_id=". $training_id;
    $select_text_query = "SELECT training_doc_text from training_document where training_id=". $training_id;
    
  
    if ($result = mysqli_query($link, $select_training_query) AND $result2 = mysqli_query($link, $select_links_query) AND $result3 = mysqli_query($link, $select_text_query))  {
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
					$write_to_page.= "<h2>This is your Training: ".htmlspecialchars($row['training_title'])." (created at: ".htmlspecialchars($row['create_dt']).") </h2>";
					
             
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
						$write_to_page.='<td>Click to open the file --> <a href="./training_documents/'.htmlspecialchars($row2['training_link']).'">'.htmlspecialchars($row2['training_link']).'</a></td>';
					}else if($row2['training_link_type']=="YV"){ //checking if this is youtube link
						$write_to_page.="<td>Youtube Video:</td>";
						$write_to_page.='<td>Click to watch --> <a href=watchVideo.php?video_link='.htmlspecialchars($row2['training_link']).'&training_id='.$training_id.'>'.htmlspecialchars($row2['training_link']).'</a></td>';
					} else if($row2['training_link_type']=="EL"){/// checking if this is external link
						$write_to_page.="<td>External Link</td>";
						$write_to_page.='<td>Click to open website --> <a href="'.htmlspecialchars($row2['training_link']).'">'.htmlspecialchars($row2['training_link']).'</a></td>';
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
 }
?>

<?php include('wrapper/Header.php'); ?>
    <title>Training</title>
   
</head>
<body>
<?php include('wrapper/Logo.php'); ?>
<div class="page-header">
    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?> Welcome to <?php echo getTrainingName($_GET['training_id'], $link); ?> training.</b></h1>



    <br><br>
   <?php echo $write_to_page; ?>

<p>
	<?php include_once("./includes/close_conn.inc"); //closing connection to db ?>
	 <a href="./welcome.php" class="btn btn-info">Back</a>
    <a href="./php_scripts/sign_out.php" class="btn btn-danger">Sign Out of Your Account</a>
</p>
</div>
</body>
</html>