<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topics extends CI_Model{

	var $table = 'public.topic t';
	var $column_order = array('t.name','s.name','t.sequence');
	var $column_search = array('t.name','s.name','t.sequence');
	var $order = array('t.sequence' => 'asc','t.name' => 'asc');

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($conds=null){
		$this->db->select("t.id, t.name, s.id subject_id, s.name subject_name, t.sequence");
		$this->db->from($this->table);
		$this->db->join('subject s', 't.subject_id=s.id','left');
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
									FROM public.topic
									WHERE name=".$this->db->escape($name)." ".$except);
		return $query->num_rows();
	}

	public function check_id($id){
		$query = $this->db->query("	SELECT id
									FROM public.topic
									WHERE id=".$this->db->escape($id));
		return $query->num_rows();
	}
	
	public function get_topics(){
		$query = $this->db->query("	SELECT id, name, subject_id, sequence
									FROM public.topic");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_topic($id){
		$query = $this->db->query("	SELECT id, name, subject_id, sequence
									FROM public.topic
									WHERE
										id=".$this->db->escape($id));
		return $query->row_array();
	}

	public function insert($name, $subject_id, $sequence){
		$this->db->query('	INSERT INTO public.topic(name, subject_id, sequence)
							VALUES ('.
								$this->db->escape($name).','.
								$this->db->escape($subject_id).','.
								$this->db->escape($sequence).
							')');
		return $this->db->insert_id();
	}

	public function update($id, $name, $subject_id, $sequence){
		$this->db->query('	UPDATE public.topic
							SET name='.$this->db->escape($name).',
								subject_id='.$this->db->escape($subject_id).',
								sequence='.$this->db->escape($sequence).'
							WHERE id='.$this->db->escape($id));
	}

	public function delete($id) {
		$this->db->query('	DELETE FROM public.topic
							WHERE id='.$this->db->escape($id));
	}
}