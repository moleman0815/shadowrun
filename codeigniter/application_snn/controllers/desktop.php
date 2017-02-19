<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#header("Content-Type: text/html; charset=utf-8");

class Desktop extends CI_Controller {

	var $settings;
	var $newMessages;
	var $header;
	
	function Desktop() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));	
		$this->load->library('pagination');				
		if ($this->session->userdata('login') == true) {

			#_debugDie($this->session->all_userdata());
			$this->load->model('main_db_assets');		
			$this->load->model('util');	
			$this->load->model('add_functions');	
			$this->add_functions->setActive();
			$this->settings = $this->add_functions->readSettings();
			$this->newMessages = $this->main_db_assets->countNewMessages();
			$this->header = array(
					'name' => $this->session->userdata('name'),
					'systemnews' => $this->add_functions->getSystemNews(),
			);
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
	
	public function deleteFeedback () {
		if ($this->input->post('fid') == true) {
			$this->main_db_assets->deleteFeedback($this->input->post('fid'));
		}
	}

	public function replyMsg() {
		if ($this->input->post('id') == true) {
			$data = $this->main_db_assets->replyMessage($this->input->post('id'));			
			echo json_encode(array('status' => 'success', 'title' => $data[0]['title'], 'receiver' => $data[0]['nickname'], 'receiver_id' => $data[0]['send_from'], 'msg' => $data[0]['msg_text'], 'sender_id' => $data[0]['send_to']));			
		}
	}
	
	public function getNewMsgHeader () {
		echo json_encode(array('msgNo' => $this->newMessages));
	}
	
	public function updateNewMessage () {
		if ($this->main_db_assets->updateNewMessage()) {
			return true;
		}
	}
	
	public function receiveFeedback () {
		echo json_encode(array('status' => 'success', 'data' => $this->main_db_assets->receiveFeedback()));
	}
	
	public function shoutbox () {
		$page = $this->uri->segment(3);
		$data = array(
				'shoutbox' => $this->main_db_assets->getShoutboxFull(),
		);
		$left = array(
				'show_shoutbox' => false,
				'show_messages' => true,
				'column_messages' => $this->main_db_assets->getColumnMessages(),
				'show_friends' => true,
				'friends' => $this->add_functions->getFriends(),
				'settings' => $this->settings,
		);
		$right = array(
				'show_ads' => true,
				'ads' => $this->main_db_assets->getAds(),
				'settings' => $this->settings,
		);
		
		$this->load->view('header');
		$this->load->view('menu_header', $this->header);
		$this->load->view('left_column', $left);
		$this->load->view('div_md8');
		$this->load->view('shoutbox', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');
	}

	public function overview() {
		_debug($this->session->all_userdata());
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

		$data = array(
						'news' => $this->main_db_assets->getNews($page),
						'pagination' => $this->pagination->create_links(),
						'receiver' => $this->main_db_assets->getReceiver(),
						'missions' => $this->add_functions->getSpecialMission(),
			);
		$left = array(
						'show_shoutbox' => true,
          				'shoutbox' => $this->main_db_assets->getShoutbox(),
          				'show_messages' => true,
          				'column_messages' => $this->main_db_assets->getColumnMessages(),          				
						'show_friends' => true,
          				'friends' => $this->add_functions->getFriends(),  
						'settings' => $this->settings,
			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
						'settings' => $this->settings,
			);

			$this->load->view('header');
			$this->load->view('menu_header', $this->header);
			$this->load->view('left_column', $left);
			$this->load->view('div_md8');
			$this->load->view('desktop', $data);
			$this->load->view('right_column', $right);
			$this->load->view('footer');


	}
	
	public function readme () {	
		$data = array();
		$left = array(
				'show_shoutbox' => false,
				'show_messages' => false,
				'show_friends' => false,
				'settings' => $this->settings,	
		);
		$right = array(
				'show_ads' => false,
				'settings' => $this->settings,
		);
	
		$this->load->view('header');
		$this->load->view('menu_header', $this->header);
		$this->load->view('left_column', $left);
		$this->load->view('div_md10');
		$this->load->view('readme', $data);
		$this->load->view('div_end');
		$this->load->view('footer');
	}
	
	public function messages () {	
		$msg = array('error' => '' , 'success' => '');
		$this->load->library('pagination');

		$page = $this->uri->segment(3);

		$config['base_url'] = '/secure/snn/desktop/messages/';
		$config['total_rows'] = ($this->main_db_assets->countMessages()-1);
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


		$header = array(
				'name' => $this->session->userdata('name'),
		);
		$data = array(
						'messages' => $this->main_db_assets->getMessages($page),
						'receiver' => $this->main_db_assets->getReceiver(),
						'pagination' => $this->pagination->create_links(),
						'msg' => $msg,	
						'settings' => $this->settings,
			);
		$left = array(
						'show_shoutbox' => true,
          				'shoutbox' => $this->main_db_assets->getShoutbox(),
          				'show_messages' => false,
						'show_friends' => true,
          				'friends' => $this->add_functions->getFriends(),    
						'settings' => $this->settings,

			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
						'settings' => $this->settings,
			);							

			$this->load->view('header');
			$this->load->view('menu_header', $this->header);
			$this->load->view('left_column', $left);
			$this->load->view('div_md8');
			$this->load->view('messages', $data);		
			$this->load->view('right_column', $right);
			$this->load->view('footer');
	}
	
	public function sendMessage () {
		if ($this->input->post('reply') == 1) {
			$title = $this->input->post('replytitle');
			$receiver = $this->input->post('replyreceiver');
			$text = $this->input->post('reply_text');
		} else {
			$title = $this->input->post('title');
			$receiver = $this->input->post('receiver');
			$text = $this->input->post('msg_text');
		}
		
		
		
		if ($this->input->post('sendmsg') == true) {
			if(empty($title)) {
				echo json_encode(array('status' => 'error', 'msg' => 'Beim Versenden der Nachricht ist ein Fehler aufgetreten: Kein Titel.'));
			} else if (empty($receiver[0])) {
				echo json_encode(array('status' => 'error', 'msg' => 'Beim Versenden der Nachricht ist ein Fehler aufgetreten: Kein Empf�nger.'));
			} else if (empty($text)) {
				echo json_encode(array('status' => 'error', 'msg' => 'Beim Versenden der Nachricht ist ein Fehler aufgetreten: Kein Text.'));
			} else {
				if ($this->main_db_assets->sendMessage()) {
					echo json_encode(array('status' => 'success', 'msg' => 'Die Nachricht wurde erfolgreich verschickt.'));
				} else {
					echo json_encode(array('status' => 'error', 'msg' => 'Beim Versenden der Nachricht ist ein Fehler aufgetreten.'));
				}
			}
		}
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

		$settings = $this->add_functions->readSettings();
		
		$left = array(
						'show_shoutbox' => true,
          				'shoutbox' => $this->main_db_assets->getShoutbox(),
          				'show_messages' => true,
          				'column_messages' => $this->main_db_assets->getColumnMessages(),
						'show_friends' => true,
          				'friends' => $this->add_functions->getFriends(), 
						'settings' => $this->settings,
		);
		$right = array(
				'show_ads' => true,
				'ads' => $this->main_db_assets->getAds(),
				'settings' => $this->settings,
		);

		
		if ($this->input->post('sendSettings')) {
			if($this->add_functions->writeSettings()) {
				$this->session->set_userdata('success', 'Deine Settings wurde erfolgreich gespeichert.');
				redirect('desktop/einstellungen');
			} else {
				$this->session->set_userdata('error', 'Beim Speichern deiner Settings ist ein Fehler aufgetreten.');
				redirect('desktop/einstellungen');
			}
		} else if($this->input->post('sendNick')) {
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
					'settings' => $this->settings,
		);


			$this->load->view('header');
			$this->load->view('menu_header', $this->header);
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
			$mode = $this->input->post('mode');
			if ((!empty($title) && (!empty($feedback)) && (!empty($bereich)) )) {
				if ($mode == 'edit') {
					if ($this->main_db_assets->editFeedback()) {
						$this->session->set_userdata('success', 'Dein Feedback wurde editiert.');
						redirect('desktop/feedback');
					} else {
						$this->session->set_userdata('error', 'Beim Editieren ist ein Fehler aufgetreten.');
						redirect('desktop/feedback');
					}
				} else {
					if ($this->main_db_assets->sendFeedback()) {
						$this->session->set_userdata('success', 'Dein Feedback wurde eingetragen.');
						redirect('desktop/feedback');									
					} else {
						$this->session->set_userdata('error', 'Beim Versenden ist ein Fehler aufgetreten.');
						redirect('desktop/feedback');									
					}
				}
			} else {
					$this->session->set_userdata('error', 'Bitte fülle alle Felder aus.');
					redirect('desktop/feedback');													
			}
		} else if ($this->input->post('sendfeedbackanswer')) {
			if ($this->main_db_assets->sendFeedbackAnswer()) {
				$this->session->set_userdata('success', 'Deine Feedbackantwort wurde eingetragen.');
				redirect('desktop/feedback');
			} else {
				$this->session->set_userdata('error', 'Beim Versenden ist ein Fehler aufgetreten.');
				redirect('desktop/feedback');
			}
		}

		$data = array('feedback' => $this->main_db_assets->getFeedback(),);	
		$this->load->view('header');
		$this->load->view('menu_header', $header);			
		$this->load->view('feedback', $data);		
		$this->load->view('footer');		
	}
	
	function features () {
		if ($this->input->post('sendFeature')) {
			$feature = $this->input->post('feature');
			if (!empty($feature)) {				
					if ($this->main_db_assets->sendFeatures()) {
						$this->session->set_userdata('success', 'Dein Feature wurde eingetragen.');
						redirect('desktop/features');
					} else {
						$this->session->set_userdata('error', 'Beim Versenden ist ein Fehler aufgetreten.');
						redirect('desktop/features');
					}				
			} else {
				$this->session->set_userdata('error', 'Bitte fülle alle Felder aus.');
				redirect('desktop/features');
			}
		}
		$header = array(
				'name' => $this->session->userdata('name'),
		);
		$data = array('features' => $this->main_db_assets->getFeatures(),);
		$this->load->view('header');
		$this->load->view('menu_header', $this->header);
		$this->load->view('features', $data);
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
	
	public function sendNewComment () {
		$comment = $this->input->post('comment');
		if (empty($comment)) {
			echo json_encode(array('status' => 'error', 'msg' => 'Ein Fehler ist aufgetreten. Bitte geben Sie einen Text ein.'));
		} else {
			if($this->main_db_assets->sendNewComment()) {
				echo json_encode(array('status' => 'success', 'msg' => 'Ihr Kommentar wurde eingetragen.'));
			} else {
				echo json_encode(array('status' => 'error', 'msg' => 'Ein Fehler ist aufgetreten.'));
			}
		}
	}
	
	public function deleteComment () {
		if($this->main_db_assets->deleteComment()) {
			echo json_encode(array('status' => 'success', 'msg' => 'Ihr Kommentar wurde gel�scht.'));
		} else {
			echo json_encode(array('status' => 'error', 'msg' => 'Ein Fehler ist aufgetreten.'));
		}
	}

	public function logout () {
		$this->session->sess_destroy();
		redirect('/login');
	}


}