<?php

class Posts extends MY_Controller {

	public function __construct() {
		parent::__construct();

		// Check that the user has admin priviledges here
	}

	public function index($page = 1) {
		$this->viewdata['posts'] = $this->posts_model->all($page);
		$this->viewdata['pagination'] = $this->posts_model->pagination();

		$this->load->view('admin/posts/posts.view.php', $this->viewdata);
	}

	public function create() {
		$this->edit();
	}

	public function edit($id = null) {
		if($data = $this->input->post()) {
			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('content', 'Content', 'required');

			if($this->form_validation->run()) {
				// Generate slug if its a new post
				$data['slug'] = $this->utility->slugify($data['title']);

				if($this->posts_model->save($data, $id)) {
					$this->session->set_flashdata('success', 'Post was successfully saved.');
				} else {
					$this->session->set_flashdata('error', 'Post could not be saved.');
				}

				redirect('admin/posts');
			}
		}

		if($id !== null) {
			$this->viewdata['post'] = $this->find_post($id);
		}

		// Validation errors
		$this->viewdata['errors'] = $this->form_validation->error_array();

		$this->load->view('admin/posts/edit.view.php', $this->viewdata);
	}

	public function delete($id) {
		$post = $this->find_post($id);

		if($this->posts_model->soft_delete($id)) {
			$this->session->set_flashdata('success', 'Post was successfully deleted.');
		} else {
			$this->session->set_flashdata('error', 'Post could not be deleted.');
		}

		redirect('/admin/posts');
	}

	private function find_post($id) {
		// Check to see if the post exists
		$post = $this->posts_model->find($id);

		// Redirect to the index with an error if no post was found
		if(!$post) {
			$this->session->set_flashdata('error', 'No post was found');
			redirect('admin/posts');
		} else {
			return $post;
		}
	}

}