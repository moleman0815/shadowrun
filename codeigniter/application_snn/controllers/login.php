<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#header("Content-Type: text/html; charset=utf-8");

class Login extends CI_Controller {

	function Login() {
		parent::__construct();
		$this->load->model('main_db_assets');		
		$this->load->model('util');
		$this->load->helper(array('form', 'url'));	

	}

	function index () {
		if ($this->input->post('login') == true) {
			$this->load->library('form_validation');		

			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Passwort', 'required');

			if ($this->form_validation->run() == FALSE)
			{			
				$data = array('flashmsg' => '<div style="color:red">An error occurred! Try again punk!</div>');
				$this->load->view('header');	
				$this->load->view('login_mask', $data);		
				
			} else {
				$user = $this->input->post('username');
				$pass = $this->input->post('password');
				if ($this->user_model->Login(array('username' => $user, 'password' => $pass))) {

					redirect('/desktop/overview', 'refresh');
				} else {
					$data = array('flashmsg' => '<div style="color:red">An error occurred! Try again punk!</div>');
					$this->load->view('header');	
					$this->load->view('login_mask', $data);		
				}
			}
		} else {
			$data = array('flashmsg' => '');
			$this->load->view('header');	
			$this->load->view('login_mask', $data);		
		}
	}

}