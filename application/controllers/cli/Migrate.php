<?php

class Migrate extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->run();
	}

	public function version($version) {
		$this->run($version);
	}

	public function run($version = null) {
		$this->load->library('migration');

		if($version === null) {
			echo 'Migrating database to latest version'.NL;
			$migrate = $this->migration->current();
		} elseif(ctype_digit($version)) {
			echo 'Migrating database to version: '.$version.NL;
			$migrate = $this->migration->version($version);
		} else {
			echo 'Nothing set'.NL;
			return;
		}

		if(!$migrate) {
			show_error($this->migration->error_string());
		}

		exit;
	}

}