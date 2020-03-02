<?php

// Search in messages
$gb_search = $_GET['gb_search']; // get string from url for search
if (isset($gb_search)) {
	
	$searchthis = strtolower($gb_search);
	$filtersearchmatches = array();

	$files = glob("guestbookmessages/*.txt"); // Specify the file directory by extension (.txt)

	foreach($files as $file) // Loop the files in the directory
	{
			
		$handle = @fopen($file, "r");
		if ($handle)
		{
			while (!feof($handle))
			{
				$buffer = fgets($handle);
				if(strpos(strtolower($buffer), $searchthis) !== FALSE) // strtolower; search word not case sensitive
					$filtersearchmatches[] = $file; // The filename of the match, eg: messages/1.txt
					
			}
			fclose($handle);
		}
	}


	/////////////////////////////////

	// if found matches for search
	if (isset($filtersearchmatches)) {
		// sort array 
		rsort($filtersearchmatches); 

		// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
		$numrows = count($filtersearchmatches);
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

		$search_matches = array_slice($filtersearchmatches, $offset, $rowsperpage);

	///////////////////////////////

		//show results:
		?>
		<a class="float-left"href="<?php echo $_SERVER["PHP_SELF"]; ?>">Back to all messages</a>
		<br /><br />
		<?php
		if($search_matches != NULL) { // found a match 
			
			$total_matches = count(array_unique($filtersearchmatches)); // count the number of searchmatches; need for pagination
			$totalpages_match = ceil($total_matches/$rowsperpage); // calculate total pages
			?>
			
			<div class="results">Search results for: <b><?php echo $gb_search; ?></b><span class="float-right">Total: <b><?php echo $total_matches; ?></b></span></div>			
			<br />
			
			<?php
					
			foreach(array_unique($search_matches) as $match) { // array_unique; if 2 or more searchmatches in the same file, show file only once 
										
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
				<div class="noresults">No searchmatches found for:&nbsp;<b><?php echo $gb_search; ?></b></div>
				<div class="clearfix"></div>
			<?php
			
		}
	
	}
}

?>
