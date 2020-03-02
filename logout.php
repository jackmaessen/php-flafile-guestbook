<?php 
	session_start();
	unset($_SESSION['guestbook_login']);
	header("Location: admin.php?logout=true");
?>
