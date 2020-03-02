<?php
if( basename($_SERVER['PHP_SELF']) == 'admin.php') {
	$admin = true;
}

/* CHECK IP BLACKLIST */
$blacklistfile = 'blacklist/ip.txt';
$blacklistarray = file($blacklistfile, FILE_IGNORE_NEW_LINES);

/* STATUS APPOVED/PENDING */
if($gb_status == 'Approved') {
	$status_output = '<b class="text-success">Approved</b>';	
}
elseif($gb_status == 'Pending') {
	$status_output = '<b class="text-danger">Pending</b>';
}

/* COLOR GENDER ICONS */
if($color_gendericon) {
	if($gb_gender == 'male') { 
		$user_icon =  '<i class="fas fa-user male"></i>';		
	}
	elseif($gb_gender == 'female') { 
		$user_icon =  '<i class="fas fa-user female"></i>';	
	}
	else {
		$user_icon =  '<i class="fas fa-user"></i>';	
	}
}
else {
	$user_icon =  '<i class="fas fa-user"></i>';
}
?>

<div class="card bg-light shadow bg-white rounded">	
	
	<div class="card-header">
		<div class="guest-name float-left"><?php echo $user_icon.'&nbsp;'.$gb_name; ?></div>		
		<div class="guest-email float-right m-1"><i class="fas fa-envelope"></i>&nbsp;<a href="#" data-toggle="modal" data-target="#<?php echo $gb_id.'-email'; ?>"><?php echo $gb_email; ?></a></div>
	</div>
	<div class="card-body">
		<?php echo $gb_message; ?>
	</div>
		
	<div class="card-footer bg-light">
		<div class="guest-timestamp float-left m-1"><i class="fas fa-calendar-alt"></i>&nbsp;<?php echo $gb_date; ?></div>
		
		<div class="clearfix"></div>
		
		
		<?php 
		// ADMIN ONLY
		if($admin) {
			// approved/pending line
			if($gb_status == 'Approved') {
				echo '<div class="guest-status float-left m-1 text-success"><i class="fas fa-check"></i>&nbsp;'.$gb_status.'</div>';
			}
			else {
				echo '<div class="guest-status float-left m-1 text-danger"><i class="fas fa-question"></i>&nbsp;'.$gb_status.'</div>';
			}
			//ip line
			if ( in_array($gb_ip, $blacklistarray) ) {
				echo '<div class="guest-ip float-left m-1 text-danger"><i class="fas fa-ban"></i>&nbsp;IP:&nbsp;'.$gb_ip.'</div>';	
			}
			else {
				echo '<div class="guest-ip float-left m-1">&nbsp;IP:&nbsp;'.$gb_ip.'</div>';	
			}	
							
		?>				
		
		
		<!-- DELETE BUTTON -->
			<form class="float-right" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" role="form">	
				<input type="hidden" class="form-control" name="delete_file" value="<?php echo $gb_id; ?>" />	
				<input type="hidden" class="form-control" name="gb_name" value="<?php echo $gb_name; ?>" />	<!-- name only for echo -->						
				<button class="guest-delete btn btn-danger m-1" type="submit" name="submit">Delete</button>				
			</form>
		<!-- EDIT; Trigger the modal with a button -->
			<button type="button" class="guest-edit btn btn-secondary float-right m-1" data-toggle="modal" data-target="#<?php echo $gb_id; ?>">Edit</button>
		
		<?php
					
		}
		// END ADMIN ONLY
		?>
		
		
		
	</div>
	<div class="clearfix"></div>
</div>
<br /><br /> <!-- 2 breaks between the cards -->




