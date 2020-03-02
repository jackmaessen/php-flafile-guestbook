<?php
// check login
session_start();
if(!isset($_SESSION['guestbook_login'])){
	header("Location: login.php");
	exit();
}

include 'settings.php';	
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Flat-file Guestbook</title>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <!-- Bootstrap js; need for modal --> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
<!-- API key for TinyMCE -->
<script src="https://cdn.tiny.cloud/1/cu9iuv1soi8lkx4dfa0qp167qpr7pw81y9rj9n42dvtj1mch/tinymce/5/tinymce.min.js"></script> 
<!-- font awesome kit -->
<script src="https://kit.fontawesome.com/3a46605f9c.js" crossorigin="anonymous"></script>

<style>

.search-form button {
    background: #ffffff;
    border: none;
    float: right;
    margin-top: -30px;
    margin-right: 5px;
    position: relative;
    z-index: 2;
	cursor: pointer;
}
.hide-robot {
	display: none;
}
</style>

</head>
<body>
<!-- prevent form resubmission after refresh -->
<script>
if ( window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>

<section>
    <div class="container">
        <div class="row">
			<div class="col-md-12 col-lg-12"> 
			<br />
				<a class="float-right" href="logout.php"><button class="btn btn-danger">Logout</button></a>
			</div>
		</div>
	</div>
	<br /><br />
</section>

<section>
    <div class="container">
        <div class="row">
		
            <div class="col-md-9 col-lg-9">                
			        <div class="messages">																	
						<?php 
							include "guestbookmessages-admin.php"; 							
						?>																								
					</div>				
			</div>
			<div class="col-md-3 col-lg-3"> 
				
				<!-- status -->
				<div class="status">
					<h3>Filter by Status</h3>	
					
					<div class="input-group">
						<form class="filter-form" action="admin.php" method="GET" role="form">
						    <div class="input-group">
								<select class="form-control" id="status" name="gb_status">						
									<option>Approved</option>
									<option>Pending</option>												
								</select>
								<div class="input-group-append">
									<button class="btn btn-primary" type="submit">Filter</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<br />
				<!-- search -->
				<div class="search">					
					<h3>Search in messages</h3>	
											
					<form class="search-form" action="admin.php" method="GET" role="form">							
						<input class="form-control search-field" type="text" name="gb_search" value="" placeholder="Search for...">													
						<button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button> 												
					</form>																								
				</div>
				<br />				
				<!-- blacklist ip's-->
				<div class="blacklist">					
					<h3>Blacklist</h3>	
											
					<form class="blacklist-form" action="admin.php" method="GET" role="form">							
																			
						<button class="btn btn-primary btn-block" name="blacklist_ip" type="submit">Show Banned IP's</button>	
						<button class="btn btn-primary btn-block mt-2" name="blacklist_words" type="submit">Show Bad Words</button>						
					</form>																								
				</div>
				<br />
				
				<!-- bad words-->
				<div class="blacklist">					
					<h3>Add bad word</h3>	
											
					<form class="blacklist-form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" role="form">							
						<div class="input-group">																
							<input class="form-control" type="text" name="bad_word" placeholder="Add word to blacklist" value="">
							<div class="input-group-append">
								<button class="btn btn-primary" type="submit">Add</button>
							</div>						
						</div>											
					</form>																								
				</div>
				
			</div>
			
		</div>
	</div>

</section>

<script>
tinyMCE.init({
	selector : ".tinymce",
	plugins: "emoticons link",
	
	menubar: false,
	toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons',
  
    selector : "textarea",
	height: 300,
	force_br_newlines : true,
	force_p_newlines : false,
	forced_root_block : '',
    mobile: {
		theme: 'silver',
		plugins: 'emoticons link',
		toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons'
	}					
});
</script>


</body>
</html>

