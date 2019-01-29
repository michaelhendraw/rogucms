<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Materials extends CI_Model{

	var $table = 'public.material m';
	var $column_order = array('m.name','t.name','s.name','m.sequence');
	var $column_search = array('m.name','t.name','s.name','m.sequence');
	var $order = array('m.sequence' => 'asc','m.name' => 'asc');

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($conds=null){
		$this->db->select("m.id, m.name, t.id topic_id, t.name topic_name, s.id subject_id, s.name subject_name, m.sequence");
		$this->db->from($this->table);
		$this->db->join('topic t', 'm.topic_id=t.id','left');
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
									FROM public.material
									WHERE name=".$this->db->escape($name)." ".$except);
		return $query->num_rows();
	}

	public function check_id($id){
		$query = $this->db->query("	SELECT id
									FROM public.material
									WHERE id=".$this->db->escape($id));
		return $query->num_rows();
	}
	
	public function get_materials(){
		$query = $this->db->query("	SELECT m.id, m.name, m.description, m.topic_id, t.subject_id, m.sequence
									FROM public.material m
									LEFT JOIN public.topic t ON m.topic_id = t.id");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_material($id){
		$query = $this->db->query("	SELECT m.id, m.name, m.description, m.topic_id, t.subject_id, m.sequence
									FROM public.material m
									LEFT JOIN public.topic t ON m.topic_id = t.id
									WHERE
										m.id=".$this->db->escape($id));
		return $query->row_array();
	}

	public function insert($name, $description, $topic_id, $sequence){
		$this->db->query('	INSERT INTO public.material(name, description, topic_id, sequence)
							VALUES ('.
								$this->db->escape($name).','.
								$this->db->escape($description).','.
								$this->db->escape($topic_id).','.
								$this->db->escape($sequence).
							')');
		return $this->db->insert_id();
	}

	public function update($id, $name, $description, $topic_id, $sequence){
		$this->db->query('	UPDATE public.material
							SET name='.$this->db->escape($name).',
								description='.$this->db->escape($description).',
								topic_id='.$this->db->escape($topic_id).',
								sequence='.$this->db->escape($sequence).'
							WHERE id='.$this->db->escape($id));
	}

	public function delete($id) {
		$this->db->query('	DELETE FROM public.material
							WHERE id='.$this->db->escape($id));
	}
}