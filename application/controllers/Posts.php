<?php

class Posts extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->viewdata['latest_posts'] = $this->posts_model->latest();
	}

	public function index($page = 1) {
		$this->load->helper('text');

		// Get our posts
		$posts = $this->posts_model->published($page);

		// Set data for the view
		$this->viewdata['posts'] = $posts;
		$this->viewdata['pagination'] = $this->posts_model->pagination();

		// Load view
		$this->load->view('posts/posts.view.php', $this->viewdata);
	}

	public function post($slug) {
		// Find the post
		$post = $this->posts_model->post($slug);

		// If no post was found show a 404
		if(!$post) {
			show_404();
		}

		// Set data for the view
		$this->viewdata['post'] = $post;

		// Load view
		$this->load->view('posts/post.view.php', $this->viewdata);
	}

}