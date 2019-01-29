<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Class_Subjects extends CI_Model{

	var $table = 'public.subject s';
	var $column_order = array('s.name','s.image');
	var $column_search = array('s.name','s.image');
	var $order = array('id' => 'desc');

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($conds=null){
		$this->db->select("s.id, s.name, s.image");
		$this->db->from($this->table);
		$this->db->join('class_subject cs', 'cs.subject_id=s.id','left');
		$this->db->group_by('s.id');
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

	public function get_class_subject_id($class_id, $subject_id){
		$query = $this->db->query("	SELECT id
									FROM class_subject 
									WHERE 
										class_id = ".$this->db->escape($class_id)." AND 
										subject_id = ".$this->db->escape($subject_id)." AND 
										user_id = ".$this->db->escape($this->session->userdata('user_id')));
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function check_class_discussion($class_id, $subject_id, $topic_id){
		$query = $this->db->query("	SELECT id
									FROM public.class_discussion cd
									WHERE
										class_subject_id IN (SELECT id FROM class_subject WHERE class_id = ".$this->db->escape($class_id)." AND subject_id = ".$this->db->escape($subject_id)." AND user_id = ".$this->db->escape($this->session->userdata('user_id')).")
										AND topic_id = ".$this->db->escape($topic_id));
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_discussion($class_discussion_id){
		$query = $this->db->query("	SELECT id, description, user_id, student_id, date
									FROM public.class_discussion_detail
									WHERE class_discussion_id = ".$this->db->escape($class_discussion_id));
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function insert_discussion($class_id, $subject_id, $topic_id){
		$query = $this->db->query("	SELECT id
									FROM class_subject
									WHERE
										class_id = ".$this->db->escape($class_id)." 
										AND subject_id = ".$this->db->escape($subject_id)." 
										AND user_id = ".$this->db->escape($this->session->userdata('user_id')));
		$class_subject = $query->row_array();

		$this->db->query('	INSERT INTO public.class_discussion(class_subject_id, topic_id)
							VALUES ('.
								$class_subject['id'].','.
								$this->db->escape($topic_id).
							')');
		return $this->db->insert_id();
	}

	public function insert_discussion_detail($class_discussion_id, $description){
		$this->db->query('	INSERT INTO public.class_discussion_detail(class_discussion_id, description, user_id, student_id, date)
							VALUES ('.
								$this->db->escape($class_discussion_id).','.
								$this->db->escape($description).','.
								$this->session->userdata('user_id').',
								0,'.
								$this->db->escape(date('Y-m-d H:i:s')).
							')');
		return $this->db->insert_id();
	}
}