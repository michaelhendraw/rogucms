<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {
	
	public function index() {
		$data = array(
				'content' => $this->load->view('template/error', null, true),
				'title' => 'Error '.ADMIN,
		);
		$this->load->view('template/template', $data);
	}
}