<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index() {
		$this->load->model('users');

		if($this->session->userdata('user_id') != ''){
			if($this->session->userdata('redirect') != ''){
				redirect($this->session->userdata('redirect'));
			}else{
				redirect('home/index');
			}
		}

		$this->form_validation->set_rules(
			'user_email',
			'Email',
			'required',
            array(
            	'required' => 'Email harus diisi',
            )
        );
        $this->form_validation->set_rules(
        	'user_password', 
        	'Password', 
        	'required',
            array(
            	'required' => 'Password harus diisi'
            )
		);
        
		if ($this->form_validation->run() == FALSE) {
			$data = array(
					'content' => $this->load->view('login/index', null, true),
					'title' => 'Login '.ADMIN,
			);
			$this->load->view('template/template', $data);
		} else {
			$user_email = $this->security->xss_clean($this->input->post('user_email'));
			$user_password = $this->security->xss_clean($this->input->post('user_password'));
			
			$this->load->model('users');
			$user = $this->users->check_login($user_email, $user_password);
			
			if($user != 0) {
				$this->global_library->set_session_user($user);

				if($this->session->userdata('user_id') != ''){
					if($this->session->userdata('redirect') != ''){
						redirect($this->session->userdata('redirect'));
					}else{
						redirect('home/index');
					}
				}
			}
			else {
				$msg = array(
							'title' => '<h4>Login Gagal!</h4>',
							'text' => 'Maaf, password salah',
						);
				$this->session->set_flashdata('error',$msg);
				redirect('login');
			}
		}
	}
}