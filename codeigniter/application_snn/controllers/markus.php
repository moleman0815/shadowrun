<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#header("Content-Type: text/html; charset=utf-8");

class Markus extends CI_Controller {

	function Markus() {
		parent::__construct();
		$this->load->model('main_db_assets');

	}

	public function index ($myparam='notset')  {
		// demo: calculate before set to data payload
		$mydata = array();
		if($this->main_db_assets->getSeminarById('600160'))
        { 

        	$mydata =$this->main_db_assets->getSeminarById('600160');
        	print_r($mydata);
        }	

		$data = array(
	                    'title'    => 'Title vom Controller gesetzt' ,
	     				'h1'    => 'H1 vom Controller gesetzt',
	     				'baseroot' => MY_BASEROOT_PATH,
	                    'content' => $myparam
	                );
		$this->load->view('markus/header', $data);
		$this->load->view('markus/body', $data);
		$this->load->view('markus/footer');		

	}

	public function seminare ($sid='notset')  {
		// demo: calculate before set to data payload
		$mydata = array();
		if(isset($sid)){
			$mydata = $this->collectSnippetsForSeminar($sid);
		}
			

		$data = array(
	                    'title'    => 'Title vom Controller gesetzt' ,
	     				'h1'    => 'H1 vom Controller gesetzt',
	     				'baseroot' => MY_BASEROOT_PATH,
	                    'content' => $mydata
	                );
		$this->load->view('markus/header', $data);
		$this->load->view('markus/body', $data);
		$this->load->view('markus/footer');		

	}

	/*
		snippets 4 seminare
		in path /_inc/seminare/[seminar-id]/[info1|info2|info_margin|info_post|]
		# editable via seminar-id Table (Auswahl für Redakteur) und via PostTransformer im richtigen Pfad abgelegt

	*/

	protected function collectSnippetsForSeminar($sid){
		$availableSnippets = array('info1','info2','info_margin','info_post');
		$givenSnippets = array();
		foreach ($availableSnippets as $snippet) {
			if(file_exists(MY_BASEROOT_PATH . '/_inc/seminare/'.$sid.'/'.$snippet.'.php')){
				array_push($givenSnippets, $snippet);
			}
		}
		return $givenSnippets;
	}
	

}