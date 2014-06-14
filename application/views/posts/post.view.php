<?php $this->load->view('common/header.inc.php'); ?>

<div class="row">
	<div class="col-md-9">
		<div class="page-header">
			<h1><?php echo $post->title; ?></h1>
		</div>
		<p class="text-muted"><em>Created on <?php echo date('d/m/Y', strtotime($post->created)) . ' at ' . date('H:m:s', strtotime($post->created)); ?> by Chris Pynegar</em></p>
		<div>
			<?php echo $post->content; ?>
		</div>
		<ul class="media-list comments">
			<?php foreach (array(
				'1' => 'Adrian Martinez',
				'2' => 'Alexander Miles',
				'3' => 'Kitty Harper'
			) as $img => $name): ?>
				<li class="media">
					<a class="pull-left" href="#">
						<img class="media-object img-circle" src="/assets/img/<?php echo $img; ?>.jpg">
					</a>
					<div class="media-body">
						<h4 class="media-heading"><?php echo $name; ?></h4>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed vestibulum quam, 
							sed ullamcorper ipsum. Suspendisse feugiat venenatis urna, et convallis urna iaculis 
							eget. Pellentesque fermentum nisi a ligula fringilla, sit amet ullamcorper metus rhoncus. 
							Donec in placerat purus. Quisque consequat eleifend aliquet. Fusce nisl nibh, malesuada 
							convallis elit vel, accumsan tincidunt lectus. Aliquam ultrices leo diam, et rutrum 
							dolor porta id.
						</p>
					</div>
				</li>
			<?php endforeach ?>
		</ul>
		<form class="form-horizontal">
			<legend>Add a comment</legend>
			<div class="form-group">
				<label for="name" class="control-label col-sm-3">Name</label>
				<div class="col-sm-5">
					<input type="text" name="name" id="name" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="control-label col-sm-3">Email</label>
				<div class="col-sm-5">
					<input type="text" name="email" id="email" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label for="content" class="control-label col-sm-3">Content</label>
				<div class="col-sm-7">
					<textarea name="content" id="content" class="form-control" rows="3"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-10">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-3">
		<?php $this->load->view('common/sidebar.inc.php'); ?>
	</div>
</div>

<?php $this->load->view('common/footer.inc.php'); ?>