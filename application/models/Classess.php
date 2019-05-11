<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classess extends CI_Model{

	var $table = 'public.class c';
	var $column_order = array('c.name','c.status');
	var $column_search = array('c.name','c.status');
	var $order = array('c.id' => 'desc');

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($conds=null){
		$this->db->select("c.id, c.name, c.status");
		$this->db->from($this->table);
		$this->db->join('class_subject cs','c.id=cs.class_id','left');
		$this->db->group_by('c.id');
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

	public function check_name($name,$id=0){
		if($id!=0){
			$except = 'AND id<>'.$this->db->escape($id);
		}else{
			$except = '';
		}
		$query = $this->db->query("	SELECT name
									FROM public.class
									WHERE name=".$this->db->escape($name)." ".$except);
		
		return $query->num_rows();
	}

	public function check_id($id){
		$query = $this->db->query("	SELECT id
									FROM public.class
									WHERE id=".$this->db->escape($id));
		return $query->num_rows();
	}
	
	public function get_classess(){
		$query = $this->db->query("	SELECT id, name, status
									FROM public.class");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_class($id){
		$query = $this->db->query("	SELECT id, name, status
									FROM public.class
									WHERE
										id=".$this->db->escape($id));
		return $query->row_array();
	}

	public function get_class_subject($id){
		$query = $this->db->query("	SELECT id, class_id, subject_id, user_id
									FROM public.class_subject
									WHERE
										class_id=".$this->db->escape($id));
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_class_student($id){
		$query = $this->db->query("	SELECT id, name
									FROM public.student
									WHERE
										class_id=".$this->db->escape($id));
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function insert($name, $status){
		$this->db->query('	INSERT INTO public.class(name, status)
							VALUES ('.
								$this->db->escape($name).','.
								$this->db->escape($status).
							')');
		return $this->db->insert_id();
	}

	public function insert_subject($class_id, $subject_id, $user_id){
		$this->db->query('	INSERT INTO public.class_subject(class_id, subject_id, user_id)
							VALUES ('.
								$this->db->escape($class_id).','.
								$this->db->escape($subject_id).','.
								$this->db->escape($user_id).
							')');
		return $this->db->insert_id();
	}

	public function update($id, $name, $status){
		$this->db->query('	UPDATE public.class
							SET name='.$this->db->escape($name).',
								status='.$this->db->escape($status).'
							WHERE id='.$this->db->escape($id));
	}

	public function update_subject($id, $class_id, $subject_id, $user_id){
		$this->db->query('	UPDATE public.class_subject
							SET class_id='.$this->db->escape($class_id).',
								subject_id='.$this->db->escape($student_id).',
								user_id='.$this->db->escape($user_id).'
							WHERE id='.$this->db->escape($id));
	}

	public function update_student($class_id, $student_id){
		$this->db->query('	UPDATE public.student
							SET class_id='.$this->db->escape($class_id).'
							WHERE id='.$this->db->escape($student_id));
	}

	public function delete($id) {
		$this->db->query('	DELETE FROM public.class
							WHERE id='.$this->db->escape($id));
		$this->db->query('	DELETE FROM public.class_subject
							WHERE class_id='.$this->db->escape($id));
	}

	public function delete_subject($id) {
		$this->db->query('	DELETE FROM public.class_subject
							WHERE id='.$this->db->escape($id));
	}

	public function get_student_log($student_id, $date){
		$query = $this->db->query("	SELECT TO_CHAR(date::DATE,'yyyy-MM-dd') date, type, COUNT(id) count
									FROM student_log
									WHERE student_id=".$this->db->escape($student_id)."
									AND TO_CHAR(date::DATE,'yyyy-MM') = ".$this->db->escape($date)."
									GROUP BY TO_CHAR(date::DATE,'yyyy-MM-dd'), type
									ORDER BY TO_CHAR(date::DATE,'yyyy-MM-dd'), type");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}
}