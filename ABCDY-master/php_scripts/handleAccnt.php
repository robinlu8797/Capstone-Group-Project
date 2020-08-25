<?php

		//	include("./../includes/open_conn.inc");
			$back =  $_SERVER['HTTP_REFERER'];
			$result = "";
			$update_query ="";
				
			if($_GET){
				
				//print_r($_GET);
					
				foreach($_GET as $u_id => $u_status){
					//echo $u_id."  ".$u_status;
									
				    if($u_status=="Disable"){
					 	$update_query = "UPDATE users SET enabled=b'0' WHERE id=".$u_id."";
				
				    }elseif($u_status=="Enable" ){
						$update_query = "UPDATE users SET enabled=b'1' WHERE id=".$u_id."";
				
				    }
				    			    
				}
				
				include_once("./../includes/open_conn.inc");
				if (mysqli_query($link, $update_query)) {
					include_once("./../includes/close_conn.inc");
					header("location: $back");
 									//echo "Record updated successfully";
				} else {
 					echo "Error updating record: " . mysqli_error($link);
				}
				include_once("./../includes/close_conn.inc");	
			}

			header("location: $back");			
 		
?>