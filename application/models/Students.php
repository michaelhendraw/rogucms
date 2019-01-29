<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Students extends CI_Model{

	var $table = 'public.student';
	var $column_order = array('nisn','name','email','status','last_login');
	var $column_search = array('nisn','name','email','status','last_login');
	var $order = array('id' => 'desc');

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($conds=null){
		$this->db->select("id, nisn, name, email, status, last_login");
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

	public function check_nisn($nisn,$id=0){
		if($id!=0){
			$except = 'AND id<>'.$this->db->escape($id);
		}else{
			$except = '';
		}
		$query = $this->db->query("	SELECT nisn
									FROM public.student
									WHERE nisn=".$this->db->escape($nisn)." ".$except);
		return $query->num_rows();
	}

	public function check_email($email,$id=0){
		if($id!=0){
			$except = 'AND id<>'.$this->db->escape($id);
		}else{
			$except = '';
		}
		$query = $this->db->query("	SELECT email
									FROM public.student
									WHERE email=".$this->db->escape($email)." ".$except);
		return $query->num_rows();
	}

	public function check_id($id){
		$query = $this->db->query("	SELECT id
									FROM public.student
									WHERE id=".$this->db->escape($id));
		return $query->num_rows();
	}
	public function get_students(){
		$query = $this->db->query("	SELECT id, nisn, name, email, pob, dob, gender, status, last_login, created_by, created_at, modified_by, modified_at
									FROM public.student");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_student($id){
		$query = $this->db->query("	SELECT id, nisn, name, email, pob, dob, gender, class_id, status, last_login, created_by, created_at, modified_by, modified_at
									FROM public.student
									WHERE
										id=".$this->db->escape($id));
		return $query->row_array();
	}

	public function insert($nisn, $email, $name, $pob, $dob, $gender, $class_id, $status){
		$this->db->query('	INSERT INTO public.student(nisn, email, name, pob, dob, gender, class_id, status, created_by, created_at, modified_by, modified_at)
							VALUES ('.
								$this->db->escape($nisn).','.
								$this->db->escape($email).','.
								$this->db->escape($name).','.
								$this->db->escape($pob).','.
								$this->db->escape($dob).','.
								$this->db->escape($gender).','.
								$this->db->escape($class_id).','.
								$this->db->escape($status).','.
								$this->session->userdata('user_id').','.
								$this->db->escape(date('Y-m-d H:i:s')).','.
								$this->session->userdata('user_id').','.
								$this->db->escape(date('Y-m-d H:i:s')).
							')');
		return $this->db->insert_id();
	}

	public function update($id, $nisn, $email, $name, $pob, $dob, $gender, $class_id, $status){
		$this->db->query('	UPDATE public.student
							SET nisn='.$this->db->escape($nisn).',
								email='.$this->db->escape($email).',
								name='.$this->db->escape($name).',
								pob='.$this->db->escape($pob).',
								dob='.$this->db->escape($dob).',
								gender='.$this->db->escape($gender).',
								class_id='.$this->db->escape($class_id).',
								status='.$this->db->escape($status).',
								modified_by='.$this->session->userdata('user_id').',
								modified_at='.$this->db->escape(date('Y-m-d H:i:s')).'
							WHERE id='.$this->db->escape($id));
	}

	public function delete($id) {
		$this->db->query('	DELETE FROM public.student
							WHERE id='.$this->db->escape($id));
	}
}