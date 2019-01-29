<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public $roles = array(
						'admin' => 'Admin',
						'guru' => 'Guru',
					);
	public $genders = array(
						'l' => 'Laki-Laki',
						'p' => 'Perempuan',
					);
	public $statuses = array(
						1 => 'Aktif',
						0 => 'Non Aktif',
					);

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
		
	public function index(){
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			if($this->input->is_ajax_request()){
				$this->load->model('users');
				$users = $this->users->get_datatables();
				$data = array();
				$no = $_POST['start'];
				foreach ($users as $user) {
					$no++;
					$row = array();
					$row[] = $user->code;
					$row[] = $user->name;
					$row[] = $user->email;
					$row[] = ucwords($user->role);
					if($user->status==1){
						$row[] = '<span class="label label-sm label-success">Aktif</span>';
					}elseif($user->status==0){
						$row[] = '<span class="label label-sm label-danger">Non Aktif</span>';
					}
					$row[] = ($user->last_login != '') ? date('d F Y H:i:s', strtotime($user->last_login)) : '-';
					$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'user/edit/'.$user->id.'"><i class="icon-pencil"></i> Ubah</a> 
							<form action="'.base_url().'user/delete/'.$user->id.'" method="POST" style="float: right;"><button class="btn btn-sm btn-danger" onclick="if(confirm(&#39;Apakah Anda yakin untuk menghapus pengguna #'.$user->code.'-'.$user->name.'?&#39;)) return true; else return false;"><i class="icon-trash"></i> Hapus</button></form>';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->users->count_all(),
								'recordsFiltered' => $this->users->count_filtered(),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$data = array(
						'title' => 'Pengguna '.APP,
						'content' => $this->load->view('user/index', null, true),
						'bc_header' => 'Manajemen Pengguna',
						'bc_detail' => 'Pengguna'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect('user/profile');
		}
	}

	public function check_code(){
		$code = $this->input->post('code');
		if($this->input->post('id')!=''){
			$id = $this->input->post('id');
		}else{
			$id = 0;
		}
		$this->load->model('users');
		echo $this->users->check_code($code,$id);
	}

	public function check_email(){
		$email = $this->input->post('email');
		if($this->input->post('id')!=''){
			$id = $this->input->post('id');
		}else{
			$id = 0;
		}
		$this->load->model('users');
		echo $this->users->check_email($email,$id);
	}

	public function add() {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$content['roles'] = $this->roles;
			$content['genders'] = $this->genders;
			$content['statuses'] = $this->statuses;
			$data = array(
					'title' => 'Tambah Pengguna '.APP,
					'content' => $this->load->view('user/add', $content, true),
					'bc_header' => 'Manajemen Pengguna',
					'bc_detail' => 'Pengguna'
			);
			$this->load->view('template/main', $data);
		}else{
			$msg = array(
					'title' => '<h4>Autentikasi Gagal!</h4>',
					'text' => "Maaf, Anda tidak memiliki hak akses untuk mengakses halaman ini",
				);
			$this->session->set_flashdata('error',$msg);
			redirect('user/profile');
		}
	}

	public function insert(){
		$code = $this->security->xss_clean($this->input->post('code'));
		$email = $this->security->xss_clean($this->input->post('email'));
		$password = $this->security->xss_clean($this->input->post('password'));
		$role = $this->security->xss_clean($this->input->post('role'));
		$name = $this->security->xss_clean($this->input->post('name'));
		$pob = $this->security->xss_clean($this->input->post('pob'));
		$dob = $this->security->xss_clean($this->input->post('dob'));
		$gender = $this->security->xss_clean($this->input->post('gender'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$this->load->model('users');

		$check_code = $this->users->check_code($code);
		if($check_code!=0){
			echo 'Maaf, kode sudah ada'; exit;
		}

		$check_email = $this->users->check_email($email);
		if($check_email!=0){
			echo 'Maaf, email sudah ada'; exit;
		}

		$this->db->trans_begin();

		$user = $this->users->insert($code, $email, $password, $role, $name, $pob, $dob, $gender, $status);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, pengguna gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function edit($id) {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('users');
			$check_id = $this->users->check_id($id);

			if($check_id){
				$content['roles'] = $this->roles;
				$content['genders'] = $this->genders;
				$content['statuses'] = $this->statuses;
				$content['data'] = $this->users->get_user($id);
				$data = array(
						'title' => 'Edit Pengguna '.APP,
						'content' => $this->load->view('user/edit', $content, true),
						'bc_header' => 'Manajemen Pengguna',
						'bc_detail' => 'Pengguna'
				);
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
				$this->session->set_flashdata('error',$msg);
				redirect('user');
			}
		}else{
			$msg = array(
					'title' => '<h4>Autentikasi Gagal!</h4>',
					'text' => "Maaf, Anda tidak memiliki hak akses untuk mengakses halaman ini",
				);
			$this->session->set_flashdata('error',$msg);
			redirect('user/profile');
		}
	}

	public function update(){
		$id = $this->security->xss_clean($this->input->post('id'));
		$code = $this->security->xss_clean($this->input->post('code'));
		$email = $this->security->xss_clean($this->input->post('email'));
		$password = $this->security->xss_clean($this->input->post('password'));
		$role = $this->security->xss_clean($this->input->post('role'));
		$name = $this->security->xss_clean($this->input->post('name'));
		$pob = $this->security->xss_clean($this->input->post('pob'));
		$dob = $this->security->xss_clean($this->input->post('dob'));
		$gender = $this->security->xss_clean($this->input->post('gender'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$this->load->model('users');

		$check_code = $this->users->check_code($code,$id);
		if($check_code!=0){
			echo 'Maaf, kode sudah ada'; exit;
		}

		$check_email = $this->users->check_email($email,$id);
		if($check_email!=0){
			echo 'Maaf, email sudah ada'; exit;
		}

		$check_id = $this->users->check_id($id);
		if($check_id){
			$data = $this->users->get_user($id);

			$this->db->trans_begin();

			$user = $this->users->update($id, $code, $email, $password, $role, $name, $pob, $dob, $gender, $status);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    echo 'Maaf, pengguna gagal diedit';
			} else {
			    $this->db->trans_commit();
			  	echo 0;
			}
		}else{
			echo 'Maaf, edit pengguna tidak diizinkan';
		}
	}

	public function profile() {
		$id = $this->session->userdata('user_id');
		$this->load->model('users');
		$check_id = $this->users->check_id($id);

		if($check_id){
			$content['roles'] = $this->roles;
			$content['genders'] = $this->genders;
			$content['statuses'] = $this->statuses;
			$content['data'] = $this->users->get_user($id);
			$data = array(
					'title' => 'Profil Pengguna '.APP,
					'content' => $this->load->view('user/profile', $content, true),
					'bc_header' => 'Manajemen Pengguna',
					'bc_detail' => 'Pengguna'
			);
			$this->load->view('template/main', $data);
		}else{
			$msg = array(
					'title' => '<h4>Tidak ditemukan!</h4>',
					'text' => 'Maaf, data tidak ditemukan',
				);
			$this->session->set_flashdata('error',$msg);
			redirect('user');
		}
	}

	public function update_profile(){
		$id = $this->session->userdata('user_id');
		$email = $this->security->xss_clean($this->input->post('email'));
		$password = $this->security->xss_clean($this->input->post('password'));
		$name = $this->security->xss_clean($this->input->post('name'));
		$pob = $this->security->xss_clean($this->input->post('pob'));
		$dob = $this->security->xss_clean($this->input->post('dob'));
		$gender = $this->security->xss_clean($this->input->post('gender'));

		$this->load->model('users');

		$check_email = $this->users->check_email($email,$id);
		if($check_email!=0){
			echo 'Maaf, email sudah ada'; exit;
		}

		$check_id = $this->users->check_id($id);
		if($check_id){
			$data = $this->users->get_user($id);

			$this->db->trans_begin();

			$user = $this->users->update_profile($id, $email, $password, $name, $pob, $dob, $gender);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    echo 'Maaf, pengguna gagal diedit';
			} else {
			    $this->db->trans_commit();
			  	echo 0;
			}
		}else{
			echo 'Maaf, edit pengguna tidak diizinkan';
		}
	}

	public function delete($id) {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('users');
			$check_id = $this->users->check_id($id);

			if($check_id){
				$this->db->trans_begin();

				$user = $this->users->delete($id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
				    $this->db->trans_rollback();
				    
				    $msg = array(
						'title' => '<h4>Pengguna Gagal Dihapus!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
					$this->session->set_flashdata('error',$msg);
					redirect('user');
				} else {
				    $this->db->trans_commit();
				  	
				  	$msg = array(
						'title' => '<h4>Pengguna Berhasil Dihapus!</h4>',
						'text' => 'Selamat, pengguna berhasil dihapus.'
					);
					$this->session->set_flashdata('success',$msg);
					redirect('user');
				}
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan',
					);
				redirect('user');
			}
		}else{
			$msg = array(
					'title' => '<h4>Autentikasi Gagal!</h4>',
					'text' => "Maaf, Anda tidak memiliki hak akses untuk mengakses halaman ini"
				);
			$this->session->set_flashdata('error',$msg);
			redirect('user/profile');
		}
	}
}