<?php $this->load->view('common/header.inc.php'); ?>

<div class="row">
	<div class="col-md-9">
		<?php if (!empty($posts)): ?>
			<?php foreach ($posts as $post): ?>
				<div class="page-header">
					<h2><a href="/post/<?php echo $post->slug; ?>"><?php echo $post->title; ?></a></h2>
				</div>
				<p>
					<?php echo strip_tags(character_limiter($post->content, 500)); ?>
					<a href="/post/<?php echo $post->slug; ?>">Read more</a>
				</p>
			<?php endforeach ?>
			<?php $this->load->view('common/pagination.inc.php', array(
				'pagination' => $pagination,
				'url' => '/'
			)); ?>
		<?php else: ?>
			<p class="text-center">There are no posts to show.</p>
		<?php endif ?>
	</div>
	<div class="col-md-3">
		<?php $this->load->view('common/sidebar.inc.php'); ?>
	</div>
</div>

<?php $this->load->view('common/footer.inc.php'); ?>