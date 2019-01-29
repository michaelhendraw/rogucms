<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_library {

    public function set_session_user($user){
        $CI =& get_instance();

        $CI->session->set_userdata('user_id',$user['id']);
        $CI->session->set_userdata('user_code',$user['code']);
        $CI->session->set_userdata('user_email',$user['email']);
        $CI->session->set_userdata('user_name',$user['name']);
        $CI->session->set_userdata('user_role',$user['role']);
    }
    
    public function encrypt($sData, $sKey='c1aY0_s3mU4b1S45uK53S') {
        $sResult = '';
        for($i=0;$i<strlen($sData);$i++){
            $sChar    = substr($sData, $i, 1);
            $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
            $sChar    = chr(ord($sChar) + ord($sKeyChar));
            $sResult .= $sChar;
        }
        return $this->encode_base64($sResult);
    }
    
    public function decrypt($sData, $sKey='c1aY0_s3mU4b1S45uK53S') {
        $sResult = '';
        $sData   = $this->decode_base64($sData);
        for($i=0;$i<strlen($sData);$i++){
            $sChar    = substr($sData, $i, 1);
            $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
            $sChar    = chr(ord($sChar) - ord($sKeyChar));
            $sResult .= $sChar;
        }
        return $sResult;
    }
    
    public function encode_base64($sData) {
        $sBase64 = base64_encode($sData);
        return str_replace('=', '', strtr($sBase64, '+/', '-_'));
    }
    
    public function decode_base64($sData) {
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64.'==');
    }
}