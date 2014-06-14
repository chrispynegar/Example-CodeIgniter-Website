<?php

class Posts_model extends MY_Model {

	/**
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * @var int
	 */
	protected $per_page = 4;

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Soft delete a post
	 *
	 * @param int $id
	 * @return int
	 */
	public function soft_delete($id) {
		return $this->save(array('deleted' => 1), $id);
	}

	/**
	 * Get latest posts
	 *
	 * @return array
	 */
	public function latest() {
		return $this->find('all', array(
			'where' => array(
				array('published', 1),
				array('deleted', 0)
			),
			'order_by' => array(
				array('created', 'desc')
			),
			'limit' => array(
				array(3)
			)
		));
	}

	/**
	 * Get published posts
	 *
	 * @param int $page
	 * @return array
	 */
	public function published($page) {
		return $this->find('all', array(
			'where' => array(
				array('published', 1),
				array('deleted', 0)
			),
			'order_by' => array(
				array('created', 'desc')
			),
			'paginate' => true,
			'page' => $page
		));
	}

	/**
	 * Get a single post
	 *
	 * @param string $slug
	 * @return object
	 */
	public function post($slug) {
		return $this->find('first', array(
			'where' => array(
				array('slug', $slug),
				array('published', 1),
				array('deleted', 0)
			)
		));
	}

	/**
	 * Get all posts
	 *
	 * @param int $page
	 * @return array
	 */
	public function all($page) {
		return $this->find('all', array(
			'where' => array(
				array('deleted', 0)
			),
			'paginate' => true,
			'page' => $page
		));
	}

}