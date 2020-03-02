
<div id="<?php echo $gb_id; ?>" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">						
				<h4 class="modal-title">Edit message</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" role="form">	
					<input type="hidden" name="gb_id" value="<?php echo $gb_id; ?>" />	
					<input type="hidden" name="gb_ip" value="<?php echo $gb_ip; ?>" />
					<input type="hidden" name="gb_status" value="<?php echo $gb_status; ?>" />					
					<input type="hidden" name="gb_date" value="<?php echo $gb_date; ?>" />
					<input type="hidden" name="gb_gender" value="<?php echo $gb_gender; ?>" />
					
					
					
			<!-- IP NUMBER and CHECKBOX-->		
					<div class="form-check form-check-inline">
						<?php if ( in_array($gb_ip, $blacklistarray) ) { ?>
						<label class="form-check-label text-danger"><i class="fas fa-ban"></i>&nbsp;IP:&nbsp;<?php echo $gb_ip;?></label>
						<?php } else { ?>	
						<label class="form-check-label">IP:&nbsp;<?php echo $gb_ip; ?></label>
						<?php } ?>
					</div>					
					<div class="form-check form-check-inline">						
						<?php if ( in_array($gb_ip, $blacklistarray) ) { ?>
							<input class="form-check-input" name="allow_ip" type="checkbox" value="<?php echo $gb_ip; ?>">
							<label class="form-check-label text-success">Allow IP</label>
						<?php } else { ?>							
							<input class="form-check-input" name="ban_ip" type="checkbox" value="<?php echo $gb_ip; ?>">
							<label class="form-check-label text-danger">Ban IP</label>	
						<?php } ?>	
					</div>									
					<br />
					
			<!-- STATUS-->
					<div class="form-check form-check-inline">	
										
					    <label class="form-check-label">Message:&nbsp;<?php echo $status_output; ?></label>
					</div>
					<?php if($gb_status == 'Pending') { ?>
					<div class="form-check form-check-inline">											
						<input class="form-check-input" name="gb_status" type="checkbox" value="Approved">
						<label class="form-check-label">Approve this message</label>
					</div>
					<?php } ?>
					<br /><br />
			<!-- NAME -->		
					<div class="input-group mb-3">
						<div class="input-group-prepend">
						   <span class="input-group-text">Name</span>
						</div>
						<input class="form-control" name="gb_name" type="text" value="<?php echo $gb_name; ?>">						
					</div>	
			<!-- EMAIL -->
					<div class="input-group mb-3">
						<div class="input-group-prepend">
						   <span class="input-group-text">Email</span>
						</div>
						<input class="form-control" name="gb_email" type="text" value="<?php echo $gb_email; ?>">						
					</div>
			<!-- MESSAGE -->																	
					<div class="form-group">								
							<textarea id="tinymce" class="tinymce form-control custom-control" style="border:none" name="gb_message"><?php echo $gb_message; ?></textarea>						
					</div>					
					<br />
						<button class="btn btn-primary float-right" type="submit" name="submit">Update</button>	
					<div class="clearfix"></div>
					
				</form>
							
			</div>
			<div class="modal-footer"></div>
		</div>

	</div>
</div> <!-- end modal -->