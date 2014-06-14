<?php if (isset($flash_success) || isset($flash_error)): ?>
	
	<div class="row">
		<div class="col-md-12">
			<?php if (isset($flash_success)): ?>
				<div class="alert alert-success">
					<?php echo $flash_success; ?>
				</div>
			<?php endif ?>
			<?php if (isset($flash_error)): ?>
				<div class="alert alert-danger">
					<?php echo $flash_error; ?>
				</div>
			<?php endif ?>
		</div>
	</div>

<?php endif ?>