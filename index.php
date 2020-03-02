<?php 
// captcha for form
session_start(); 

include 'settings.php';

header('Cache-Control: no cache'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Flat-file Guestbook</title>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<!-- Bootstrap js -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
<!-- API key for TinyMCE -->
<script src="https://cdn.tiny.cloud/1/cu9iuv1soi8lkx4dfa0qp167qpr7pw81y9rj9n42dvtj1mch/tinymce/5/tinymce.min.js"></script> 
<!-- font awesome kit -->
<script src="https://kit.fontawesome.com/3a46605f9c.js" crossorigin="anonymous"></script>

<style>
.tox .tox-statusbar__wordcount {   
    color: red !important;
}
.male {
	color: #02a3fe;
}
.female {
	color: #ec49a6;
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

<section class="section-margine">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                
				<div class="comments">
										
					<?php include "guestbookmessages.php"; ?>
										
					<br /><br /><br />
					<h3>Write your message</h3>					
					<form action="index.php" method="POST" role="form">
														
						<!-- NAME 	-->
						<div class="control-group form-group">
							<div class="controls">
								<input class="form-control" name="name" type="text" placeholder="NAME" required>
							</div>
						</div>
						<!-- GENDER -->
						<div class="control-group form-group">
							<div class="form-check-inline">
								<label class="form-check-label">
									<input type="radio" class="form-check-input" name="gender" value="male" required>Male
								</label>
							</div>
							<div class="form-check-inline">
								<label class="form-check-label">
									<input type="radio" class="form-check-input" name="gender" value="female">Female
								</label>
							</div>
							<div class="form-check-inline disabled">
								<label class="form-check-label">
									<input type="radio" class="form-check-input" name="gender" value="other">Other
								</label>
							</div>
						</div>
						<!-- EMAIL 	-->						
						<div class="control-group form-group">
							<div class="controls">
								<input class="form-control" name="email" type="text" placeholder="EMAIL(optional)">
							</div>
						</div>
						<!-- MESSAGE	-->
						<div class="control-group form-group">	
							<div class="controls">															
								<textarea class="tinymce form-control custom-control" onkeyup="countChar(this)" name="message" placeholder="MESSAGE (max charcters: <?php echo $max_chars; ?>)"></textarea>
								
							</div>
						</div>
						<!-- CAPTCHA 	-->
						<div class="control-group form-group">
							<div class="controls">
							  <img src="includes/captcha.php?rand=<?php echo rand(); ?>" id='captcha_image'>
							  <a href='javascript: refreshCaptcha();'>Refresh captcha</a>
							</div>
						</div>
						<div class="control-group form-group">
							<div class="controls">
							  <input class="form-control" id="cf_captcha_input" type="text" name="captcha" placeholder="Enter captcha"  required>
							</div>
						</div>
						<button type="submit" id="cf-submit" name="submit_message" class="btn btn-primary">SUBMIT</button>							
					</form>					
					
				</div>
								
			</div>
						
		</div>
	</div>
	<br /><br />
</section>

<!-- tinymce config -->
<script src="js/tiny-placeholder.js"></script>
<script src="js/charactercount.js"></script>

<script>

//tiny texteditor
tinyMCE.init({
	selector : ".tinymce",
	plugins: "emoticons placeholder link preview wordcount charactercount",
	elementpath: false,
	

	menubar: false,
	toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | preview | wordcount',
      
	height: 300,
	force_br_newlines : true,
	force_p_newlines : false,
	forced_root_block : '',
	paste_as_text: true,
	
    mobile: {
		theme: 'silver',
		plugins: 'emoticons placeholder link preview wordcount charactercount',
		toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | preview | wordcount'
	}	


	
});


//Refresh Captcha
function refreshCaptcha(){
    var img = document.images['captcha_image'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}


</script>



</body>
</html>


