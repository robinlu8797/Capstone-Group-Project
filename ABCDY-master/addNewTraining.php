<?php
 /* 

 * This php page is for getting information about the new creating training,
 *	and for sending it to script to add it to DB.
 *
 */

	session_start();//session is starting 
	//checking if loggedin session is set, and role is Manager, if not rederecting to main page
	if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Manager") {
	    header("location: index.php");
	    exit;
	}


	$training_title = "";
	$training_title_err = "";
	$training_video_link = "";
	$training_video_link_err = "";
	$training_document_link = "";
	$training_document_link_err = "";
	$training_document = "";
	$training_document_err = "";
	$training_text = "";
	$training_text_err = "";
	$temp_folder ="";
	
	
	if(isset($_POST['reset'])){
		resetInputData();	
	}
	
	
	
	
	if(isset($_POST['training_title']) && trim($_POST['training_title'])!==""){ //checking if title already been entered
		$training_title = $_POST['training_title'];
		$_SESSION['title']=$training_title;
	}elseif(isset($_SESSION['title']) && trim($_SESSION['title'])!==""){ //checking if title is already in session variable
		$training_title = $_SESSION['title'];
	}else{
		$training_title_err = "Please Enter Training Title";	
	}
	
	

	
	if(isset($_SESSION['video_links'])){	// checking if video links array session is set
		$arr_video_links = $_SESSION['video_links'];
	}else{
		$arr_video_links = array();
	}
	
	//Handling add more video links:
	if(isset($_POST['add_more_video_links']) && isset($_POST['training_video_link']) && trim($_POST['training_video_link'])!==""){
		if(checkURL($_POST['training_video_link'])){
			if(in_array($_POST['training_video_link'], $arr_video_links)){
				$training_video_link_err = "This Video Link You already included";
			}else{
				array_push($arr_video_links, $_POST['training_video_link']);
			}				
		}else{
			$training_video_link_err = "Please Enter Valid Video URL";
		}	
		$_SESSION['video_links']= $arr_video_links;		
	}
	
			
	if(isset($_SESSION['document_links'])){ //checking if document links array session is set
		$arr_document_links = $_SESSION['document_links'];
	}else{
		$arr_document_links = array();
	}
	
	//Handling add more document links:
	if(isset($_POST['add_more_document_links']) && isset($_POST['training_document_link']) && trim($_POST['training_document_link'])!==""){
		if(checkURL($_POST['training_document_link'])){
			if(in_array($_POST['training_document_link'], $arr_document_links)){
				$training_document_link_err = "This Document Link You already included";
			}else{
				array_push($arr_document_links, $_POST['training_document_link']);
			}				
		}else{
			$training_document_link_err = "Please Enter Valid Document URL";
		}	
		$_SESSION['document_links']= $arr_document_links;		
	}

	if(isset($_SESSION['local_documents'])){ //checking if local uploaded document array session is set
		$arr_documents = $_SESSION['local_documents'];
	}else{
		$arr_documents = array();
	}

	//Handling add more documents
	if(isset($_POST['add_more_documents']) && isset($_FILES['file_training_document']['name']) && trim($_FILES['file_training_document']['name'])!==""){


		if(checkDublicateFiles($_FILES['file_training_document']['name'], $arr_documents)){
			$training_document_err = $_FILES['file_training_document']['name']. " --> This Document You already included";
		}else{
			$tmp_folder = createTempDirectory();
			$_SESSION['tmp_folder']=$tmp_folder;
			array_push($arr_documents, $_FILES['file_training_document']);
			move_uploaded_file($_FILES['file_training_document']['tmp_name'], $tmp_folder.$_FILES['file_training_document']['name']);
						
		}
		$_SESSION['local_documents'] = $arr_documents;	

	}
	if(isset($_POST['training_text'])){ // checking if training text been entered
		$training_text = sanitizeString($_POST['training_text']);
		$_SESSION['text'] = $training_text;
	}
	
	
	if(isset($_POST['submit'])){ // to create new training title is required
		if(isset($_SESSION['title']) && trim($_SESSION['title'])!==""){
			header("location: ./php_scripts/addTrainingToDB2.php");			
		}else{
			$training_title_err = "Title is required to create training";		
		}
	}
	
	if(isset($_POST['remove_video_link'])){
		removeVideoLinkFromArray($arr_video_links, $_POST['remove_video_link']);
	}
	
	if(isset($_POST['remove_document_link'])){
		removeDocLinkFromArray($arr_document_links, $_POST['remove_document_link']);
	}
	if(isset($_POST['remove_document'])){
		removeDocument($arr_documents, $_POST['remove_document']);
	}
	
	
	
	function removeDocument($arr_documents, $document_to_remove){ //this function is for removing uploaded document
		if(is_file($_SESSION['tmp_folder']. DIRECTORY_SEPARATOR . $document_to_remove)) //checking if this file exists
			unlink($_SESSION['tmp_folder']. DIRECTORY_SEPARATOR . $document_to_remove); // removing file
		
		//print_r($arr_documents);
		//echo $document_to_remove;		
		$index = 0;
		foreach($arr_documents as $doc){
			if($doc['name']===$document_to_remove){
				//print_r($doc);
				unset($arr_documents[$index]); // removing element
				$arr_documents = array_values($arr_documents); // resetting indexes to be started from 0 (as after deletion of element indexes are not rearrange)
				$_SESSION['local_documents'] = $arr_documents; // assigning array with removed element to session variable
				break;			
			}
			$index++;		
		}		
		
		header("Location: ".$_SERVER['PHP_SELF']);
		
	}
	
	
	//this function is for checking if the file is already in submitting array
	function checkDublicateFiles($fileName, $arr_documents){ 
		$answer = false;
		foreach($arr_documents as $doc){
		
			if($doc['name']==$fileName){
				$answer= true;			
			}		
		}
		return $answer;
	}
	
	//this function is for checking if url valid or not
	function checkURL($url){ //code for this function partially is taken from www.w3schools.com
		$url = filter_var($url, FILTER_SANITIZE_URL); // removing all illegal characters from url
		
		//validate url
		if(!filter_var($url, FILTER_VALIDATE_URL) === false){
			return true;		
		}else {
			return false;
		}
		
	}
	
	function resetInputData(){ //this function is for clearing all variables
		if(isset($_SESSION['tmp_folder']) && trim($_SESSION['tmp_folder'])!=="")
			deleteDirectory($_SESSION['tmp_folder']);
		unset($_SESSION['video_links']);
		unset($_SESSION['document_links']);
		unset($_SESSION['local_documents']);
		unset($_SESSION['title']);
		unset($_SESSION['text']);
		unset($_SESSION['tmp_folder']);
		unset($_POST);
		$training_title = "";
		$training_title_err = "";
		$training_video_link = "";
		$training_video_link_err = "";
		$training_document_link = "";
		$training_document_link_err = "";
		$training_document = "";
		$training_document_err = "";
		$training_text = "";
		$training_text_err = "";
	}
	
	function sanitizeString($str){ // to sanitizeStrings
		if(get_magic_quotes_gpc()) $str = stripslashes($str);	
		$str = htmlentities($str);
		$str = strip_tags($str);
		return $str;
	}
	
	function createTempDirectory(){
		$temp_folder = "temp_documents";
		if(is_dir($temp_folder)===false){
			mkdir($temp_folder);		
		}
		if(is_dir($temp_folder."/".$_SESSION['user_id'])===false){
			mkdir($temp_folder."/".$_SESSION['user_id']);	
		}	
		return $temp_folder."/".$_SESSION['user_id']."/";
	}
	
	//function to delete temp files directory
	function deleteDirectory($dir) { //this function is taken from https://stackoverflow.com/questions/1653771/how-do-i-remove-a-directory-that-is-not-empty
	    if (!file_exists($dir)) {
	        return true;
	    }
	
	    if (!is_dir($dir)) {
	        return unlink($dir);
	    }
	
	    foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') {
	            continue;
	        }
	
	        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
	            return false;
	        }	
	    }
	
	    return rmdir($dir);
	}
	
	function removeVideoLinkFromArray($arr_links, $link_to_remove){ //this function is for deleting specific element from  array
		if (($key = array_search($link_to_remove, $arr_links)) !== false) {
		    unset($arr_links[$key]);
			 $_SESSION['video_links']=$arr_links;
		}
		
		header("Location: ".$_SERVER['PHP_SELF']);
			
	}
	
	function removeDocLinkFromArray($arr_links, $link_to_remove){ //this function is for deleting specific element from  array
		if (($key = array_search($link_to_remove, $arr_links)) !== false) {
		    unset($arr_links[$key]);
			 $_SESSION['document_links']=$arr_links;
		}
		
		header("Location: ".$_SERVER['PHP_SELF']);
			
	}
	
?>
    <?php include('wrapper/Header.php'); ?>
    <title>Add New Training</title>
    </head>
    <body>
    <?php include('wrapper/Logo.php'); ?>
<div class="page-header">
    <h1><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>, please Fill all fields for Adding New Training.
    </h1>
  <br><br>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <table align="center">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <td><label>Training Title:</label></td>
                    <td>
                        <div class="form-group <?php echo (!empty($training_title_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="training_title" class="form-control"
                                   value="<?php echo htmlspecialchars($training_title); ?>" required="" placeholder="Enter Title for new Training here">
                            <span class="help-block"><?php echo $training_title_err; ?></span>
                        </div>
                    </td>
                </tr>
              
                <tr>
                    <td><label>Training Video Link:</label></td>
                    <td>
                        <div class="form-group <?php echo (!empty($training_video_link_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="training_video_link" class="form-control"
                                   value="<?php echo $training_video_link; ?>" placeholder="Enter Video Link Here(if you needed), and Click: add this video link button on the right --->">
                            <span class="help-block"><?php echo $training_video_link_err; ?></span>
                        </div>
                    </td>
                    <td>
                        <button onclick="" name="add_more_video_links">add this video link</button>
                    </td>
                </tr>
               <?php //this piece of code is for showing all already entered video links
               	
               	foreach($arr_video_links as $video_link){
							 echo "<tr><td>added video link:</td>";
							 echo "<td>".htmlspecialchars($video_link)."</td>"; 
							 echo "<td><button onclick='' class='btn btn-danger' name='remove_video_link' value='".htmlspecialchars($video_link)."'>Remove Link</button></td>";             	
               	}
               
               	echo "</tr>";
               ?>

                <tr>
                    <td><label>Training Document Link:</label></td>
                    <td>
                        <div class="form-group <?php echo (!empty($training_document_link_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="training_document_link" class="form-control"
                                   value="<?php echo $training_document_link; ?>"  placeholder="Enter Document Link Here(if you needed), and Click: add this document link button on the right --->">
                            <span class="help-block"><?php echo $training_document_link_err; ?></span>
                        </div>
                    </td>
                    <td>
                        <button onclick="" name="add_more_document_links">add this document link</button>
                    </td>
                </tr>
                <?php //this piece of code is for showing all already entered document links
               	
               	foreach($arr_document_links as $document_link){
							 echo "<tr><td>added document link: </td>";
							 echo "<td>$document_link</td>"; 
 							 echo "<td><button onclick='' class='btn btn-danger' name='remove_document_link' value='".htmlspecialchars($document_link)."'>Remove Link</button></td>";                 	
               	}
               
               	echo "</tr>";
               ?>

                <tr>
                    <td><label>Training Document</label></td>
                    <td>
                        <div class="form-group <?php echo (!empty($training_document_err)) ? 'has-error' : ''; ?>">
                            <input type="file" name="file_training_document" class="form-control" >
                            <span class="help-block"><?php echo $training_document_err; ?></span>
                        </div>
                    </td>
                    <td>
                        <button onclick="" name="add_more_documents">add this document</button>
                    </td>
                </tr>
					<?php //this piece of code is for showing all already selected document
               	
               	foreach($arr_documents as $document){
							 echo "<tr><td>added document:</td>";
							 echo "<td>".$document['name']."</td>";  
							 echo "<td><button onclick='' class='btn btn-danger' name='remove_document' value='".htmlspecialchars($document['name'])."'>Remove Document</button></td>";            	
               	}
               
               	echo "</tr>";
               ?>

                <tr>
                    <td>Training Text</td>
                    <td>
                        <div class="form-group <?php echo (!empty($training_text_err)) ? 'has-error' : ''; ?>">
	                			<textarea rows="20" cols="100" name="training_text" placeholder="Enter Here Trainig Text. &#13;&#10;Text can be formatted as you want.&#13;&#10;Also you may include any characters or numbers or symbols in here.&#13;&#10;Text will stay formatted as you entered it."><?php echo $training_text; ?></textarea>
                            <span class="help-block"><?php echo $training_text_err; ?></span>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="form-group">
                <a href="./php_scripts/clearTrainingAddingSessionVariables.php" class="btn btn-default" value="Back">Back</a>
                <button class="btn btn-primary" name="submit" >Create Training</button>
                <button class="btn btn-warning" name="reset" >Reset Entered Data</button>
            </div>
        </form>
</div>
        <?php
        include('wrapper/Footer.php');
        ?>























