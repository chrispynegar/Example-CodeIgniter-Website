<?php $this->load->view('common/header.inc.php'); ?>

<div class="row">
	<div class="col-md-2">
		<?php $this->load->view('common/sidebar.inc.php'); ?>
	</div>
	<div class="col-md-10">
		<div class="page-header">
			<h1><?php echo isset($post) ? 'Edit' : 'Create'; ?> Post</h1>
		</div>
		<form method="post" class="form-horizontal" role="form">
			<div class="form-group<?php echo isset($errors['title']) ? ' has-error has-feedback' : ''; ?>">
				<label for="title" class="control-label col-sm-2">Title</label>
				<div class="col-sm-10">
					<input type="text" name="title" id="title" class="form-control" value="<?php echo set_value('title', (isset($post->title) ? $post->title : null)); ?>">
					<?php if (isset($errors['title'])): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<p class="help-block"><?php echo $errors['title']; ?></p>
					<?php endif ?>
				</div>
			</div>
			<div class="form-group<?php echo isset($errors['content']) ? ' has-error has-feedback' : ''; ?>">
				<label for="content" class="control-label col-sm-2">Content</label>
				<div class="col-sm-10">
					<textarea name="content" id="content" class="form-control" rows="5"><?php echo set_value('content', (isset($post->content) ? $post->content : null)); ?></textarea>
					<?php if (isset($errors['title'])): ?>
						<span class="glyphicon glyphicon-remove form-control-feedback"></span>
						<p class="help-block"><?php echo $errors['content']; ?></p>
					<?php endif ?>
				</div>
			</div>
			<div class="form-group">
				<label for="content" class="control-label col-sm-2">Published</label>
				<div class="col-sm-10">
					<div class="radio-inline">
						<label>
							<input type="radio" name="published" value="0" <?php echo set_checkbox('published', '0', (isset($post->published) && $post->published == 0)) ?>> No
						</label>
					</div>
					<div class="radio-inline">
						<label>
							<input type="radio" name="published" value="1" <?php echo set_checkbox('published', '1', (isset($post->published) && $post->published == 1) || !isset($post)) ?>> Yes
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php $this->load->view('common/footer.inc.php'); ?>