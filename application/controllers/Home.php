<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();

		ini_set('memory_limit', '-1');

		// $function_without_login = array('');
		// if(!in_array($this->uri->segment(2),$function_without_login)){
			if($this->session->userdata('user_id') == ''){
				$msg = array(
						'title' => '<h4>Autentikasi Gagal!</h4>',
						'text' => 'Maaf, Anda harus login untuk mengakses halaman ini',
					);
				$this->session->set_flashdata('error',$msg);
				redirect('login');
			}
		// }
	}

	public function index() {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('homes');
			$content = '';
			$data = array(
				'content' => $this->load->view('home/admin', $content, true),
				'title' => 'Home '.ADMIN,
				'bc_header' => 'Dashboard',
				'bc_detail' => 'Home'
			);
		}elseif(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('homes');
			$content = '';
			$data = array(
				'content' => $this->load->view('home/teacher', $content, true),
				'title' => 'Home '.ADMIN,
				'bc_header' => 'Dashboard',
				'bc_detail' => 'Home'
			);
		}

		$this->load->view('template/main', $data);
	}
}