<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classes extends CI_Controller {

	public $statuses = array(
						1 => 'Aktif',
						0 => 'Non Aktif',
					);
	public $types = array(
						'material_learn' => 'Belajar',
						'material_quiz' => 'Latihan Soal',
						'material_discussion' => 'Diskusi',
						'final_quiz' => 'Latihan UN',
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
		if(in_array($this->session->userdata('user_role'), array('admin','guru'))){
			if($this->input->is_ajax_request()){
				$conds='';
				if($this->session->userdata('user_role')=='guru'){
					$conds='cs.user_id = '.$this->session->userdata('user_id');
				}
				$this->load->model('classess');
				$classess = $this->classess->get_datatables($conds);
				$data = array();
				$no = $_POST['start'];
				foreach ($classess as $class) {
					$no++;
					$row = array();
					$row[] = $class->name;
					if($class->status==1){
						$row[] = '<span class="label label-sm label-success">Aktif</span>';
					}elseif($class->status==0){
						$row[] = '<span class="label label-sm label-danger">Non Aktif</span>';
					}
					if($this->session->userdata('user_role')=='admin'){
						$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'classes/edit/'.$class->id.'"><i class="icon-pencil"></i> Edit </a> 
								<form action="'.base_url().'classes/delete/'.$class->id.'" method="POST" style="float: right;"><button class="btn btn-sm btn-danger" onclick="if(confirm(&#39;Apakah Anda yakin untuk menghapus kelas #'.$class->name.'?&#39;)) return true; else return false;"><i class="icon-trash"></i> Hapus</button></form>';
					}elseif($this->session->userdata('user_role')=='guru'){
						$row[] = '<a class="btn btn-sm btn-success" href="'.base_url().'classes/'.$class->id.'/student"><i class="fa fa-users"></i> Siswa</a> 
								<a class="btn btn-sm purple" href="'.base_url().'classes/'.$class->id.'/subject"><i class="fa fa-book"></i> Mapel</a>';
					}
							$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->classess->count_all($conds),
								'recordsFiltered' => $this->classess->count_filtered($conds),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$data = array(
						'title' => 'Kelas '.APP,
						'content' => $this->load->view('class/index', null, true),
						'bc_header' => 'Belajar Mengajar',
						'bc_detail' => 'Kelas'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function check_name(){
		$name = $this->input->post('name');
		if($this->input->post('id')!=''){
			$id = $this->input->post('id');
		}else{
			$id = 0;
		}
		$this->load->model('classess');
		echo $this->classess->check_name($name,$id);
	}

	public function add() {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('students');
			$content['statuses'] = $this->statuses;
			$data = array(
					'title' => 'Tambah Kelas '.APP,
					'content' => $this->load->view('class/add', $content, true),
					'bc_header' => 'Belajar Mengajar',
					'bc_detail' => 'Kelas'
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
		$name = $this->security->xss_clean($this->input->post('name'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$this->load->model('classess');

		$check_name = $this->classess->check_name($name);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$this->db->trans_begin();

		$class = $this->classess->insert($name, $status);

		$students = $this->input->post('student');
		foreach ($students as $ks => $vs) {
			$student_id = $this->security->xss_clean($vs['student_id']);

			$student = $this->classess->update_student($class, $student_id);
		}

		$subjects = $this->input->post('subject');
		foreach ($subjects as $ks => $vs) {
			$subject_id = $this->security->xss_clean($vs['subject_id']);
			$user_id = $this->security->xss_clean($vs['user_id']);
			
			$subject = $this->classess->insert_subject($class, $subject_id, $user_id);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, kelas gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function edit($id) {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('classess');
			$check_id = $this->classess->check_id($id);

			if($check_id){
				$this->load->model('classess');
				$content['statuses'] = $this->statuses;
				$content['data'] = $this->classess->get_class($id);
				$content['subjects'] = $this->classess->get_class_subject($id);
				$content['students'] = $this->classess->get_class_student($id);
				$data = array(
						'title' => 'Edit Kelas '.APP,
						'content' => $this->load->view('class/edit', $content, true),
						'bc_header' => 'Belajar Mengajar',
						'bc_detail' => 'Kelas'
				);
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
				$this->session->set_flashdata('error',$msg);
				redirect('classes');
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
		$name = $this->security->xss_clean($this->input->post('name'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$this->load->model('classess');

		$check_name = $this->classess->check_name($name,$id);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$check_id = $this->classess->check_id($id);
		if($check_id){
			$data = $this->classess->get_class($id);

			$this->db->trans_begin();

			$class = $this->classess->update($id, $name, $status);
			
			// student
			$students = $this->input->post('student');
			foreach ($students as $ks => $vs) {
				$student_id = $this->security->xss_clean($vs['student_id']);

				$student = $this->classess->update_student($id, $student_id);
			}

			// class_subject
			$existingSubjects = $this->classess->get_class_subject($id);
			$editSubjects=array();
			$subjects = $this->input->post('subject');
			foreach ($subjects as $ks => $vs) {
				$subject_id = $this->security->xss_clean($vs['subject_id']);
				$user_id = $this->security->xss_clean($vs['user_id']);
				
				if(!empty($vd['id'])){ // update class_subject
					$class_subject_id = $this->security->xss_clean($vd['id']);

					$this->quizes->update_subject($class_subject_id, $id, $subject_id, $user_id);

					$editSubjects[$class_subject_id]=$class_subject_id;
				}else{ // insert class_subject
					$subject = $this->classess->insert_subject($id, $subject_id, $user_id);
				}
			}

			// delete old class_subject
			foreach ($editSubjects as $subject) {
				if (!in_array($subject['id'], $editSubjects)) {
					$this->quizes->delete_subject($subject['id']);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    echo 'Maaf, kelas gagal diedit';
			} else {
			    $this->db->trans_commit();
			  	echo 0;
			}
		}else{
			echo 'Maaf, edit kelas tidak diizinkan';
		}
	}

	public function delete($id) {
		if(in_array($this->session->userdata('user_role'), array('admin'))){
			$this->load->model('classess');
			$check_id = $this->classess->check_id($id);

			if($check_id){
				$this->db->trans_begin();

				$class = $this->classess->delete($id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
				    $this->db->trans_rollback();
				    
				    $msg = array(
						'title' => '<h4>Kelas Gagal Dihapus!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
					$this->session->set_flashdata('error',$msg);
					redirect('classes');
				} else {
				    $this->db->trans_commit();
				  	
				  	$msg = array(
						'title' => '<h4>Kelas Berhasil Dihapus!</h4>',
						'text' => 'Selamat, kelas berhasil dihapus.'
					);
					$this->session->set_flashdata('success',$msg);
					redirect('classes');
				}
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan',
					);
				redirect('classes');
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

	public function student_index($id) {
		if(in_array($this->session->userdata('user_role'), array('admin','guru'))){
			if($this->input->is_ajax_request()){
				$this->load->model('students');
				$conds = "class_id=".$id;
				$students = $this->students->get_datatables($conds);
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
					$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'classes/'.$id.'/student/log/'.$student->id.'"><i class="fa fa-search"></i> Log Aktivitas</a>';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->students->count_all($conds),
								'recordsFiltered' => $this->students->count_filtered($conds),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$content['class_id'] = $id;
				$data = array(
						'title' => 'Siswa '.APP,
						'content' => $this->load->view('class/student_index', $content, true),
						'bc_header' => 'Belajar Mengajar',
						'bc_detail' => 'Siswa'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function student_log($class_id, $student_id) {
		if(in_array($this->session->userdata('user_role'), array('admin','guru'))){
			$this->load->model('students');
			$check_id = $this->students->check_id($student_id);

			if($check_id){
				$content['student'] = $this->students->get_student($student_id);
				
				if($this->input->post('date')!=''){
					$date = $this->input->post('date');
				}else{
					$date = date('Y-m');
				}
	
				$this->load->model('classess');
				$content['types'] = $this->types;
				$content['class_id'] = $class_id;
				$content['student_id'] = $student_id;
				$content['date'] = $date;
				$content['logs'] = $this->classess->get_student_log($student_id, $date);
				$data = array(
						'title' => 'Siswa '.APP,
						'content' => $this->load->view('class/student_log', $content, true),
						'bc_header' => 'Belajar Mengajar',
						'bc_detail' => 'Siswa'
				);
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
				$this->session->set_flashdata('error',$msg);
				redirect('classes/'.$class_id.'/student');
			}
		}else{
			redirect();
		}
	}

	public function subject_index($class_id){
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			if($this->input->is_ajax_request()){
				$conds = 'class_id='.$class_id;
				$this->load->model('class_subjects');
				$class_subjects = $this->class_subjects->get_datatables($conds);
				$data = array();
				$no = $_POST['start'];
				foreach ($class_subjects as $subject) {
					$no++;
					$row = array();
					$row[] = $subject->name;
					$row[] = '<a href="'.base_url().'uploads/subject/'.$subject->image.'?t='.time().'" target="_blank"><div class="jobseeker-img-div-profile"><img class="jobseeker-img-profile" src="'.base_url().'uploads/subject/'.$subject->image.'?t='.time().'" alt="'.$subject->name.'"></div><a/>';
					$row[] = '<a class="btn btn-sm btn-success" href="'.base_url().'classes/'.$class_id.'/subject/'.$subject->id.'/topic"><i class="fa fa-book"></i> Topik</a>
							<a class="btn btn-sm purple" href="'.base_url().'classes/'.$class_id.'/subject/'.$subject->id.'/quiz"><i class="fa fa-files-o"></i> Latihan UN</a>';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->class_subjects->count_all($conds),
								'recordsFiltered' => $this->class_subjects->count_filtered($conds),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$content['class_id'] = $class_id;
				$data = array(
						'title' => 'Mapel '.APP,
						'content' => $this->load->view('class/subject_index', $content, true),
						'bc_header' => 'Belajar Mengajar',
						'bc_detail' => 'Mapel'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function topic_index($class_id, $subject_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			if($this->input->is_ajax_request()){
				$conds = 'subject_id='.$subject_id;
				$this->load->model('topics');
				$topics = $this->topics->get_datatables($conds);
				$data = array();
				$no = $_POST['start'];
				foreach ($topics as $topic) {
					$no++;
					$row = array();
					$row[] = $topic->name;
					$row[] = $topic->subject_name;
					$row[] = $topic->sequence;
					$row[] = '<a class="btn btn-sm btn-success" href="'.base_url().'classes/'.$class_id.'/subject/'.$subject_id.'/topic/'.$topic->id.'/material" target="_blank"><i class="fa fa-book"></i> Materi</a>
							<a class="btn btn-sm btn-primary" href="'.base_url().'classes/'.$class_id.'/subject/'.$subject_id.'/topic/'.$topic->id.'/discussion"><i class="fa fa-wechat"></i> Diskusi</a>';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->topics->count_all($conds),
								'recordsFiltered' => $this->topics->count_filtered($conds),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$content['class_id'] = $class_id;
				$content['subject_id'] = $subject_id;
				$data = array(
						'title' => 'Topik '.APP,
						'content' => $this->load->view('class/topic_index', $content, true),
						'bc_header' => 'Belajar Mengajar',
						'bc_detail' => 'Topik'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function topic_discussion($class_id, $subject_id, $topic_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('class_subjects');
			$check_id = $this->class_subjects->check_class_discussion($class_id, $subject_id, $topic_id);
			if($check_id){
				$class_discussion_id = $check_id[0]['id'];
			}else{
				$class_discussion_id = $this->class_subjects->insert_discussion($class_id, $subject_id, $topic_id);
			}
			$content['discussions'] = $this->class_subjects->get_discussion($class_discussion_id);
			$content['class_id'] = $class_id;
			$content['subject_id'] = $subject_id;
			$content['topic_id'] = $topic_id;
			$content['class_discussion_id'] = $class_discussion_id;
			$data = array(
					'title' => 'Diskusi '.APP,
					'content' => $this->load->view('class/topic_discussion', $content, true),
					'bc_header' => 'Belajar Mengajar',
					'bc_detail' => 'Diskusi'
			);
			$this->load->view('template/main', $data);
		}else{
			redirect();
		}
	}

	public function topic_discussion_add($class_id, $subject_id, $topic_id, $class_discussion_id) {
		$description = $this->security->xss_clean($this->input->post('description'));

		$this->load->model('class_subjects');

		$this->db->trans_begin();

		$class_subjects = $this->class_subjects->insert_discussion_detail($class_discussion_id, $description);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, diskusi gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function quiz_index($class_id, $subject_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			if($this->input->is_ajax_request()){
				$this->load->model('class_subjects');
				$class_subject = $this->class_subjects->get_class_subject_id($class_id, $subject_id);
				$class_subject_id = $class_subject[0]['id'];

				$conds = 'class_subject_id='.$class_subject_id;
				$this->load->model('class_quizes');
				$class_quizes = $this->class_quizes->get_datatables($conds);
				$data = array();
				$no = $_POST['start'];
				foreach ($class_quizes as $quiz) {
					$no++;
					$row = array();
					$row[] = $quiz->name;
					$row[] = $quiz->subject_name;
					$row[] = ($quiz->open_date != '') ? date('d F Y H:i:s', strtotime($quiz->open_date)) : '-';
					$row[] = ($quiz->close_date != '') ? date('d F Y H:i:s', strtotime($quiz->close_date)) : '-';
					$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'classes/'.$class_id.'/subject/'.$subject_id.'/quiz/edit/'.$quiz->id.'"><i class="icon-pencil"></i> Ubah</a> 
							<a class="btn btn-sm purple" href="'.base_url().'classes/'.$class_id.'/subject/'.$subject_id.'/quiz/result/'.$quiz->id.'"><i class="fa fa-bar-chart"></i> Hasil</a> ';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->class_quizes->count_all($conds),
								'recordsFiltered' => $this->class_quizes->count_filtered($conds),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$content['class_id'] = $class_id;
				$content['subject_id'] = $subject_id;
				$data = array(
						'title' => 'Latihan UN '.APP,
						'content' => $this->load->view('class/quiz_index', $content, true),
						'bc_header' => 'Belajar Mengajar',
						'bc_detail' => 'Latihan UN'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function quiz_add($class_id, $subject_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('class_subjects');
			$class_subject = $this->class_subjects->get_class_subject_id($class_id, $subject_id);
			$class_subject_id = $class_subject[0]['id'];

			$this->load->model('class_quizes');
			$content['quizes'] = $this->class_quizes->get_quizes($subject_id);
			$content['class_id'] = $class_id;
			$content['subject_id'] = $subject_id;
			$content['class_subject_id'] = $class_subject_id;
			$data = array(
					'title' => 'Tambah Latihan UN '.APP,
					'content' => $this->load->view('class/quiz_add', $content, true),
					'bc_header' => 'Belajar Mengajar',
					'bc_detail' => 'Latihan UN'
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

	public function quiz_insert(){
		$class_subject_id = $this->security->xss_clean($this->input->post('class_subject_id'));
		$quiz_id = $this->security->xss_clean($this->input->post('quiz_id'));
		$open_date = $this->security->xss_clean($this->input->post('open_date'));
		$close_date = $this->security->xss_clean($this->input->post('close_date'));

		$this->load->model('class_quizes');

		$this->db->trans_begin();

		$class_quizes = $this->class_quizes->insert($class_subject_id, $quiz_id, $open_date, $close_date);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, latihan UN kelas gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function quiz_edit($class_id, $subject_id, $id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('class_quizes');
			$check_id = $this->class_quizes->check_id($id);

			if($check_id){
				$content['quizes'] = $this->class_quizes->get_quizes($subject_id);
				$content['class_id'] = $class_id;
				$content['subject_id'] = $subject_id;
				$content['data'] = $this->class_quizes->get_class_quizes($id);
				$data = array(
						'title' => 'Edit Latihan UN '.APP,
						'content' => $this->load->view('class/quiz_edit', $content, true),
						'bc_header' => 'Materi Belajar',
						'bc_detail' => 'Latihan UN'
				);
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
				$this->session->set_flashdata('error',$msg);
				redirect('subject/'.$subject_id.'/quiz');
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

	public function quiz_update(){
		$id = $this->security->xss_clean($this->input->post('id'));
		$class_subject_id = $this->security->xss_clean($this->input->post('class_subject_id'));
		$quiz_id = $this->security->xss_clean($this->input->post('quiz_id'));
		$open_date = $this->security->xss_clean($this->input->post('open_date'));
		$close_date = $this->security->xss_clean($this->input->post('close_date'));

		$this->load->model('class_quizes');

		$check_id = $this->class_quizes->check_id($id);
		if($check_id){
			$this->db->trans_begin();

			$quiz = $this->class_quizes->update($id, $class_subject_id, $quiz_id, $open_date, $close_date);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    echo 'Maaf, latihan UN gagal diedit';
			} else {
				$this->db->trans_commit();
			  	echo 0;
			}
		}else{
			echo 'Maaf, edit latihan UN tidak diizinkan';
		}
	}
}