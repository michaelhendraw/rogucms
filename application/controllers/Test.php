<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index(){
        $periode_mulai = '2017-05-01';
        $periode_selesai = '2017-07-01';

        for ($i=new DateTime($periode_mulai); $i<new DateTime($periode_selesai); $i->modify('+1 day')) { 
            echo $i->format('Y-m-d').'<br/>';
        }
    }

	public function session(){
		pr($this->session->userdata());
	}

    public function e($data='id=1'){
        $this->load->library('global_library');
        echo $this->global_library->encrypt($data);
    }

    public function d($data='X3NhbHRlZF9azDexGFMLgp54u2B7fHf5UgwI41SzlEw='){
        $this->load->library('global_library');
        echo $this->global_library->decrypt($data);
    }
}