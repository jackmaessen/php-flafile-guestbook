<?php

// Filter category
$gb_status = $_GET['gb_status']; // get string from url for filter
if (isset($gb_status)) {
	
	$filterthis = strtolower($gb_status);
	$filterstatusmatches = array();
	
	$files = glob("guestbookmessages/*.txt"); // Specify the file directory by extension (.txt)

	foreach($files as $file) {// Loop the files in the directory	
			
		$handle = @fopen($file, "r");
								
		if ($handle) {
			
			$lines = file($file); //file into an array
			$buffer = $lines[2]; // grab status line
			
			if(strpos(strtolower($buffer), $filterthis) !== FALSE) { // strtolower; search word not case sensitive	
										
					$filterstatusmatches[] = $file; // The filename of the match					 										
			}
			fclose($handle);
		}
	}

	

	// if found matches for search
	if (isset($filterstatusmatches)) {
		// sort array 
		rsort($filterstatusmatches); 

		// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
		$numrows = count($filterstatusmatches);
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

		$status_matches = array_slice($filterstatusmatches, $offset, $rowsperpage);
		


		
		//show results:
		if($status_matches != NULL) { // found a match 
		
			$total_matches = count($filterstatusmatches); // count the number of matches; need for pagination
			$totalpages_match = ceil($total_matches/$rowsperpage); // calculate total pages
					
			?>
			<a class="float-left"href="<?php echo $_SERVER["PHP_SELF"]; ?>">Back to all messages</a>
			<br /><br />
			<div class="results">Messages with status: <b><?php echo $gb_status; ?></b><span class="float-right">Total: <b><?php echo $total_matches; ?></b></span></div>
		
			
			<div class="clearfix"></div>
			<br />
			<?php
				
			foreach($status_matches as $match) { 
									
				$lines = file($match, FILE_IGNORE_NEW_LINES); // filedata into an array
				
				$gb_id = $lines[0]; // id
				$gb_ip = $lines[1]; // ip
				$gb_status = $lines[2]; // status
				$gb_date = $lines[3]; // date
				$gb_name = $lines[4]; //  name
				$gb_gender= $lines[5]; //  gender
				$gb_email = $lines[6]; //  email
				$gb_message = $lines[7]; // message
				
				
				
				//$readmore_link = $_SERVER["PHP_SELF"]."?page=".$gb_id;
			
				/* OUTPUT CARD */
				include 'includes/output.php'; 
						
				// Modal, ADMIN ONLY
				if( basename($_SERVER['PHP_SELF']) == 'admin.php') {
					include 'includes/modal.php'; // modal dialog to edit messages
				}		
										
			} // end foreach
			
			// pagination under the output
			include 'includes/pagination.php';
		}
		else {
			?>
				<a class="float-left"href="<?php echo $_SERVER["PHP_SELF"]; ?>">Back to all messages</a>
				<br /><br />
				<div class="noresults">No messages found with status:&nbsp;<b> <?php echo $gb_status; ?></b></div>
				<div class="clearfix"></div>
			<?php
		}
	}
}			
?>