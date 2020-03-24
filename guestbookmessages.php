<?php

if(isset($_POST['submit_message'])) {
		
	// check ip in blacklist					
	$userip = $_SERVER['REMOTE_ADDR'];
	$blacklistfile = 'blacklist/ip.txt';
	$blacklistarray = file($blacklistfile, FILE_IGNORE_NEW_LINES);
	if ( in_array($userip, $blacklistarray) ) {
		$response = '<div class="alert alert-danger">You are not allowed to post messages anymore</div>';
		$error = true;
	}
	
	// status
	if($need_approval) {
		$userstatus = 'Pending';		
	}
	else {
		$userstatus = 'Approved';
	}
	
	// name block
	if (isset($_POST['name'])) {		
		$safeuserinput = htmlentities($_POST['name']);
		if($safeuserinput == NULL){
			$response = '<div class="alert alert-danger">Please fill in your name!</div>';
			$error = true;	
		} else {
			$username = $safeuserinput;
		}
	}
	
	// gender block
	if (!isset($_POST['gender'])) {
		$response = '<div class="alert alert-danger">Please fill in a gender!</div>';
		$error = true;
	}
	else {
		$safeuserinput = htmlentities($_POST['gender']);		
		$usergender = $safeuserinput;
			
	}
	

	// email block
	if (isset($_POST['email'])) {
		$safeuserinput = htmlentities($_POST['email']);
				
		if($safeuserinput == NULL) { // email empty
			$useremail = '';
		} 								
		elseif( !filter_var($safeuserinput, FILTER_VALIDATE_EMAIL) ) {
			$response = '<div class="alert alert-danger"><b>'.$safeuserinput.'</b> seems to be invalid!</div>';
			$error = true;
		}
		else {
			$useremail = $safeuserinput;
		}
	}
			
	// captcha
	if ( isset($_POST['captcha']) && ($_POST['captcha'] !="") ) {
		// Validation: Checking entered captcha code with the generated captcha code
		if(strcasecmp($_SESSION['captcha'], $_POST['captcha']) != 0) {
			// Note: the captcha code is compared case insensitively.
			// if you want case sensitive match, update the check above to strcmp()
			$response = '<div class="alert alert-danger">Entered captcha code does not match! Kindly try again.</div>';
			$error = true;	
		}			
	}
			
		
	// message block
	if ( isset($_POST['message']) ) {
				
		$safeuserinput = htmlentities($_POST['message']);
		$usermessage = $safeuserinput;
		
		// captcha
		if ($_POST['captcha'] == NULL)  {
			$response = '<div class="alert alert-danger">Please fill in the captcha code!</div>';			
			$error = true;							
		}
		
		// filter bad words and replace with ***
		if($filter_badwords) { 		
			$blacklistfile = 'blacklist/words.txt';
			$blacklistarray = file($blacklistfile, FILE_IGNORE_NEW_LINES);
			$replaceArray = "***";
			$usermessage = str_ireplace($blacklistarray, $replaceArray, $usermessage);
		}
		// check empty message
		if ($usermessage == NULL){				
			$response = '<div class="alert alert-danger">Message field cannot be empty!</div>';								
		}
		// check number of characters submitted
		elseif( strlen( strip_tags($_POST['message']) ) > $max_chars ) { 
			$response = '<div class="alert alert-danger">You exceeded max number of allowed characters!</div>';			
			$error = true;				
		}		
		elseif(!$error) {
			
			// set name and content of message file; each message has unique id
			$unique_id = 'id_'.date('YmdHis');
							
			// put content in .txt file with linebreaks; unique_id first
			$userinput = $unique_id.PHP_EOL;
			$userinput .= $userip.PHP_EOL; // ip address
			$userinput .= $userstatus.PHP_EOL; // status, pending or approved
			$userinput .= date('d M Y H:i').PHP_EOL; // date				
			$userinput .= $username.PHP_EOL; // name
			$userinput .= $usergender.PHP_EOL; // gender			
			$userinput .= $useremail.PHP_EOL; // email
			$userinput .= $usermessage.PHP_EOL; // message
			
							
			$messagefile = 'guestbookmessages/';
			$messagefile .= $unique_id . '.txt'; //name of the file is the same as unique_id

			// mail feature
			//$to = 'name@mail.com'; // your email address if you want new posts in guestbook mailed to you.
			//$subject = $userinput1.' has written a new post in your guestbook';
			//mail($to, $subject, $userinput);

			// create file in messages folder
			$h = fopen($messagefile, 'w+');
			fwrite($h, html_entity_decode($userinput));
			fclose($h);
			
			if($need_approval) {
				$response = '<div class="alert alert-warning"><i class="fas fa-exclamation"></i> Your message is awaiting moderation!</div>';
			}
			else {
				$response = '<div class="alert alert-success"><i class="fas fa-check"></i> Your message has been posted!</div>';
			}
				
		}
	}
	
}	

if(isset($_POST['submit_email'])) {
	// Sending email
	if( isset($_POST['sendemail']) ) {	
			
		$recipient = $_POST['email-to'];
		$subject = $_POST['email-subject'];
		$body = $_POST['email-message'];
		$headers  = 'MIME-Version: 1.0';
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$headers .= 'From: '.$_POST['email-from'];
				
		//honey pot field
		$honeypot = $_POST['honeyname'];
		
		if( ! empty( $honeypot ) ) {
			$error = true;
		}
		elseif(!filter_var($recipient, FILTER_VALIDATE_EMAIL)) { 			
			$response = '<div class="alert alert-danger"><b>'.$recipient.'</b> seems to be invalid!</div>';
			$error = true;
		}		
		elseif(!$error) {
			if( mail($recipient, $subject, $body, $headers) ) { 
				$response = '<div class="alert alert-success">Email send to <b>'.$recipient.'</b> </div>';
			}
			else {
				$response = '<div class="alert alert-danger">Something went wrong! Please try again</div>';
			}
		}
		
	}
}

// Read all 'Approved' messages 
$filterthis = strtolower('Approved');
$messagelist = array();

$files = glob("guestbookmessages/*.txt"); // Specify the file directory by extension (.txt)

foreach($files as $file) {// Loop the files in the directory	
		
	$handle = @fopen($file, "r");
							
	if ($handle) {
		
		$lines = file($file); //file into an array
		$buffer = $lines[2]; // grab status line
		
		if(strpos(strtolower($buffer), $filterthis) !== FALSE) { // strtolower; search word not case sensitive	
									
				$messagelist[] = $file; // The filename of the match
				$countmessages = count($messagelist); // count nuber of txt files which are filtered				
		}
		fclose($handle);
	}
}


// ECHO RESPONSE (ALL THE ECHOS COME HERE)
echo $response;


// check if ANY of the messages is Approved
if( !empty($messagelist) ) {
			
	// sort array
	rsort($messagelist);

	// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
	$numrows = count($messagelist);
	$totalpages = ceil($numrows/$rowsperpage);

	// get the current page or set a default
	if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
		// cast var as int
		$currentpage = (int) $_GET['currentpage'];
	} else {
		// default page num
		$currentpage = 1;
	} // end if

	// if current page is greater than total pages...
	if ($currentpage > $totalpages) {
		// set current page to last page
		$currentpage = $totalpages;
	} // end if

	// if current page is less than first page...
	if ($currentpage < 1) {
		// set current page to first page
		$currentpage = 1;
	} // end if

	// the offset of the list, based on current page 
	$offset = ($currentpage - 1) * $rowsperpage;
	//echo $offset;

	$pagemsgs = array_slice($messagelist, $offset, $rowsperpage);
	

	//output header and total messages
	echo '<h3 class="float-left">Messages</h3>';				
	echo '<div class="countmessages float-right">Total:&nbsp;<b>'.$countmessages.'</b></div>';		
	echo '<div class="clearfix"></div><br />';	

	
	foreach($pagemsgs as $file) {
		
		// get data out of txt file		
		$lines = file($file, FILE_IGNORE_NEW_LINES);// filedata into an array
		
		$gb_id = $lines[0]; // id
		$gb_ip = $lines[1]; // ip
		$gb_status = $lines[2]; // status
		$gb_date = $lines[3]; // date
		$gb_name = $lines[4]; //  name
		$gb_gender = $lines[5]; //  gender
		$gb_email = $lines[6]; //  email
		$gb_message = $lines[7]; // message
		
		
		/* OUTPUT CARD */
		include 'includes/output.php'; 
		
		include 'includes/modal-email.php'; // modal for sending emailmessages		
	} // end foreach

	
	include 'includes/pagination.php';
	
}
// no approved messages
else {
	echo 'No messages yet...';
}


?>
