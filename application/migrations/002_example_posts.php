<?php

class Migration_example_posts extends CI_Migration {

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function up() {
		echo 'Installing example post data'.NL;

		$posts = require 'application/migrations/data/posts.php';

		foreach($posts as $post) {
			$this->posts_model->save(array(
				'title' => $post['title'],
				'slug' => $this->utility->slugify($post['title']),
				'content' => strip_tags($post['content'], '<p><a>'),
				'published' => 1
			));
		}
	}

	public function down() {
		
	}

}