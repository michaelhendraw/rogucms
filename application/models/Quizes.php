<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quizes extends CI_Model{

	var $table = 'public.quiz q';
	var $column_order = array('q.name','s.name','q.status','q.created_at','q.modified_at');
	var $column_search = array('q.name','s.name','q.status','q.created_at','q.modified_at');
	var $order = array('q.id' => 'desc');

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($conds=null){
		$this->db->select("q.id, q.name, q.description, s.id subject_id, s.name subject_name, q.status, q.created_at, q.modified_at");
		$this->db->from($this->table);
		$this->db->join('subject s', 'q.subject_id=s.id','left');
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
									FROM public.quiz
									WHERE name=".$this->db->escape($name)." ".$except);
		return $query->num_rows();
	}

	public function check_id($id){
		$query = $this->db->query("	SELECT id
									FROM public.quiz
									WHERE id=".$this->db->escape($id));
		return $query->num_rows();
	}
	
	public function get_quizes(){
		$query = $this->db->query("	SELECT id, name, description, subject_id, status
									FROM public.quiz");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_quiz($id){
		$query = $this->db->query("	SELECT id, name, description, subject_id, status
									FROM public.quiz
									WHERE
										id=".$this->db->escape($id));
		return $query->row_array();
	}

	public function get_quiz_details($quiz_id){
		$query = $this->db->query("	SELECT id, quiz_id, question, correct_answer, solution, material_id
									FROM public.quiz_detail
									WHERE
										quiz_id=".$this->db->escape($quiz_id));
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function get_quiz_answers($quiz_detail_id){
		$query = $this->db->query("	SELECT id, quiz_detail_id, answer
									FROM public.quiz_answer
									WHERE
										quiz_detail_id=".$this->db->escape($quiz_detail_id));
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function insert($name, $description, $subject_id, $status){
		$this->db->query('	INSERT INTO public.quiz(name, description, subject_id, status, created_by, created_at, modified_by, modified_at)
							VALUES ('.
								$this->db->escape($name).','.
								$this->db->escape($description).','.
								$this->db->escape($subject_id).','.
								$this->db->escape($status).','.
								$this->session->userdata('user_id').','.
								$this->db->escape(date('Y-m-d H:i:s')).','.
								$this->session->userdata('user_id').','.
								$this->db->escape(date('Y-m-d H:i:s')).
							')');
		return $this->db->insert_id();
	}

	public function insert_detail($quiz_id, $question, $correct_answer, $solution, $material_id){
		$this->db->query('	INSERT INTO public.quiz_detail(quiz_id, question, correct_answer, solution, material_id)
							VALUES ('.
								$this->db->escape($quiz_id).','.
								$this->db->escape($question).','.
								$this->db->escape($correct_answer).','.
								$this->db->escape($solution).','.
								$this->db->escape($material_id).
							')');
		return $this->db->insert_id();
	}

	public function insert_answer($quiz_detail_id, $answer){
		$this->db->query('	INSERT INTO public.quiz_answer(quiz_detail_id, answer)
							VALUES ('.
								$this->db->escape($quiz_detail_id).','.
								$this->db->escape($answer).
							')');
		return $this->db->insert_id();
	}

	public function update($id, $name, $description, $subject_id, $status){
		$this->db->query('	UPDATE public.quiz
							SET name='.$this->db->escape($name).',
								description='.$this->db->escape($description).',
								subject_id='.$this->db->escape($subject_id).',
								status='.$this->db->escape($status).',
								modified_by='.$this->session->userdata('user_id').',
								modified_at='.$this->db->escape(date('Y-m-d H:i:s')).'
							WHERE id='.$this->db->escape($id));
	}

	public function update_detail($id, $quiz_id, $question, $correct_answer, $solution, $material_id){
		$this->db->query('	UPDATE public.quiz_detail
							SET quiz_id='.$this->db->escape($quiz_id).',
								question='.$this->db->escape($question).',
								correct_answer='.$this->db->escape($correct_answer).',
								solution='.$this->db->escape($solution).',
								material_id='.$this->db->escape($material_id).'
							WHERE id='.$this->db->escape($id));
	}

	public function update_answer($id, $quiz_detail_id, $answer){
		$this->db->query('	UPDATE public.quiz_answer
							SET quiz_detail_id='.$this->db->escape($quiz_detail_id).',
								answer='.$this->db->escape($answer).'
							WHERE id='.$this->db->escape($id));
	}

	public function delete($id) {
		$this->db->query('	DELETE FROM public.quiz
							WHERE id='.$this->db->escape($id));
		$quiz_details = $this->get_quiz_details($id);
		$this->db->query('	DELETE FROM public.quiz_detail
							WHERE quiz_id='.$this->db->escape($id));
		foreach ($quiz_details as $quiz_detail) {
			$this->db->query('	DELETE FROM public.quiz_answer
								WHERE quiz_detail_id='.$this->db->escape($quiz_detail['id']));
		}
	}

	public function delete_detail($id) {
		$this->db->query('	DELETE FROM public.quiz_detail
							WHERE id='.$this->db->escape($id));
		$this->db->query('	DELETE FROM public.quiz_answer
							WHERE quiz_detail_id='.$this->db->escape($id));
	}

	public function delete_answer($id) {
		$this->db->query('	DELETE FROM public.quiz_answer
							WHERE id='.$this->db->escape($id));
	}

	public function delete_answer_by_detail($quiz_detail_id) {
		$this->db->query('	DELETE FROM public.quiz_answer
							WHERE quiz_detail_id='.$this->db->escape($quiz_detail_id));
	}
}