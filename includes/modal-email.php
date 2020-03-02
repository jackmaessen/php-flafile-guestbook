
<div class="modal" id="<?php echo $gb_id.'-email'; ?>">
  <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content">
	
		<div class="modal-header">
			<h3 class="modal-title">Email <i><?php echo $gb_name; ?></i></h3>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		
		</div>
		<div class="modal-body">

			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" role="form">	
				<input type="hidden" name="sendemail" value="email" />								
				
				<!-- HONEYPOT FIELD -->
					<input name="honeyname" type="text" class="hide-robot">
				<!-- NAME -->		
					<div class="input-group mb-3">
						<div class="input-group-prepend">
						   <span class="input-group-text">Your email</span>
						</div>
						<input class="form-control" name="email-from" type="email" value="">						
					</div>	
				<!-- EMAIL TO-->
					<div class="input-group mb-3">
						<div class="input-group-prepend">
						   <span class="input-group-text">To</span>
						</div>
						<input class="form-control" name="email-to" type="email" value="<?php echo $gb_email; ?>">						
					</div>
				<!-- SUBJECT -->
					<div class="input-group mb-3">
						<div class="input-group-prepend">
						   <span class="input-group-text">Subject</span>
						</div>
						<input class="form-control" name="email-subject" type="text" value="">						
					</div>
				<!-- MESSAGE -->																	
					<div class="form-group">								
							<textarea rows="6" class="form-control custom-control" name="email-message" placeholder="MESSAGE"></textarea>						
					</div>					
				
					<button class="btn btn-primary float-right" type="submit" name="submit_email">Send</button>	
					<div class="clearfix"></div>	
										
			</form>
					
		</div>
		<div class="modal-footer"></div>
	</div>

  </div>
</div>