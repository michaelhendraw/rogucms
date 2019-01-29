<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model{

	var $table = 'public.user';
	var $column_order = array('code','name','email','role','status','last_login');
	var $column_search = array('code','name','email','role','status','last_login');
	var $order = array('id' => 'desc');

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($conds=null){
		$this->db->select("id, code, name, email, role, status, last_login");
		$this->db->from($this->table);
		if($conds){
			$this->db->where($conds);
		}
		$i = 0;
		foreach ($this->column_search as $item){ // loop column
			if(isset($_POST['search']['value'])) {// if datatable send POST for search
				if($i===0) { // first loop				
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item,$_POST['search']['value']);
				}else{
					$this->db->or_like($item,$_POST['search']['value']);
				}

				if(count($this->column_search)-1==$i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])){ // here order processing
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}else if(isset($this->order)){
			$order = $this->order;
			$this->db->order_by(key($order),$order[key($order)]);
		}
	}

	public function get_datatables($conds=null) {
		$this->_get_datatables_query($conds);
		// if(!$conds){
			if($_POST['length']!=-1)
			$this->db->limit($_POST['length'],$_POST['start']);
		// }
		$query = $this->db->get();
		return $query->result();
	}

	public function count_filtered($conds=null) {
		$this->_get_datatables_query($conds);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($conds=null) {
		$this->_get_datatables_query($conds);
		return $this->db->count_all_results();
	}

	public function get_by_column($column='id',$id) {
		$this->db->from($this->table);
		$this->db->where($column, $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function check_code($code,$id=0){
		if($id!=0){
			$except = 'AND id<>'.$this->db->escape($id);
		}else{
			$except = '';
		}
		$query = $this->db->query("	SELECT code
									FROM public.user
									WHERE code=".$this->db->escape($code)." ".$except);
		return $query->num_rows();
	}

	public function check_email($email,$id=0){
		if($id!=0){
			$except = 'AND id<>'.$this->db->escape($id);
		}else{
			$except = '';
		}
		$query = $this->db->query("	SELECT email
									FROM public.user
									WHERE email=".$this->db->escape($email)." ".$except);
		return $query->num_rows();
	}

	public function check_id($id){
		$query = $this->db->query("	SELECT id
									FROM public.user
									WHERE id=".$this->db->escape($id));
		return $query->num_rows();
	}
	public function get_users(){
		$query = $this->db->query("	SELECT id, code, name, email, role, pob, dob, gender, status, last_login, created_by, created_at, modified_by, modified_at
									FROM public.user");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_user($id){
		$query = $this->db->query("	SELECT id, code, name, email, role, pob, dob, gender, status, last_login, created_by, created_at, modified_by, modified_at
									FROM public.user
									WHERE
										id=".$this->db->escape($id));
		return $query->row_array();
	}

	public function insert($code, $email, $password, $role, $name, $pob, $dob, $gender, $status){
		$this->db->query('	INSERT INTO public.user(code, email, password, role, name, pob, dob, gender, status, created_by, created_at, modified_by, modified_at)
							VALUES ('.
								$this->db->escape($code).','.
								$this->db->escape($email).','.
								$this->db->escape(md5(sha1($password))).','.
								$this->db->escape($role).','.
								$this->db->escape($name).','.
								$this->db->escape($pob).','.
								$this->db->escape($dob).','.
								$this->db->escape($gender).','.
								$this->db->escape($status).','.
								$this->session->userdata('user_id').','.
								$this->db->escape(date('Y-m-d H:i:s')).','.
								$this->session->userdata('user_id').','.
								$this->db->escape(date('Y-m-d H:i:s')).
							')');
		return $this->db->insert_id();
	}

	public function update($id, $code, $email, $password, $role, $name, $pob, $dob, $gender, $status){
		if($password==''){
			$this->db->query('	UPDATE public.user
								SET code='.$this->db->escape($code).',
									email='.$this->db->escape($email).',
									role='.$this->db->escape($role).',
									name='.$this->db->escape($name).',
									pob='.$this->db->escape($pob).',
									dob='.$this->db->escape($dob).',
									gender='.$this->db->escape($gender).',
									status='.$this->db->escape($status).',
									modified_by='.$this->session->userdata('user_id').',
									modified_at='.$this->db->escape(date('Y-m-d H:i:s')).'
								WHERE id='.$this->db->escape($id));
		}else{
			$this->db->query('	UPDATE public.user
								SET code='.$this->db->escape($code).',
									email='.$this->db->escape($email).',
									password='.$this->db->escape(md5(sha1($password))).',
									role='.$this->db->escape($role).',
									name='.$this->db->escape($name).',
									pob='.$this->db->escape($pob).',
									dob='.$this->db->escape($dob).',
									gender='.$this->db->escape($gender).',
									status='.$this->db->escape($status).',
									modified_by='.$this->session->userdata('user_id').',
									modified_at='.$this->db->escape(date('Y-m-d H:i:s')).'
								WHERE id='.$this->db->escape($id)
						);
		}
	}

	public function update_profile($id, $email, $password, $name, $pob, $dob, $gender){
		if($password==''){
			$this->db->query('	UPDATE public.user
								SET email='.$this->db->escape($email).',
									name='.$this->db->escape($name).',
									pob='.$this->db->escape($pob).',
									dob='.$this->db->escape($dob).',
									gender='.$this->db->escape($gender).',
									modified_by='.$this->session->userdata('user_id').',
									modified_at='.$this->db->escape(date('Y-m-d H:i:s')).'
								WHERE id='.$this->db->escape($id));
		}else{
			$this->db->query('	UPDATE public.user
								SET email='.$this->db->escape($email).',
									password='.$this->db->escape(md5(sha1($password))).',
									name='.$this->db->escape($name).',
									pob='.$this->db->escape($pob).',
									dob='.$this->db->escape($dob).',
									gender='.$this->db->escape($gender).',
									modified_by='.$this->session->userdata('user_id').',
									modified_at='.$this->db->escape(date('Y-m-d H:i:s')).'
								WHERE id='.$this->db->escape($id)
						);
		}
	}

	public function delete($id) {
		$this->db->query('	DELETE FROM public.user
							WHERE id='.$this->db->escape($id));
	}

	public function check_login($email, $password){
		$query = $this->db->query("	SELECT *
									FROM public.user
									WHERE
										email=".$this->db->escape($email)." AND 
										password=".$this->db->escape(md5(sha1($password)))." AND
										status=1");

		if($query->num_rows() > 0){
			$data = $query->row_array();
			$this->db->query('	UPDATE public.user
								SET last_login='.$this->db->escape(date('Y-m-d H:i:s')).'
								WHERE id='.$this->db->escape($data['id']));
			return $data;
		}else{
			return 0;
		}
	}
}