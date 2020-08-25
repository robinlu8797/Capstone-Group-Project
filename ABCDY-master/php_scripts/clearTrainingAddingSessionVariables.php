<?php
		unset($_SESSION['video_links']);
		unset($_SESSION['document_links']);
		unset($_SESSION['local_documents']);
		unset($_SESSION['title']);
		unset($_SESSION['text']);
		unset($_SESSION['tmp_folder']);
		unset($_POST);
		header("location: ./../manager.php");
?>