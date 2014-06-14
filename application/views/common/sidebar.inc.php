<div class="sidebar">
	<?php if ($this->uri->segment(1) === 'admin'): ?>
		<div class="well">
			<ul class="nav nav-pills nav-stacked">
				<li>
					<a href="#">
						<i class="fa fa-dashboard fa-fw"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<li<?php echo $this->uri->segment(2) === 'posts' ? ' class="active"' : ''; ?>>
					<a href="/admin/posts">
						<i class="fa fa-pencil fa-fw"></i>
						<span>Posts</span>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-comment fa-fw"></i>
						<span>Comments</span>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-users fa-fw"></i>
						<span>Users</span>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-calendar fa-fw"></i>
						<span>Events</span>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-envelope fa-fw"></i>
						<span>Messages</span>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-wrench fa-fw"></i>
						<span>Settings</span>
					</a>
				</li>
			</ul>
		</div>
	<?php else: ?>
		<div class="item">
			<h3>Latest Posts</h3>
			<ul>
				<?php foreach ($latest_posts as $latest): ?>
					<li><a href="/post/<?php echo $latest->slug ?>"><?php echo $latest->title; ?></a></li>
				<?php endforeach ?>
			</ul>
		</div>
		<div class="item">
			<h3>Login</h3>
			<form action="#" role="form">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</form>
		</div>
	<?php endif; ?>
</div>