<?php $this->load->view('common/header.inc.php'); ?>

<div class="row">
	<div class="col-md-2">
		<?php $this->load->view('common/sidebar.inc.php'); ?>
	</div>
	<div class="col-md-10">
		<div class="page-header">
			<h1>Manage Posts</h1>
		</div>
		<?php $this->load->view('common/flashdata.inc.php'); ?>
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="/admin/posts/create"><i class="fa fa-plus"></i> Create</a>
					</li>
				</ul>
			</div>
		</nav>
		<?php if (!empty($posts)): ?>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($posts as $post): ?>
						<tr>
							<td><?php echo $post->id; ?></td>
							<td><?php echo $post->title; ?></td>
							<td>
								<a href="/admin/posts/edit/<?php echo $post->id; ?>" class="btn btn-info" title="Edit this post">
									<i class="fa fa-edit"></i>
								</a>
								<a href="/admin/posts/delete/<?php echo $post->id; ?>" class="btn btn-danger confirm" title="Delete this post" data-confirm="Are you sure you want to delete this post?">
									<i class="fa fa-trash-o"></i>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php $this->load->view('common/pagination.inc.php', array(
				'pagination' => $pagination,
				'url' => '/admin/posts/'
			)); ?>
		<?php else: ?>
			<p class="text-center">There are no posts to display.</p>
		<?php endif ?>
	</div>
</div>

<?php $this->load->view('common/footer.inc.php'); ?>