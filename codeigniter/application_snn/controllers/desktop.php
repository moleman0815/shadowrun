<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#header("Content-Type: text/html; charset=utf-8");

class Desktop extends CI_Controller {

	function Desktop() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));	
		$this->load->library('pagination');				
		if ($this->session->userdata('login') == true) {


			$this->load->model('main_db_assets');		
			$this->load->model('util');	
			$this->load->model('add_functions');	
			$this->add_functions->setActive();
			#$this->session->set_userdata('id', '5');		
			#$this->session->set_userdata('name', 'feta');		
			#$this->session->set_userdata('rank', '2');
		} else {
			redirect('/login');
		}
	}

	public function index ()  {
		redirect('/desktop/overview');
	}
	public function home ()  {
		redirect('/desktop/overview');
	}	

	public function deleteShoutbox() {
		if ($this->main_db_assets->deleteShoutbox()) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'false'));
		}
	}

	public function newShoutbox() {
		if ($this->input->post('sb_senden') == true) {
			if($this->main_db_assets->sendShoutbox()) {
				$this->session->set_userdata('sb_success', 'Dein Post war erfolgreich.');
				redirect($this->input->post('from'));		
			} else {
				redirect($this->input->post('from'));		
				$this->session->set_userdata('sb_error', 'Beim Eintragen deines Posts ist ein Fehler aufgetreten.');
			}	
		}
	}

	public function deleteMsg() {
		if ($this->input->post('id') == true) {
			$this->main_db_assets->deleteMessage($this->input->post('id'));							
		}
	}	

	public function replyMsg() {
		if ($this->input->post('id') == true) {
			$data = $this->main_db_assets->replyMessage($this->input->post('id'));			
			echo json_encode(array('status' => 'success', 'title' => $data[0]['title'], 'receiver' => $data[0]['nickname'], 'receiver_id' => $data[0]['send_from'], 'msg' => $data[0]['msg_text']));			
		}
	}

	public function overview() {
		$page = $this->uri->segment(3);
		$config['base_url'] = '/secure/snn/desktop/overview/';
		$config['total_rows'] = ($this->main_db_assets->countNews()-1);
		$config['per_page'] = 10;
		$config['num_tag_open'] = '<span class="newspaginationdigit">';
		$config['num_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span class="newspaginationdigitactive">';
		$config['cur_tag_close'] = '</span>';
		$config['next_link'] = '';
		$config['prev_link'] = '';
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<span class="newspaginationdigit">';
		$config['last_tag_close'] = '</span>';		
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<span class="newspaginationdigit">';
		$config['first_tag_close'] = '</span>';		
		
		$this->pagination->initialize($config);		
		$header = array('name' => $this->session->userdata('name'));
		$data = array(
						'news' => $this->main_db_assets->getNews($page),
						'pagination' => $this->pagination->create_links(),
			);
		$left = array(
						'show_shoutbox' => true,
          				'shoutbox' => $this->main_db_assets->getShoutbox(),
          				'show_messages' => true,
          				'column_messages' => $this->main_db_assets->getColumnMessages(),          				
						'show_friends' => true,
          				'friends' => $this->add_functions->getFriends(),          				
			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
			);

			$this->load->view('header');
			$this->load->view('menu_header', $header);
			$this->load->view('left_column', $left);
			$this->load->view('div_md8');
			$this->load->view('desktop', $data);
			$this->load->view('right_column', $right);
			$this->load->view('footer');


	}

	public function messages () {
		
		$msg = array('error' => '' , 'success' => '');
		
		if ($this->input->post('sendmsg') == true) {
			if ($this->main_db_assets->sendMessage()) {
				$msg['success'] = 'Die Nachricht wurde erfolgreich verschickt.';
			} else {
				$msg['error'] = 'Beim Versenden der Nachricht ist ein Fehler aufgetreten.';
			}
		}
		
		$page = $this->uri->segment(3);
		$header = array('name' => $this->session->userdata('name'));
		$config['base_url'] = '/secure/snn/desktop/messages/';
		$config['num_tag_open'] = '<span class="newspaginationdigit">';
		$config['num_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span class="newspaginationdigitactive">';
		$config['cur_tag_close'] = '</span>';
		$config['next_link'] = '';
		$config['prev_link'] = '';
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<span class="newspaginationdigit">';
		$config['last_tag_close'] = '</span>';		
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<span class="newspaginationdigit">';
		$config['first_tag_close'] = '</span>';			
		$config['total_rows'] = ($this->main_db_assets->countMessages()-1);
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		
		$data = array(
						'messages' => $this->main_db_assets->getMessages($page),
						'receiver' => $this->main_db_assets->getReceiver(),
						'pagination' => $this->pagination->create_links(),
						'msg' => $msg,						
			);
		$left = array(
						'show_shoutbox' => true,
          				'shoutbox' => $this->main_db_assets->getShoutbox(),
          				'show_messages' => false,
						'show_friends' => true,
          				'friends' => $this->add_functions->getFriends(),          				          				

			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
			);							

			$this->load->view('header');
			$this->load->view('menu_header');
			$this->load->view('left_column', $left);
			$this->load->view('div_md8');
			$this->load->view('messages', $data);		
			$this->load->view('right_column', $right);
			$this->load->view('footer');
	}

	public function addFriend() {
		if($this->add_functions->addFriend()) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'false'));
		}		
	}

	public function removeFriend() {
		if($this->add_functions->removeFriend()) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'false'));
		}		
	}

	public function friendship () {
		if($this->add_functions->activateFriend()) {
			redirect('/desktop/einstellungen');
		}
		
	

	}

	public function einstellungen () {
		$nickError = '';
		$passError = '';
		$avatarError = '';
		$avatarSuccess = '';
		$charError = '';
		$showme = 'default';
		$friendMsg = '';
		$flashMsg;

		$header = array('name' => $this->session->userdata('name'));
		$left = array(
						'show_shoutbox' => true,
          				'shoutbox' => $this->main_db_assets->getShoutbox(),
          				'show_messages' => true,
          				'column_messages' => $this->main_db_assets->getColumnMessages(),
						'show_friends' => true,
          				'friends' => $this->add_functions->getFriends(),          				          				
		);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
		);

		if($this->input->post('sendNick')) {
			if($this->input->post('nickname')) {
				if($this->user_model->changeNickname()) {
					$this->session->set_userdata('success', 'Dein Nickname wurde erfolgreich geändert.');
					redirect('desktop/einstellungen');									
				} else {
					$this->session->set_userdata('error', 'Beim Ändern deines Nicknames ist ein Fehler aufgetreten.');
					redirect('desktop/einstellungen');									
				}
			} else {
				$this->session->set_userdata('error', 'Yo Chummer, gib bitte einen Nickname an.');
				redirect('desktop/einstellungen');	
			}
		} else if ($this->input->post('sendPass')) {
			if($this->input->post('oldpassword') && $this->input->post('newpassword')) {
				if($this->user_model->changePassword()) {
					$this->session->set_userdata('success', 'Dein Passwort wurde erfolgreich geändert.');
					redirect('desktop/einstellungen');									
				} else {
					$this->session->set_userdata('error', 'Beim Ändern deines Passworts ist ein Fehler aufgetreten.');
					redirect('desktop/einstellungen');									
				}
			} else {
				$this->session->set_userdata('error', 'Check das nochmal Chummer, nen Feld leer lassen is nich.');
				redirect('desktop/einstellungen');	
			}
		} else if ($this->input->post('sendAvatar')) {
			if($this->add_functions->avatar()) {
				$this->session->set_userdata('success', 'Dein Avatar wurde erfolgreich hochgeladen.');
				redirect('desktop/einstellungen');									
			} else {
				$this->session->set_userdata('error', 'Beim Hochladen deines Avatars ist ein Fehler aufgetreten.');
				redirect('desktop/einstellungen');									
			}
		} else if ($this->input->post('sendChar')) {
			if($this->input->post('charname')) {
				if ($this->add_functions->editCharacter()) {
					$this->session->set_userdata('success', 'Dein Char wurde erfolgreich erstellt/ editiert.');
					redirect('desktop/einstellungen');									
				} else {
					$this->session->set_userdata('error', 'Beim Erstellen/ Editieren ist ein Fehler aufgetreten.');
					redirect('desktop/einstellungen');									
				}
			} else {
				$this->session->set_userdata('error', 'Du solltest deinem Char schon einen Namen spendieren.');
				redirect('desktop/einstellungen');	
			}
		}

		$data = array(
					'nickError' => $nickError,
					'charError' => $charError,
					'passError' => $passError,
					'avatarError' => $avatarError,
					'avatarSuccess' => $avatarSuccess,
					'char' => $this->add_functions->getCharacter(),
					'avatar' => $this->main_db_assets->getAvatar(),
					'friends' => $this->add_functions->getMainFriends(),
					'all_users' => $this->main_db_assets->getReceiver(),
					'showme' => $showme,
					'friendMsg' => $friendMsg,
					'flashMsg' => '',
		);


			$this->load->view('header');
			$this->load->view('menu_header');
			$this->load->view('left_column', $left);
			$this->load->view('div_md8');
			$this->load->view('einstellungen', $data);		
			$this->load->view('right_column', $right);
			$this->load->view('footer');
	}

	function deleteAvatar () {
		if($this->add_functions->deleteAvatar()) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'false'));
		}
	}

	function feedback () {
		if ($this->input->post('sendFeedback')) {
			$title = $this->input->post('title');
			$feedback = $this->input->post('feedback');
			$bereich = $this->input->post('bereich');
			if ((!empty($title) && (!empty($feedback)) && (!empty($bereich)) )) {
				if ($this->main_db_assets->sendFeedback()) {
					$this->session->set_userdata('success', 'Dein Feedback wurde eingetragen.');
					redirect('desktop/feedback');									
				} else {
					$this->session->set_userdata('error', 'Beim Versenden ist ein Fehler aufgetreten.');
					redirect('desktop/feedback');									
				}
			} else {
					$this->session->set_userdata('error', 'Bitte fülle alle Felder aus.');
					redirect('desktop/feedback');													
			}
		}

		$data = array('feedback' => $this->main_db_assets->getFeedback(),);	
		$this->load->view('header');
		$this->load->view('menu_header');			
		$this->load->view('feedback', $data);		
		$this->load->view('footer');		
	}

	public function changeFeedbackStatus () {
		if($this->main_db_assets->changeFeedbackStatus()) {
			$this->session->set_userdata('success', 'Feedbackstatus wurde geändert.');			
			echo json_encode(array('status' => 'success'));
		} else {
			$this->session->set_userdata('error', 'Ein Felder ist aufgetreten.');			
			echo json_encode(array('status' => 'false'));
		}		
	}

	public function logout () {
		$this->session->sess_destroy();
		redirect('/login');
	}


}