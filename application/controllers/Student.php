<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

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
				$this->load->model('students');
				$students = $this->students->get_datatables();
				$data = array();
				$no = $_POST['start'];
				foreach ($students as $student) {
					$no++;
					$row = array();
					$row[] = $student->nisn;
					$row[] = $student->name;
					$row[] = $student->email;
					if($student->status==1){
						$row[] = '<span class="label label-sm label-success">Aktif</span>';
					}elseif($student->status==0){
						$row[] = '<span class="label label-sm label-danger">Non Aktif</span>';
					}
					$row[] = ($student->last_login != '') ? date('d F Y H:i:s', strtotime($student->last_login)) : '-';
					$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'student/edit/'.$student->id.'"><i class="icon-pencil"></i> Ubah</a> 
							<form action="'.base_url().'student/delete/'.$student->id.'" method="POST" style="float: right;"><button class="btn btn-sm btn-danger" onclick="if(confirm(&#39;Apakah Anda yakin untuk menghapus siswa #'.$student->nisn.'-'.$student->name.'?&#39;)) return true; else return false;"><i class="icon-trash"></i> Hapus</button></form>';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->students->count_all(),
								'recordsFiltered' => $this->students->count_filtered(),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$data = array(
						'title' => 'Siswa '.APP,
						'content' => $this->load->view('student/index', null, true),
						'bc_header' => 'Manajemen Pengguna',
						'bc_detail' => 'Siswa'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function check_nisn(){
		$nisn = $this->input->post('nisn');
		if($this->input->post('id')!=''){
			$id = $this->input->post('id');
		}else{
			$id = 0;
		}
		$this->load->model('students');
		echo $this->students->check_nisn($nisn,$id);
	}

	public function check_email(){
		$email = $this->input->post('email');
		if($this->input->post('id')!=''){
			$id = $this->input->post('id');
		}else{
			$id = 0;
		}
		$this->load->model('students');
		echo $this->students->check_email($email,$id);
	}

	public function add() {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('classess');
			$content['classess'] = $this->classess->get_classess();
			$content['genders'] = $this->genders;
			$content['statuses'] = $this->statuses;
			$data = array(
					'title' => 'Tambah Siswa '.APP,
					'content' => $this->load->view('student/add', $content, true),
					'bc_header' => 'Manajemen Pengguna',
					'bc_detail' => 'Siswa'
			);
			$this->load->view('template/main', $data);
		}else{
			$msg = array(
					'title' => '<h4>Autentikasi Gagal!</h4>',
					'text' => "Maaf, Anda tidak memiliki hak akses untuk mengakses halaman ini",
				);
			$this->session->set_flashdata('error',$msg);
			redirect();
		}
	}

	public function insert(){
		$nisn = $this->security->xss_clean($this->input->post('nisn'));
		$email = $this->security->xss_clean($this->input->post('email'));
		$name = $this->security->xss_clean($this->input->post('name'));
		$pob = $this->security->xss_clean($this->input->post('pob'));
		$dob = $this->security->xss_clean($this->input->post('dob'));
		$gender = $this->security->xss_clean($this->input->post('gender'));
		$class_id = $this->security->xss_clean($this->input->post('class_id'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$this->load->model('students');

		$check_nisn = $this->students->check_nisn($nisn);
		if($check_nisn!=0){
			echo 'Maaf, NISN sudah ada'; exit;
		}

		$check_email = $this->students->check_email($email);
		if($check_email!=0){
			echo 'Maaf, email sudah ada'; exit;
		}

		$this->db->trans_begin();

		$student = $this->students->insert($nisn, $email, $name, $pob, $dob, $gender, $class_id, $status);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, siswa gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function edit($id) {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('students');
			$check_id = $this->students->check_id($id);

			if($check_id){
				$this->load->model('classess');
				$content['classess'] = $this->classess->get_classess();
				$content['genders'] = $this->genders;
				$content['statuses'] = $this->statuses;
				$content['data'] = $this->students->get_student($id);
				$data = array(
						'title' => 'Edit Siswa '.APP,
						'content' => $this->load->view('student/edit', $content, true),
						'bc_header' => 'Manajemen Pengguna',
						'bc_detail' => 'Siswa'
				);
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
				$this->session->set_flashdata('error',$msg);
				redirect('student');
			}
		}else{
			$msg = array(
					'title' => '<h4>Autentikasi Gagal!</h4>',
					'text' => "Maaf, Anda tidak memiliki hak akses untuk mengakses halaman ini",
				);
			$this->session->set_flashdata('error',$msg);
			redirect();
		}
	}

	public function update(){
		$id = $this->security->xss_clean($this->input->post('id'));
		$nisn = $this->security->xss_clean($this->input->post('nisn'));
		$email = $this->security->xss_clean($this->input->post('email'));
		$name = $this->security->xss_clean($this->input->post('name'));
		$pob = $this->security->xss_clean($this->input->post('pob'));
		$dob = $this->security->xss_clean($this->input->post('dob'));
		$gender = $this->security->xss_clean($this->input->post('gender'));
		$class_id = $this->security->xss_clean($this->input->post('class_id'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$this->load->model('students');

		$check_nisn = $this->students->check_nisn($nisn,$id);
		if($check_nisn!=0){
			echo 'Maaf, NISN sudah ada'; exit;
		}

		$check_email = $this->students->check_email($email,$id);
		if($check_email!=0){
			echo 'Maaf, email sudah ada'; exit;
		}

		$check_id = $this->students->check_id($id);
		if($check_id){
			$data = $this->students->get_student($id);

			$this->db->trans_begin();

			$student = $this->students->update($id, $nisn, $email, $name, $pob, $dob, $gender, $class_id, $status);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    echo 'Maaf, siswa gagal diedit';
			} else {
			    $this->db->trans_commit();
			  	echo 0;
			}
		}else{
			echo 'Maaf, edit siswa tidak diizinkan';
		}
	}

	public function delete($id) {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('students');
			$check_id = $this->students->check_id($id);

			if($check_id){
				$this->db->trans_begin();

				$student = $this->students->delete($id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
				    $this->db->trans_rollback();
				    
				    $msg = array(
						'title' => '<h4>Siswa Gagal Dihapus!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
					$this->session->set_flashdata('error',$msg);
					redirect('student');
				} else {
				    $this->db->trans_commit();
				  	
				  	$msg = array(
						'title' => '<h4>Siswa Berhasil Dihapus!</h4>',
						'text' => 'Selamat, siswa berhasil dihapus.'
					);
					$this->session->set_flashdata('success',$msg);
					redirect('student');
				}
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan',
					);
				redirect('student');
			}
		}else{
			$msg = array(
					'title' => '<h4>Autentikasi Gagal!</h4>',
					'text' => "Maaf, Anda tidak memiliki hak akses untuk mengakses halaman ini"
				);
			$this->session->set_flashdata('error',$msg);
			redirect();
		}
	}
}