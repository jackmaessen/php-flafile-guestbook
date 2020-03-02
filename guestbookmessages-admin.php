<?php
// check login
session_start();
if(!isset($_SESSION['guestbook_login'])){
	header("Location: login.php");
	exit();
}


/* BAD WORDS */
if( isset($_POST['bad_word']) ) {
	// move ip to blacklist
	$bad_word = $_POST['bad_word'];
	$blacklist = 'blacklist/words.txt';
	$currentlist  = file_get_contents($blacklist);
	$currentlist .= $bad_word.PHP_EOL;
	// write to file
	file_put_contents($blacklist, $currentlist);
	$response = '<div class="alert alert-success"><b>'.$bad_word.'</b> added to bad words!</div>';
}

/* BAN IP */
if( isset($_POST['ban_ip']) ) {
	// move ip to blacklist
	$guest_ip = $_POST['ban_ip'];
	$blacklist = 'blacklist/ip.txt';
	$currentlist  = file_get_contents($blacklist);
	$currentlist .= $guest_ip.PHP_EOL;
	// write to file
	file_put_contents($blacklist, $currentlist);
	
}

/* ALLOW IP */
if( isset($_POST['allow_ip']) ) {
	$guest_ip = $_POST['allow_ip'];
	$blackfile = "blacklist/ip.txt"; 
	$content = file_get_contents($blackfile);
	$content = str_replace($guest_ip, '', $content); // replace ip with empty line
	// write back to file
	file_put_contents($blackfile, str_replace("\n\n", "\n", $content)); // \n skip empty lines
	$response = '<div class="alert alert-success"><i class="fas fa-check"></i> <b>'.$guest_ip.'</b> removed from blacklist!</div>';
}

/* ALLOW BAD WORD */
if( isset($_POST['allow_word']) ) {
	$badword = $_POST['allow_word'];
	$blackfile = "blacklist/words.txt"; 
	$content = file_get_contents($blackfile);
	$content = str_replace($badword, '', $content); // replace word with empty line
	// write back to file
	file_put_contents($blackfile, str_replace("\n\n", "\n", $content)); // \n skip empty lines
	$response = '<div class="alert alert-success"><i class="fas fa-check"></i> <b>'.$badword.'</b> removed from blacklist!</div>';
}



/* DELETE MESSAGE */
if(isset($_POST['delete_file'])) {							
	$filename = 'guestbookmessages/'.$_POST['delete_file'].'.txt';

	if(file_exists($filename)) {
 
		unlink($filename);	//delete .txt file					 
		$response = '<div class="alert alert-success"><i class="fa fa-check"></i>&nbsp;&nbsp;Message&nbsp;<b>'.$_POST['gb_name'].'</b> deleted!</div>';						 
	}
	else {
		$response = '<div class="alert alert-danger"><i class="fa fa-times"></i>&nbsp;&nbsp;Message&nbsp;<b>'.$_POST['gb_name'].'</b>does not exist!</div>';
	}
}

/* EDIT MESSAGE*/
if (isset($_POST['gb_message'])) {
	
	$admininput = $_POST['gb_id'].PHP_EOL;
	$admininput .= $_POST['gb_ip'].PHP_EOL;
	$admininput .= $_POST['gb_status'].PHP_EOL;
	$admininput .= $_POST['gb_date'].PHP_EOL;
	$admininput .= $_POST['gb_name'].PHP_EOL;
	$admininput .= $_POST['gb_gender'].PHP_EOL;
	$admininput .= $_POST['gb_email'].PHP_EOL;
	$admininput .= $_POST['gb_message'].PHP_EOL;
	
	$messagefile = 'guestbookmessages/';
	$messagefile .= $_POST['gb_id'] . '.txt'; //name of the file is the same as unique_id

	$response ='<div class="alert alert-success"><i class="fa fa-check"></i>&nbsp;&nbsp;Content&nbsp;<b>'.$_POST['gb_name'].'</b> updated!</div>';
	
	$h = fopen($messagefile, 'w+');
	fwrite($h, $admininput);
	fclose($h);
		
}

/* SEND EMAIL */
if(isset($_POST['sendemail'])) {	
		
	$recipient = $_POST['email-to'];
	$subject = $_POST['email-subject'];
	$body = $_POST['email-message'];
	$headers  = 'MIME-Version: 1.0';
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= 'From: '.$_POST['email-from'];
	if(mail($recipient, $subject, $body, $headers)) {
		$response = '<div class="alert alert-success">Email send to <b>'.$recipient.'</b> </div>';
	}
	else {
		$response = '<div class="alert alert-danger">Something went wrong! Please try again</div>';
	}
}

// ECHO RESPONSE (all the echos come here)	
echo $response;


/* SHOW ALL BANNED IP'S */
$gb_blacklist_ip = $_GET['blacklist_ip'];
if( isset($_GET['blacklist_ip']) ) {
	$blackfile = "blacklist/ip.txt"; 
	$lines = file($blackfile, FILE_IGNORE_NEW_LINES); // filedata into an array
	echo '<a class="float-left" href="admin.php">Back to all messages</a>';
	echo '<br /><br />';
	echo '<h3>Banned IP\'s</h3><br />';
	foreach ($lines as $line) {
		if(!empty($line)) {
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" role="form">								
			<div class="input-group">																
				<input class="form-control" type="text" name="allow_ip" placeholder="<?php echo $line; ?>" value="<?php echo $line; ?>" readonly>
				<div class="input-group-append">
					<button class="btn btn-secondary" type="submit">Remove from blacklist</button>
				</div>						
			</div>												
		</form>
		<br />
		<?php
		}
	}
}


/* SHOW ALL BAD WORDS */
$gb_blacklist_words = $_GET['blacklist_words'];
if( isset($_GET['blacklist_words']) ) {
	$blackfile = "blacklist/words.txt"; 
	$lines = file($blackfile, FILE_IGNORE_NEW_LINES); // filedata into an array
	asort($lines); // sort array alphbetically
	
	echo '<a class="float-left" href="admin.php">Back to all messages</a>';
	echo '<br /><br />';
	echo '<h3>Bad word\'s</h3><br />';
	
	foreach ($lines as $line) {
		if(!empty($line)) {
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" role="form">								
			<div class="input-group">																
				<input class="form-control" type="text" name="allow_word" placeholder="<?php echo $line; ?>" value="<?php echo $line; ?>" readonly>
				<div class="input-group-append">
					<button class="btn btn-secondary" type="submit">Remove from blacklist</button>
				</div>						
			</div>												
		</form>
		<br />
		<?php
		}
	}
}



// Read all 'approved' messages 
$filterthis = strtolower('Approved');
$approved_messages = array();

$files = glob("guestbookmessages/*.txt"); // read all the messages in directory "guestbookmessages"
$count_all_messages = count($files);

foreach($files as $file) {// Loop the files in the directory	
		
	$handle = @fopen($file, "r");
							
	if ($handle) {
		
		$lines = file($file); //file into an array
		$buffer = $lines[2]; // grab status line
		
		if(strpos(strtolower($buffer), $filterthis) !== FALSE) { // strtolower; search word not case sensitive	
									
				$approved_messages[] = $file; // The filename of the match
				$count_approved_messages = count($approved_messages); // count nuber of txt files which are filtered				
		}
		fclose($handle);
	}
}



include 'includes/search.php';
include 'includes/status.php';


if( !empty($files) && !isset($gb_search) && !isset($gb_status) && !isset($gb_blacklist_ip) && !isset($gb_blacklist_words) ) {
			
	// sort array
	rsort($files);

	// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
	$numrows = count($files);
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

	$pagemsgs = array_slice($files, $offset, $rowsperpage);
	

	//output header and total messages
	echo '<h3 class="float-left">Messages</h3>';		
	$pending = $count_all_messages - $count_approved_messages;
	echo '<div class="float-right">&nbsp;&nbsp;Pending:&nbsp;<b class="text-danger">'.$pending.'</b></div>';
	echo '<div class="countmessages float-right">Total:&nbsp;<b>'.$count_all_messages.'</b></div>';
			
	echo '<div class="clearfix"></div><br />';	

	
	foreach($pagemsgs as $file) {
		
		
		// get data out of txt file		
		$lines = file($file, FILE_IGNORE_NEW_LINES); // filedata into an array
		
		
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
		
											
		include 'includes/modal.php'; // modal dialog to edit messages
		include 'includes/modal-email.php'; // modal for sending emailmessage
	
		
	} // end foreach


	include 'includes/pagination.php';
	
}
elseif( !isset($gb_search) && !isset($gb_status) && !isset($gb_blacklist_ip) && !isset($gb_blacklist_words) ) {
	echo 'No messages yet...';
}


?>