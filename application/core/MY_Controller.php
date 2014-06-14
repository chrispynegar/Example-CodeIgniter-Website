<?php

/**
 * Core Controller Class
 */
class MY_Controller extends CI_Controller {

	/**
	 * @var array
	 */
	protected $viewdata = array();

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		// If we are access a controller in the 'cli' folder
		// check that we are in a cli request
		if($this->router->directory === 'cli') {
			if(!is_cli()) {
				show_404();
			}
		}

		$this->setup_flashdata();
	}

	protected function setup_flashdata() {
        if($this->session->flashdata('success')) {
            $this->viewdata['flash_success'] = $this->session->flashdata('success');
        }
        
        if($this->session->flashdata('error')) {
            $this->viewdata['flash_error'] = $this->session->flashdata('error');
        }
    }

}