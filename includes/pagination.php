<?php
/******  build the pagination links ******/
// Do not show navigation when search request 
if($_GET['gb_search'] == NULL && $_GET['gb_status'] == NULL ) {
	
	?>
	<nav aria-label="Page navigation">
	<ul class="pagination">
	<?php

	// if not on page 1, show back links
	if ($currentpage > 1) {
		// show << link to go back to page 1
		echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=1'><b><<</b></a></li> ";
		// get previous page num
		$prevpage = $currentpage - 1;
		// show < link to go back to 1 page
		echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><b><</b></a></li> ";
	} // end if

	// loop to show links to pagination_range of pages around current page
	for($x = ($currentpage - $pagination_range); $x < (($currentpage + $pagination_range) + 1); $x++){
		// if it's a valid page number...
		if (($x > 0) && ($x <= $totalpages)) {
			// if we're on current page...
			if ($x == $currentpage) {
				// 'highlight' it but don't make a link
				echo "<li class=\"page-item current\"><a class=\"page-link\">$x</a></li>";
				// if not current page...
			} else {
				// make it a link
				echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a></li> ";
			} // end else
		} // end if 
	} // end for
					 
	// if not on last page, show forward and last page links        
	if ($currentpage != $totalpages) {
		// get next page
		$nextpage = $currentpage + 1;
		// echo forward link for next page 
		echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'><b>></b></a></li>";
		// echo forward link for lastpage
		echo "<li class=\"page-item\"><a class=\"page-link\" href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'><b>>></b></a></li>";
	} // end if

	?>
	</ul>
	</nav>
	<?php
}

// If IS filter status request or search request
if($_GET['gb_search'] != NULL || $_GET['gb_status'] != NULL ) {
	
	// grab first part of query string
	parse_str($_SERVER['QUERY_STRING'], $array);
	$val = reset($array);
	$key = key($array);
	$querystring = $key.'='.$val;		
	
	if($total_matches != NULL) { // if found a match

		?>
		<nav aria-label="Page navigation">
		<ul class="pagination">
		<?php

		// if not on page 1, show back links
		if ($currentpage > 1) {
			// show << link to go back to page 1
			?>
			<li class="page-item"><a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?' . $querystring; ?>&currentpage=1"><b><<</b></a></li>
			<?php
			// get previous page num
			$prevpage = $currentpage - 1;
			// show < link to go back to 1 page
			?>
			<li class="page-item"><a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?' . $querystring . '&currentpage=' . $prevpage; ?>"><b><</b></a></li>
			<?php	
		} // end if

		// loop to show links to pagination_range of pages around current page
		for($x = ($currentpage - $pagination_range); $x < (($currentpage + $pagination_range) + 1); $x++){
			// if it's a valid page number...
			if (($x > 0) && ($x <= $totalpages_match)) {
				// if we're on current page...
				if ($x == $currentpage) {
					// 'highlight' it but don't make a link
					?>
					<li class="page-item current"><a class="page-link"><?php echo $x; ?></a></li>
					<?php
					// if not current page...
				} else {
					// make it a link
					?>
					<li class="page-item"><a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?' . $querystring . '&currentpage=' . $x; ?>"><?php echo $x; ?></a></li>
					<?php
				} // end else
			} // end if 
		} // end for
						 
		// if not on last page, show forward and last page links        
		if ($currentpage != $totalpages_match) {
			// get next page
			$nextpage = $currentpage + 1;
			// echo forward link for next page 
			?>
			<li class="page-item"><a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?' . $querystring . '&currentpage=' . $nextpage; ?>"><b>></b></a></li>
			<!-- echo forward link for lastpage -->
			<li class="page-item"><a class="page-link" href=" <?php echo $_SERVER['PHP_SELF'] . '?' . $querystring . '&currentpage=' . $totalpages_match; ?>"><b>>></b></a></li>
			<?php
		} // end if

		?>
		</ul>
		</nav>
		<?php
	}
}


/****** end build pagination links ******/
?>