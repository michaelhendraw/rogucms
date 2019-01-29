<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subject extends CI_Controller {

	public $quiz_statuses = array(
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

	// Subject (Mapel)
	public function index(){
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			if($this->input->is_ajax_request()){
				$this->load->model('subjects');
				$subjects = $this->subjects->get_datatables();
				$data = array();
				$no = $_POST['start'];
				foreach ($subjects as $subject) {
					$no++;
					$row = array();
					$row[] = $subject->name;
					$row[] = '<a href="'.base_url().'uploads/subject/'.$subject->image.'?t='.time().'" target="_blank"><div class="jobseeker-img-div-profile"><img class="jobseeker-img-profile" src="'.base_url().'uploads/subject/'.$subject->image.'?t='.time().'" alt="'.$subject->name.'"></div><a/>';
					$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'subject/edit/'.$subject->id.'"><i class="icon-pencil"></i> Ubah</a> 
							<a class="btn btn-sm btn-success" href="'.base_url().'subject/'.$subject->id.'/topic"><i class="fa fa-book"></i> Topik</a>
							<a class="btn btn-sm purple" href="'.base_url().'subject/'.$subject->id.'/quiz"><i class="fa fa-files-o"></i> Latihan UN</a>
							<form action="'.base_url().'subject/delete/'.$subject->id.'" method="POST" style="float: right;"><button class="btn btn-sm btn-danger" onclick="if(confirm(&#39;Apakah Anda yakin untuk menghapus mapel #'.$subject->name.'?&#39;)) return true; else return false;"><i class="icon-trash"></i> Hapus</button></form>';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->subjects->count_all(),
								'recordsFiltered' => $this->subjects->count_filtered(),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$data = array(
						'title' => 'Mapel '.APP,
						'content' => $this->load->view('subject/index', null, true),
						'bc_header' => 'Materi Belajar',
						'bc_detail' => 'Mapel'
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
		$this->load->model('subjects');
		echo $this->subjects->check_name($name,$id);
	}

	public function add() {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$data = array(
					'title' => 'Tambah Mapel '.APP,
					'content' => $this->load->view('subject/add', null, true),
					'bc_header' => 'Materi Belajar',
					'bc_detail' => 'Mapel'
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

		$this->load->model('subjects');

		$check_name = $this->subjects->check_name($name);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$this->db->trans_begin();

		$subject = $this->subjects->insert($name);

		$image_name = $subject.'-'.str_replace(' ', '_', $name).date('YmdHis');
		$config = array(
					'upload_path' 	=> './uploads/subject/',
					'allowed_types' => 'png|jpg|jpeg',
					'max_size' 		=> 20480000,
					'file_name' 	=> $image_name,
				);
        $this->load->library('upload', $config);
        if(!isset($_FILES['image'])){
			echo 'Maaf, image harus dipilih';
        }

        if(!$this->upload->do_upload('image')) {
			echo $this->upload->display_errors();
			echo 'Maaf, gambar gagal diupload.<br/>Gambar tidak sesuai dengan format dan ukuran yang ditentukan.'; exit;
        } else {
			$image_upload = $this->upload->data();
			$image_ext = $image_upload['file_ext'];
			$image = $image_name.$image_ext;
			$this->subjects->update($subject,$name,$image);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, mapel gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function edit($id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('subjects');
			$check_id = $this->subjects->check_id($id);

			if($check_id){
				$content['data'] = $this->subjects->get_subject($id);
				$data = array(
						'title' => 'Edit Mapel '.APP,
						'content' => $this->load->view('subject/edit', $content, true),
						'bc_header' => 'Materi Belajar',
						'bc_detail' => 'Mapel'
				);
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
				$this->session->set_flashdata('error',$msg);
				redirect('subject');
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

		$this->load->model('subjects');

		$check_name = $this->subjects->check_name($name,$id);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$check_id = $this->subjects->check_id($id);
		if($check_id){
			$data = $this->subjects->get_subject($id);

			if(isset($_FILES['image'])){
				$image_name = $data['id'].'-'.str_replace(' ', '_', $name).date('YmdHis');
				$config = array(
							'upload_path' 	=> './uploads/subject/',
							'allowed_types' => 'png|jpg|jpeg',
							'max_size' 		=> 20480000,
							'file_name' 	=> $image_name,
						);
		        $this->load->library('upload', $config);

		        if(!$this->upload->do_upload('image')) {
					echo $this->upload->display_errors();
					echo 'Maaf, gambar gagal diupload.<br/>Gambar tidak sesuai dengan format dan ukuran yang ditentukan.'; exit;
		        } else {
		        	$image_upload = $this->upload->data();
					$image_ext = $image_upload['file_ext'];
					$image = $image_name.$image_ext;
		        }
		    }else{
		    	$image = $data['image'];
		    }

			$this->db->trans_begin();

			$subject = $this->subjects->update($id, $name, $image);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    echo 'Maaf, mapel gagal diedit';
			} else {
				$this->db->trans_commit();
				if(isset($_FILES['image'])){
					unlink(DIR.'uploads/subject/'.$data['image']);
			   	}
			  	echo 0;
			}
		}else{
			echo 'Maaf, edit mapel tidak diizinkan';
		}
	}

	public function delete($id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('subjects');
			$check_id = $this->subjects->check_id($id);

			if($check_id){
				$data = $this->subjects->get_subject($id);

				$this->db->trans_begin();

				$subject = $this->subjects->delete($id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
				    $this->db->trans_rollback();
				    
				    $msg = array(
						'title' => '<h4>Mapel Gagal Dihapus!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
					$this->session->set_flashdata('error',$msg);
					redirect('subject');
				} else {
					$this->db->trans_commit();
					unlink(DIR.'uploads/subject/'.$data['image']);
				  	
				  	$msg = array(
						'title' => '<h4>Mapel Berhasil Dihapus!</h4>',
						'text' => 'Selamat, mapel berhasil dihapus.'
					);
					$this->session->set_flashdata('success',$msg);
					redirect('subject');
				}
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan',
					);
				redirect('subject');
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

	// Topic (Topik)
	public function topic_index($subject_id) {
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
					$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'subject/'.$subject_id.'/topic/edit/'.$topic->id.'"><i class="icon-pencil"></i> Ubah</a> 
							<a class="btn btn-sm btn-success" href="'.base_url().'subject/'.$subject_id.'/topic/'.$topic->id.'/material"><i class="fa fa-book"></i> Materi</a>
							<form action="'.base_url().'subject/'.$subject_id.'/topic/delete/'.$topic->id.'" method="POST" style="float: right;"><button class="btn btn-sm btn-danger" onclick="if(confirm(&#39;Apakah Anda yakin untuk menghapus topik #'.$topic->name.'?&#39;)) return true; else return false;"><i class="icon-trash"></i> Hapus</button></form>';
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
				$content['subject_id'] = $subject_id;
				$data = array(
						'title' => 'Topik '.APP,
						'content' => $this->load->view('topic/index', $content, true),
						'bc_header' => 'Materi Belajar',
						'bc_detail' => 'Topik'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function topic_check_name(){
		$name = $this->input->post('name');
		if($this->input->post('id')!=''){
			$id = $this->input->post('id');
		}else{
			$id = 0;
		}
		$this->load->model('topics');
		echo $this->topics->check_name($name,$id);
	}

	public function topic_add($subject_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('subjects');
			$content['subjects'] = $this->subjects->get_subjects();
			$content['subject_id'] = $subject_id;
			$data = array(
					'title' => 'Tambah Topik '.APP,
					'content' => $this->load->view('topic/add', $content, true),
					'bc_header' => 'Materi Belajar',
					'bc_detail' => 'Topik'
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

	public function topic_insert(){
		$name = $this->security->xss_clean($this->input->post('name'));
		$subject_id = $this->security->xss_clean($this->input->post('subject_id'));
		$sequence = $this->security->xss_clean($this->input->post('sequence'));

		$this->load->model('topics');

		$check_name = $this->topics->check_name($name);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$this->db->trans_begin();

		$topic = $this->topics->insert($name,$subject_id,$sequence);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, topik gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function topic_edit($subject_id, $id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('topics');
			$check_id = $this->topics->check_id($id);

			if($check_id){
				$this->load->model('subjects');
				$content['subjects'] = $this->subjects->get_subjects();
				$content['subject_id'] = $subject_id;
				$content['data'] = $this->topics->get_topic($id);
				$data = array(
						'title' => 'Edit Topik '.APP,
						'content' => $this->load->view('topic/edit', $content, true),
						'bc_header' => 'Materi Belajar',
						'bc_detail' => 'Topik'
				);
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
				$this->session->set_flashdata('error',$msg);
				redirect('subject/'.$subject_id.'/topic');
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

	public function topic_update(){
		$id = $this->security->xss_clean($this->input->post('id'));
		$name = $this->security->xss_clean($this->input->post('name'));
		$subject_id = $this->security->xss_clean($this->input->post('subject_id'));
		$sequence = $this->security->xss_clean($this->input->post('sequence'));

		$this->load->model('topics');

		$check_name = $this->topics->check_name($name,$id);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$check_id = $this->topics->check_id($id);
		if($check_id){
			$data = $this->topics->get_topic($id);

			$this->db->trans_begin();

			$topic = $this->topics->update($id, $name, $subject_id, $sequence);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    echo 'Maaf, topik gagal diedit';
			} else {
				$this->db->trans_commit();
			  	echo 0;
			}
		}else{
			echo 'Maaf, edit topik tidak diizinkan';
		}
	}

	public function topic_delete($subject_id, $id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('topics');
			$check_id = $this->topics->check_id($id);

			if($check_id){
				$data = $this->topics->get_topic($id);

				$this->db->trans_begin();

				$topic = $this->topics->delete($id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
				    $this->db->trans_rollback();
				    
				    $msg = array(
						'title' => '<h4>Topik Gagal Dihapus!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
					$this->session->set_flashdata('error',$msg);
					redirect('subject/'.$subject_id.'/topic');
				} else {
					$this->db->trans_commit();
				  	
				  	$msg = array(
						'title' => '<h4>Topik Berhasil Dihapus!</h4>',
						'text' => 'Selamat, topik berhasil dihapus.'
					);
					$this->session->set_flashdata('success',$msg);
					redirect('subject/'.$subject_id.'/topic');
				}
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan',
					);
				redirect('subject/'.$subject_id.'/topic');
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

	// Material (Materi)
	public function material_index($subject_id, $topic_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			if($this->input->is_ajax_request()){
				$conds = 'topic_id='.$topic_id;
				$this->load->model('materials');
				$materials = $this->materials->get_datatables($conds);
				$data = array();
				$no = $_POST['start'];
				foreach ($materials as $material) {
					$no++;
					$row = array();
					$row[] = $material->name;
					$row[] = $material->topic_name;
					$row[] = $material->subject_name;
					$row[] = $material->sequence;
					$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'subject/'.$subject_id.'/topic/'.$topic_id.'/material/edit/'.$material->id.'"><i class="icon-pencil"></i> Ubah</a> 
							<form action="'.base_url().'subject/'.$subject_id.'/topic/'.$topic_id.'/material/delete/'.$material->id.'" method="POST" style="float: right;"><button class="btn btn-sm btn-danger" onclick="if(confirm(&#39;Apakah Anda yakin untuk menghapus materi #'.$material->name.'?&#39;)) return true; else return false;"><i class="icon-trash"></i> Hapus</button></form>';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->materials->count_all($conds),
								'recordsFiltered' => $this->materials->count_filtered($conds),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$content['subject_id'] = $subject_id;
				$content['topic_id'] = $topic_id;
				$data = array(
						'title' => 'Materi '.APP,
						'content' => $this->load->view('material/index', $content, true),
						'bc_header' => 'Materi Belajar',
						'bc_detail' => 'Materi'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function material_check_name(){
		$name = $this->input->post('name');
		if($this->input->post('id')!=''){
			$id = $this->input->post('id');
		}else{
			$id = 0;
		}
		$this->load->model('materials');
		echo $this->materials->check_name($name,$id);
	}

	public function material_add($subject_id, $topic_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('topics');
			$content['topics'] = $this->topics->get_topics();
			$content['topic_id'] = $topic_id;
			$this->load->model('subjects');
			$content['subjects'] = $this->subjects->get_subjects();
			$content['subject_id'] = $subject_id;
			$data = array(
					'title' => 'Tambah Materi '.APP,
					'content' => $this->load->view('material/add', $content, true),
					'bc_header' => 'Materi Belajar',
					'bc_detail' => 'Materi'
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

	public function material_insert(){
		$name = $this->security->xss_clean($this->input->post('name'));
		$description = $this->security->xss_clean($this->input->post('description'));
		$topic_id = $this->security->xss_clean($this->input->post('topic_id'));
		$sequence = $this->security->xss_clean($this->input->post('sequence'));

		$this->load->model('materials');

		$check_name = $this->materials->check_name($name);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$this->db->trans_begin();

		$topic = $this->materials->insert($name,$description,$topic_id,$sequence);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, materi gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function material_edit($subject_id, $topic_id, $id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('materials');
			$check_id = $this->materials->check_id($id);

			if($check_id){
				$this->load->model('topics');
				$content['topics'] = $this->topics->get_topics();
				$content['topic_id'] = $topic_id;
				$this->load->model('subjects');
				$content['subjects'] = $this->subjects->get_subjects();
				$content['subject_id'] = $subject_id;
				$content['data'] = $this->materials->get_material($id);
				$data = array(
						'title' => 'Edit Materi '.APP,
						'content' => $this->load->view('material/edit', $content, true),
						'bc_header' => 'Materi Belajar',
						'bc_detail' => 'Materi'
				);
				$this->load->view('template/main', $data);
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
				$this->session->set_flashdata('error',$msg);
				redirect('subject/'.$subject_id.'/topic/'.$topic_id);
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

	public function material_update(){
		$id = $this->security->xss_clean($this->input->post('id'));
		$name = $this->security->xss_clean($this->input->post('name'));
		$description = $this->security->xss_clean($this->input->post('description'));
		$topic_id = $this->security->xss_clean($this->input->post('topic_id'));
		$sequence = $this->security->xss_clean($this->input->post('sequence'));

		$this->load->model('materials');

		$check_name = $this->materials->check_name($name,$id);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$check_id = $this->materials->check_id($id);
		if($check_id){
			$data = $this->materials->get_material($id);

			$this->db->trans_begin();

			$material = $this->materials->update($id, $name, $description, $topic_id, $sequence);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    echo 'Maaf, materi gagal diedit';
			} else {
				$this->db->trans_commit();
			  	echo 0;
			}
		}else{
			echo 'Maaf, edit materi tidak diizinkan';
		}
	}

	public function material_delete($subject_id, $topic_id, $id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('materials');
			$check_id = $this->materials->check_id($id);

			if($check_id){
				$data = $this->materials->get_material($id);

				$this->db->trans_begin();

				$material = $this->materials->delete($id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
				    $this->db->trans_rollback();
				    
				    $msg = array(
						'title' => '<h4>Materi Gagal Dihapus!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
					$this->session->set_flashdata('error',$msg);
					redirect('subject/'.$subject_id.'/topic/'.$topic_id.'/material');
				} else {
					$this->db->trans_commit();
				  	
				  	$msg = array(
						'title' => '<h4>Materi Berhasil Dihapus!</h4>',
						'text' => 'Selamat, materi berhasil dihapus.'
					);
					$this->session->set_flashdata('success',$msg);
					redirect('subject/'.$subject_id.'/topic/'.$topic_id.'/material');
				}
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan',
					);
					redirect('subject/'.$subject_id.'/topic/'.$topic_id).'/material';
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
	
	// Quiz (Latihan UN)
	public function quiz_index($subject_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			if($this->input->is_ajax_request()){
				$conds = 'subject_id='.$subject_id;
				$this->load->model('quizes');
				$quizes = $this->quizes->get_datatables($conds);
				$data = array();
				$no = $_POST['start'];
				foreach ($quizes as $quiz) {
					$no++;
					$row = array();
					$row[] = $quiz->name;
					$row[] = $quiz->subject_name;
					if($quiz->status==1){
						$row[] = '<span class="label label-sm label-success">Aktif</span>';
					}elseif($quiz->status==0){
						$row[] = '<span class="label label-sm label-danger">Non Aktif</span>';
					}
					$row[] = ($quiz->created_at != '') ? date('d F Y H:i:s', strtotime($quiz->created_at)) : '-';
					$row[] = ($quiz->modified_at != '') ? date('d F Y H:i:s', strtotime($quiz->modified_at)) : '-';
					$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url().'subject/'.$subject_id.'/quiz/edit/'.$quiz->id.'"><i class="icon-pencil"></i> Ubah</a> 
							<form action="'.base_url().'subject/'.$subject_id.'/quiz/delete/'.$quiz->id.'" method="POST" style="float: right;"><button class="btn btn-sm btn-danger" onclick="if(confirm(&#39;Apakah Anda yakin untuk menghapus latihan UN #'.$quiz->name.'?&#39;)) return true; else return false;"><i class="icon-trash"></i> Hapus</button></form>';
					$data[] = $row;
				}

				$output = array(
								'draw' => $_POST['draw'],
								'recordsTotal' => $this->quizes->count_all($conds),
								'recordsFiltered' => $this->quizes->count_filtered($conds),
								'data' => $data,
						);
				echo json_encode($output);
			}else{
				$content['subject_id'] = $subject_id;
				$data = array(
						'title' => 'Latihan UN '.APP,
						'content' => $this->load->view('quiz/index', $content, true),
						'bc_header' => 'Materi Belajar',
						'bc_detail' => 'Latihan UN'
				);
				$this->load->view('template/main', $data);
			}
		}else{
			redirect();
		}
	}

	public function quiz_check_name(){
		$name = $this->input->post('name');
		if($this->input->post('id')!=''){
			$id = $this->input->post('id');
		}else{
			$id = 0;
		}
		$this->load->model('quizes');
		echo $this->quizes->check_name($name,$id);
	}

	public function quiz_add($subject_id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('subjects');
			$content['subjects'] = $this->subjects->get_subjects();
			$content['subject_id'] = $subject_id;
			$content['quiz_statuses'] = $this->quiz_statuses;
			$data = array(
					'title' => 'Tambah Latihan UN '.APP,
					'content' => $this->load->view('quiz/add', $content, true),
					'bc_header' => 'Materi Belajar',
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
		$name = $this->security->xss_clean($this->input->post('name'));
		$description = $this->security->xss_clean($this->input->post('description'));
		$subject_id = $this->security->xss_clean($this->input->post('subject_id'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$this->load->model('quizes');

		$check_name = $this->quizes->check_name($name);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$this->db->trans_begin();

		$quiz_id = $this->quizes->insert($name, $description, $subject_id, $status);

		$details = $this->input->post('detail');
		foreach ($details as $kd => $vd) {
			$question = $this->security->xss_clean($vd['question']);
			$solution = $this->security->xss_clean($vd['solution']);
			$material_id = $this->security->xss_clean($vd['material_id']);

			$answers = $vd['answer'];
			$correct_answer = $answers[$vd['correct_answer']];

			$quiz_detail_id = $this->quizes->insert_detail($quiz_id, $question, $correct_answer, $solution, $material_id);
			
			foreach ($answers as $ka => $va) {
				$answer =  $this->security->xss_clean($va);

				$quiz_answer_id = $this->quizes->insert_answer($quiz_detail_id, $answer);
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
		    echo 'Maaf, latihan UN gagal ditambah';
		} else {
		    $this->db->trans_commit();
		  	echo 0;
		}
	}

	public function quiz_edit($subject_id, $id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('quizes');
			$check_id = $this->quizes->check_id($id);

			if($check_id){
				$this->load->model('subjects');
				$content['subjects'] = $this->subjects->get_subjects();
				$content['subject_id'] = $subject_id;
				$content['quiz_statuses'] = $this->quiz_statuses;
				$content['data'] = $this->quizes->get_quiz($id);
				$content['details'] = $this->quizes->get_quiz_details($id);
				foreach($content['details'] as $kd => $vd) {
					$answers = $this->quizes->get_quiz_answers($vd['id']);
					foreach($answers as $ka => $va) {
						$content['details'][$kd]['answer'][$ka]['id'] = $va['id'];
						$content['details'][$kd]['answer'][$ka]['quiz_detail_id'] = $va['quiz_detail_id'];
						$content['details'][$kd]['answer'][$ka]['answer'] = $va['answer'];
					}
				}
				$data = array(
						'title' => 'Edit Latihan UN '.APP,
						'content' => $this->load->view('quiz/edit', $content, true),
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
		$name = $this->security->xss_clean($this->input->post('name'));
		$description = $this->security->xss_clean($this->input->post('description'));
		$subject_id = $this->security->xss_clean($this->input->post('subject_id'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$this->load->model('quizes');

		$check_name = $this->quizes->check_name($name,$id);
		if($check_name!=0){
			echo 'Maaf, nama sudah ada'; exit;
		}

		$check_id = $this->quizes->check_id($id);
		if($check_id){
			$this->db->trans_begin();

			$quiz = $this->quizes->update($id, $name, $description, $subject_id, $status);

			// quiz_detail
			$existingDetails = $this->quizes->get_quiz_details($id);
			$editDetails=array();
			$details = $this->input->post('detail');
			foreach ($details as $kd => $vd) {
				$question = $this->security->xss_clean($vd['question']);
				$solution = $this->security->xss_clean($vd['solution']);
				$material_id = $this->security->xss_clean($vd['material_id']);

				$answers = $vd['answer'];
				$correct_answer = $answers[$vd['correct_answer']];
				
				if(!empty($vd['id'])){ // update quiz_detail
					$quiz_detail_id = $this->security->xss_clean($vd['id']);

					$this->quizes->update_detail($quiz_detail_id, $id, $question, $correct_answer, $solution, $material_id);

					$editDetails[$quiz_detail_id]=$quiz_detail_id;
				}else{ // insert quiz_detail
					$quiz_detail_id = $this->quizes->insert_detail($id, $question, $correct_answer, $solution, $material_id);
				}

				// quiz_answer
				// delete old quiz_answer
				$this->quizes->delete_answer_by_detail($quiz_detail_id);

				// insert quiz_answer
				foreach ($answers as $ka => $va) {
					$answer = $this->security->xss_clean($va);
					$quiz_answer_id = $this->quizes->insert_answer($quiz_detail_id, $answer);
				}
			}

			// delete old quiz_detail
			foreach ($existingDetails as $detail) {
				if (!in_array($detail['id'], $editDetails)) {
					$this->quizes->delete_detail($detail['id']);
				}
			}

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

	public function quiz_delete($subject_id, $id) {
		if(in_array($this->session->userdata('user_role'), array('guru'))){
			$this->load->model('quizes');
			$check_id = $this->quizes->check_id($id);

			if($check_id){
				$data = $this->quizes->get_quiz($id);

				$this->db->trans_begin();

				$quiz = $this->quizes->delete($id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
				    $this->db->trans_rollback();
				    
				    $msg = array(
						'title' => '<h4>Latihan UN Gagal Dihapus!</h4>',
						'text' => 'Maaf, data tidak ditemukan'
					);
					$this->session->set_flashdata('error',$msg);
					redirect('subject/'.$subject_id.'/quiz');
				} else {
					$this->db->trans_commit();
				  	
				  	$msg = array(
						'title' => '<h4>Latihan UN Berhasil Dihapus!</h4>',
						'text' => 'Selamat, latihan UN berhasil dihapus.'
					);
					$this->session->set_flashdata('success',$msg);
					redirect('subject/'.$subject_id.'/quiz');
				}
			}else{
				$msg = array(
						'title' => '<h4>Tidak ditemukan!</h4>',
						'text' => 'Maaf, data tidak ditemukan',
					);
				redirect('subject/'.$subject_id.'/quiz');
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