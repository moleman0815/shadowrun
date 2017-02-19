<?php

/**
 * User_Model
 * 
 * @package Users
 */

class User_Model extends CI_Model {
	
	  function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('util');
    }	

	/** Utility Methods **/
	function GetUser($username, $pass) {
			$this->db->select('id, name, nickname, type, rank');
			$this->db->where('name', $username);	
			$this->db->where('privatekey', $pass);	
			$user = $this->db->get("login")->result_array();
			$this->db->select('charname, cid');
			$this->db->where('uid', $user[0]['id']);
			$char = $this->db->get("chars")->result_array();
			$user[0]['charid'] = $char[0]['cid'];
			$user[0]['charname'] = $char[0]['charname'];
			
			return $user;			
	}	
	
	function changeNickname () {
		$data = array('nickname' => $this->input->post('nickname'));
		$this->db->where('id', $this->session->userdata('id'));
		if($this->db->update('login', $data)) {
			$this->session->set_userdata('nickname', $this->input->post('nickname'));
			return true;
		} else {
			return false;
		}
	}
	
	function changePassword () {
		$old = md5($this->input->post('oldpassword'));
		$new = md5($this->input->post('newpassword'));
		
		$this->db->select('id');
		$this->db->where('id', $this->session->userdata('id'));
		$this->db->where('privatekey', $old);
		$query = $this->db->get("login");
		$login = $query->result_array();
		if ($login[0]['id']) {
			$data = array('privatekey' => $new);
			$this->db->where('id', $this->session->userdata('id'));
			return ($this->db->update('login', $data)) ? true : false;
		} else {
			return false;
		}
	}
	
	function Login($options = array()) {
		$user = $this->GetUser($options['username'], md5($options['password']));
		if(!$user) return false;

		foreach ($user[0] as $key => $value) {
			$this->session->set_userdata($key, $value);
		}
		$this->session->set_userdata('login', true);
	
		return true;
	}	

	function updateSession($id) {
	
	}
}

?>	