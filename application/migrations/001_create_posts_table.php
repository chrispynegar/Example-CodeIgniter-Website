<?php

class Migration_create_posts_table extends CI_Migration {

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function up() {
		echo 'Creating posts table'.NL;

		$this->dbforge->add_field(array(
				'id' =>  array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true
			),
				'title' => array(
				'type' => 'VARCHAR',
				'constraint' => 200
			),
				'slug' => array(
				'type' => 'VARCHAR',
				'constraint' => 250
			),
				'content' => array(
				'type' => 'TEXT'
			),
				'published' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 1
			),
				'deleted' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
				'created' => array(
				'type' => 'DATETIME'
			),
				'modified' => array(
				'type' => 'DATETIME'
			)
		));

		// add keys
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('created');
		$this->dbforge->add_key('modified');

		$this->dbforge->create_table('posts', true);
	}

	public function down() {
		echo 'Dropping posts table'.NL;
		$this->dbforge->drop_table('posts');
	}

}