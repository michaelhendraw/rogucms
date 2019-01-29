<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('pr')) {
	function pr($data) {		
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		exit;
	}
}

if (!function_exists('getColumnBy')) {
	function getColumnBy($column, $table, $where) {
	    $CI =& get_instance();

	    $CI->db->select($column);
		$CI->db->from($table);
  		$CI->db->where($where);
		$query = $CI->db->get();
		
		if ($query->num_rows() > 0) {
            $result= $query->result();
            return $result[0]->$column;
        }else{
        	return '-';
        }
	}
}

if (!function_exists('dateIndo')) {
	function dateIndo($data, $format='d $ Y', $separator=' ', $index_month=1) {
		$bulanIndo = array(
					'01' => 'Januari',
					'02' => 'Februai',
					'03' => 'Maret',
					'04' => 'April',
					'05' => 'Mei',
					'06' => 'Juni',
					'07' => 'Juli',
					'08' => 'Agustus',
					'09' => 'September',
					'10' => 'Oktober',
					'11' => 'November',
					'12' => 'Desember',
				);
		$date = explode($separator, date($format, strtotime($data)));
		$month = date('m', strtotime($data));
		$bulan = $bulanIndo[$month];
		$date = date($format, strtotime($data));
		return str_replace('$', $bulan, $date);
	}
}

if (!function_exists('loading')){
	function loading(){
		echo "<img src='".base_url()."assets/apps/img/loading.gif' />";
	}
}

if (!function_exists('getMaterialBySubject')) {
	function getMaterialBySubject($subject_id, $material_id = 0) {
		$result = '<option value="">--- Pilih Materi ---</option>';
	    $CI =& get_instance();
		$query_topic = $CI->db->query("	SELECT id, name
										FROM public.topic
										WHERE subject_id=".$CI->db->escape($subject_id)."
										ORDER BY sequence ASC
									");
		if($query_topic->num_rows() > 0){
			$topics = $query_topic->result_array();
			foreach($topics as $topic) {
				$result.= '<optgroup label="'.$topic['name'].'">';

				$query_material = $CI->db->query("	SELECT id, name
													FROM public.material
													WHERE topic_id=".$CI->db->escape($topic['id']));
				if($query_material->num_rows() > 0){
					$materials = $query_material->result_array();
					foreach($materials as $material) {
						$result.= '<option value="'.$material['id'].'" '.(($material_id==$material['id']) ? 'selected' : '').'>'.$material['name'].'</option>';
					}
				}

				$result.= '</optgroup>';
			}
		}

		return $result;
	}
}

if (!function_exists('getStudent')) {
	function getStudent($student_id = 0) {
		$result = '<option value="">--- Pilih Siswa ---</option>';
	    $CI =& get_instance();
		$query_student = $CI->db->query("	SELECT id, name, nisn
											FROM public.student
											ORDER BY name ASC
									");
		if($query_student->num_rows() > 0){
			$students = $query_student->result_array();
			foreach($students as $student) {
				$result.= '<option value="'.$student['id'].'" '.(($student_id==$student['id']) ? 'selected' : '').'>'.$student['name'].' ('.$student['nisn'].')'.'</option>';
			}
		}

		return $result;
	}
}

if (!function_exists('getSubject')) {
	function getSubject($subject_id = 0) {
		$result = '<option value="">--- Pilih Mapel ---</option>';
	    $CI =& get_instance();
		$query_subject = $CI->db->query("	SELECT id, name
											FROM public.subject
											ORDER BY name ASC
									");
		if($query_subject->num_rows() > 0){
			$subjects = $query_subject->result_array();
			foreach($subjects as $subject) {
				$result.= '<option value="'.$subject['id'].'" '.(($subject_id==$subject['id']) ? 'selected' : '').'>'.$subject['name'].'</option>';
			}
		}

		return $result;
	}
}

if (!function_exists('getTeacher')) {
	function getTeacher($user_id = 0) {
		$result = '<option value="">--- Pilih Guru ---</option>';
	    $CI =& get_instance();
		$query_user = $CI->db->query("	SELECT id, name, code
											FROM public.user
											WHERE role='guru' AND status=1
											ORDER BY name ASC
									");
		if($query_user->num_rows() > 0){
			$users = $query_user->result_array();
			foreach($users as $user) {
				$result.= '<option value="'.$user['id'].'" '.(($user_id==$user['id']) ? 'selected' : '').'>'.$user['name'].' ('.$user['code'].')</option>';
			}
		}

		return $result;
	}
}
