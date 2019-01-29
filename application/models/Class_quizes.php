<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Class_Quizes extends CI_Model{

	var $table = 'public.quiz q';
	var $column_order = array('q.name','s.name','cq.open_date','cq.close_date');
	var $column_search = array('q.name','s.name');
	var $order = array('cq.open_date' => 'desc');

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($conds=null){
		$this->db->select("q.id, q.name, s.id subject_id, s.name subject_name, cq.open_date, cq.close_date");
		$this->db->from($this->table);
		$this->db->join('subject s','q.subject_id=s.id','left');
		$this->db->join('class_quiz cq','q.id=cq.quiz_id','left');
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

	public function check_id($id){
		$query = $this->db->query("	SELECT id
									FROM public.class_quiz
									WHERE id=".$this->db->escape($id));
		return $query->num_rows();
	}
	
	public function get_quizes($subject_id){
		$query = $this->db->query("	SELECT id, name, description, subject_id, status
									FROM public.quiz
									WHERE
										subject_id=".$this->db->escape($subject_id)."
										AND status=1");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_class_quizes($id){
		$query = $this->db->query("	SELECT id, class_subject_id, quiz_id, open_date, close_date
									FROM public.class_quiz
									WHERE
										id=".$this->db->escape($id));
		return $query->row_array();
	}

	public function insert($class_subject_id, $quiz_id, $open_date, $close_date){
		$this->db->query('	INSERT INTO public.class_quiz(class_subject_id, quiz_id, open_date, close_date)
							VALUES ('.
								$this->db->escape($class_subject_id).','.
								$this->db->escape($quiz_id).','.
								$this->db->escape($open_date).','.
								$this->db->escape($close_date).
							')');
		return $this->db->insert_id();
	}

	public function update($id, $class_subject_id, $quiz_id, $open_date, $close_date){
		$this->db->query('	UPDATE public.class_quiz
							SET class_subject_id='.$this->db->escape($class_subject_id).',
								quiz_id='.$this->db->escape($quiz_id).',
								open_date='.$this->db->escape($open_date).',
								close_date='.$this->db->escape($close_date).'
							WHERE id='.$this->db->escape($id));
	}
}