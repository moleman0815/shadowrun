<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#header("Content-Type: text/html; charset=utf-8");

class Admin extends CI_Controller {

	function Admin() {
		parent::__construct();
		$this->load->helper(array('form', 'url', 'directory'));			
		if ($this->session->userdata('rank') < '3') {
			$this->load->model('main_db_assets');	
			$this->load->model('add_functions');		
			$this->load->model('util');	
			$this->load->library('pagination');		
			$this->add_functions->setActive();			
		} else {
			redirect('/desktop/overview');
		}
	}

	function index () {
		redirect('/admin/overview');
	}

	public function overview ()  {
		$header = array('name' => $this->session->userdata('name'));
		$data = array();
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('div_md8');
		$this->load->view('admin/overview', $data);
		$this->load->view('div_end');
		$this->load->view('footer');			
	}	


	public function insertItem () {
		$error = '';
		$success = '';
		if($this->input->post('sendItem')) {
			if ($this->input->post('itemname')) {
				if($this->add_functions->insertItem()) {
					$this->session->set_userdata('success', 'Der Gegenstand wurde erfolgreich erstellt.');
				} else {
					$this->session->set_userdata('error', 'Beim Erstellen des Gegenstand ist ein Fehler aufgetreten.');
				}
			} else {
				$this->session->set_userdata('error', 'Beim Erstellen des Gegenstand ist ein Fehler aufgetreten.');
			}
		}
		$header = array('name' => $this->session->userdata('name'));
		$data = array(
						'error' => $error,
						'success' => $success,

			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/additem', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');		
	}

	public function itemsVerwalten() {
		$error = '';
		$success = '';
		$header = array('name' => $this->session->userdata('name'));
		$data = array(
				'error' => $error,
				'success' => $success,
				'items' => $this->add_functions->getAllItems(),

			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/itemsverwalten', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');			
	}
	
	public function itemsImport () {
		if ($this->input->post("sendfile") == true) {
			if($this->add_functions->importItems()) {
				$this->session->set_userdata('success', 'Die Gegenst�nde wurden erfolgreich importiert.');
			} else {
				$this->session->set_userdata('error', 'Beim Importieren der Gegenst�nde ist ein Fehler aufgetreten.');
			}			
		}
		$data = array(
				'error' => $error,
				'success' => $success,
		
		);
		$left = array(
				'show_shoutbox' => false,
				'show_messages' => false,
				'show_friends' => false,
		);
		$right = array('show_ads' => false);
		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('div_md8');
		$this->load->view('admin/importitems', $data);
		$this->load->view('div_end');
		$this->load->view('footer');
	}

	public function editItem () {
		if($this->input->post('sendItem')) {
			if($this->add_functions->editItem()) {
				$this->session->set_userdata('success', 'Der Gegenstand wurde erfolgreich editiert.');
				redirect('admin/editItem/'.$this->input->post('wid'));				
			} else {
				$this->session->set_userdata('error', 'Beim Editieren ist ein Fehler aufgetreten.');
				redirect('admin/editItem/'.$this->input->post('wid'));				
			}
		}
		$header = array('name' => $this->session->userdata('name'));
		$data = array(
				'item' => $this->add_functions->getItemById(),

			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/edititem', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');		
	}


	public function checkGangerName () {
		if($this->add_functions->checkGangerName()) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'false'));
		}
	}

	public function checkMissionTitle() {
		if($this->add_functions->checkMissionTitle()) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'false'));
		}
	}

	public function deleteGanger () {
		if($this->add_functions->deleteGanger()) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'false'));
		}		
	}

	public function editGanger () {
		if($this->input->post('editGanger')) {
			if ($this->input->post('gangername')) {
				if($this->add_functions->editGanger()) {
					$this->session->set_userdata('success', 'Der Ganger wurde erfolgreich editiert.');
					redirect('admin/editGanger/'.$this->input->post('gid'));
				} else {
					$this->session->set_userdata('error', 'Beim Editieren ist ein Fehler aufgetreten.');
					redirect('admin/editGanger/'.$this->input->post('gid'));
				}
			} else {
				$this->session->set_userdata('error', 'Bitte gibt dem Ganger einen Namen.');
				redirect('admin/editGanger/'.$this->input->post('gid'));
			}
		}

		$header = array('name' => $this->session->userdata('name'));
		$data = array(
			'ganger' => $this->add_functions->getGanger($this->uri->segment(3)),
			'images' => directory_map('assets/img/combat/ganger/'),
			'gid' => $this->input->post('gid'),
			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/editganger', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');				
	}

	public function gangerVerwalten() {
		$header = array('name' => $this->session->userdata('name'));	
		$data = array(
			'allganger' => $this->add_functions->getAllGanger(),
			);			
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/gangerverwalten', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');			
	}

	public function generateGanger () {
		if($this->input->post('sendGanger')) {
			if ($this->input->post('gangername')) {
				if ($this->input->post('gangerportrait')) {
					if($this->add_functions->generateGanger()) {
						$this->session->set_userdata('success', 'Der Ganger wurde erfolgreich erstellt.');
						redirect('admin/generateGanger');
					} else {
						$this->session->set_userdata('error', 'Beim Erstellen ist ein Fehler aufgetreten.');
						redirect('admin/generateGanger');
					}

				} else {
					$this->session->set_userdata('error', 'Du musst ein Profilbild auswählen.');
					redirect('admin/generateGanger');							
				}
			} else {
				$this->session->set_userdata('error', 'Benenne erstmal deinen Ganger.');
				redirect('admin/generateGanger');				
			}
		}


		$header = array('name' => $this->session->userdata('name'));
		$data = array(
			'images' => directory_map('assets/img/combat/ganger/'),
			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/generateganger', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');			
		
	}
	public function generateUpload() {
		#_debugDie($this->input->post());
		if($this->input->post('sendImage')) {
			if($this->add_functions->uploadImages()) {
				$this->session->set_userdata('success', 'Bild wurde erfolgreich hochgeladen.');
				redirect('admin/generateUpload/'.$this->input->post('imagetype'));
			} else {
				$this->session->set_userdata('error', 'Beim Erstellen ist ein Fehler aufgetreten.');
				redirect('admin/generateUpload/'.$this->input->post('imagetype'));
			}
		}
		$header = array('name' => $this->session->userdata('name'));
		$data = array();
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$data = array('type' => $this->uri->segment('3'));
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/generateupload', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');
	}

	public function uploadVerwalten() {
		$header = array('name' => $this->session->userdata('name'));
		$data = array();
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);	
		$data = array(
				'images' => directory_map('assets/img/combat/'.$this->uri->segment('3').'/'), 
				'type' => $this->uri->segment('3'),
			);	
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/uploadverwalten', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');
	}

	public function deleteUpload() {
		if ($this->add_functions->deleteUpload()) {
			$this->session->set_userdata('success', 'Das Löschen war erfolgreich.');			
			echo json_encode(array('status' => 'success'));
		} else {
			$this->session->set_userdata('error', 'Beim Löschen ist ein Fehler aufgetreten.');			
			echo json_encode(array('status' => 'false'));
		}		
	}


	public function generateMission() {
		if($this->input->post('sendMission')) {
			if ($this->input->post('missionstitle')) {
				if ($this->input->post('missionsimage')) {
					if ($this->input->post('missionganger')) {
						if ($this->add_functions->generateMission()) {
							$this->session->set_userdata('success', 'Die Mission wurde erfolgreich erstellt.');
							redirect('admin/generateMission');
						} else {
							$this->session->set_userdata('error', 'Beim Erstellen ist ein Fehler aufgetreten.');
							redirect('admin/generateMission');
						}
					} else {
						$this->session->set_userdata('error', 'Bitte wähle mindestens einen Gegner aus.');
						redirect('admin/generateMission');						
					}
				} else {
					$this->session->set_userdata('error', 'Du musst ein Banner auswählen.');
					redirect('admin/generateMission');
				}
			} else {
					$this->session->set_userdata('error', 'Benenne bitte deine Mission.');
					redirect('admin/generateMission');
			}
		}


		$header = array('name' => $this->session->userdata('name'));
		$data = array(
			'images' => directory_map('assets/img/combat/missionsbanner/'),
			'allganger' => $this->add_functions->getAllGanger(),
			'johnson' => directory_map('assets/img/combat/johnson/'),
			'story' => directory_map('assets/img/combat/storyimage/'),
			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/generatemission', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');			
	}

	public function getMission () {
		$data = $this->add_functions->getMission();
		echo json_encode(array('status' => 'success', 'data' => $data));
	}

	public function editMission() {
		$missionError = '';
		$missionSuccess = '';
		if($this->input->post('editMission')) {
			if ($this->input->post('missionstitle')) {
				if ($this->input->post('missionsimage')) {
					if ($this->input->post('missionganger')) {
						if ($this->add_functions->editMission()) {
							$this->session->set_userdata('success', 'Die Mission wurde erfolgreich editiert/ gelöscht.');
							redirect('admin/editMission/'.$this->input->post('mid'));
						} else {
							$this->session->set_userdata('error', 'Beim Editieren ist ein Fehler aufgetreten.');
							redirect('admin/editMission/'.$this->input->post('mid'));
						}
					} else {
						$this->session->set_userdata('error', 'Bitte wähle mindestens einen Gegner aus.');
						redirect('admin/editMission/'.$this->input->post('mid'));
					}
				} else {
					$this->session->set_userdata('error', 'Du musst ein Banner auswählen.');
					redirect('admin/editMission/'.$this->input->post('mid'));
				}
			} else {
				$this->session->set_userdata('error', 'Benenne bitte deine Mission.');
				redirect('admin/editMission/'.$this->input->post('mid'));
			}
		}


		$header = array('name' => $this->session->userdata('name'));
		$data = array(
			'images' => directory_map('assets/img/combat/missionsbanner/'),
			'johnson' => directory_map('assets/img/combat/johnson/'),
			'story' => directory_map('assets/img/combat/storyimage/'),
			'allganger' => $this->add_functions->getAllGanger(),
			'allmissions' => $this->add_functions->getAllMissions(),
			'missionError' => $missionError,
			'missionSuccess' => $missionSuccess,
			'mid' => $this->input->post('mid'),

			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/editmission', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');			
	}

	public function newNews () {
		if ($this->input->post('sendNews')) {
			if ($this->input->post('title')) {
				if ($this->input->post('category')) {
					if ($this->add_functions->insertNews()) {
						$this->session->set_userdata('success', 'Die News wurde erfolgreich eingetragen.');
						redirect('admin/newNews');
					} else {
						$this->session->set_userdata('error', 'Beim Erstellen ist ein Fehler aufgetreten.');
						redirect('admin/newNews');
					}
				} else {
					$this->session->set_userdata('error', 'Bitte wähle eine Kategorie aus.');
					redirect('admin/newNews');
				}
			} else {
				$this->session->set_userdata('error', 'Bitte gib einen Titel ein.');
				redirect('admin/newNews');
			}
		}
		
		$header = array('name' => $this->session->userdata('name'));
		$data = array(
				'categories' => $this->add_functions->getCategories(),
			);
		$left = array(
					'show_shoutbox' => false,
          			'show_messages' => false,
					'show_friends' => false,	          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/newnews', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');
	}

	public function newUser () {
			$newsError = '';
			$newsSuccess = '';
			if ($this->input->post('sendUser')) {
				if ($this->add_functions->newUser()) {
					$newsSuccess = "Der User wurde erfolgreich eingetragen.";
				} else {
					$newsError = "Beim Eintragen des Users ist ein Fehler aufgetreten.";
				}				
			}
			
			$header = array('name' => $this->session->userdata('name'));
			$data = array(
					'categories' => $this->add_functions->getCategories(),
					'newsError' => $newsError,
					'newsSuccess' => $newsSuccess,
				);
			$left = array(
						'show_shoutbox' => false,
	          			'show_messages' => false,
						'show_friends' => false,	          				
				);
			$right = array('show_ads' => false);		
			$this->load->view('header');
			$this->load->view('menu_header', $header);
			$this->load->view('left_column', $left);
			$this->load->view('admin/newuser', $data);
			$this->load->view('right_column', $right);
			$this->load->view('footer');
	}
	public function editNews () {
			
			$newsError = '';
			$newsSuccess = '';
			if ($this->input->post('editNews')) {
				if ($this->input->post('title')) {
					if ($this->input->post('category')) {
						if ($this->add_functions->editNews()) {
							$this->session->set_userdata('success', 'Die News wurde erfolgreich editiert.');
							redirect('admin/editNews/'.$this->input->post('id'));
						} else {
							$this->session->set_userdata('error', 'Beim Editieren ist ein Fehler aufgetreten.');
							redirect('admin/editNews/'.$this->input->post('id'));
						}
					} else {
						$this->session->set_userdata('error', 'Bitte wähle eine Kategorie aus.');
						redirect('admin/editNews/'.$this->input->post('id'));						
					}
				} else {
					$this->session->set_userdata('error', 'Bitte gib einen Titel ein.');
					redirect('admin/editNews/'.$this->input->post('id'));											
				}
			}
			
			$header = array('name' => $this->session->userdata('name'));
			$data = array(
					'news' =>  $this->add_functions->getNews(),
					'categories' => $this->add_functions->getCategories(),
					'newsError' => $newsError,
					'newsSuccess' => $newsSuccess,
				);
			$left = array(
						'show_shoutbox' => false,
	          			'show_messages' => false,
						'show_friends' => false,	          			
				);
			$right = array('show_ads' => false);		
			$this->load->view('header');
			$this->load->view('menu_header', $header);
			$this->load->view('left_column', $left);
			$this->load->view('admin/editnews', $data);
			$this->load->view('right_column', $right);
			$this->load->view('footer');
	}	

	public function userVerwalten ()  {
		$newsError = '';
		$newsSuccess = '';

		$header = array('name' => $this->session->userdata('name'));
		$data = array(
						'newsError' => $newsError,
						'newsSuccess' => $newsSuccess,
						'user' => $this->add_functions->getLoginUsers(),
			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/userverwalten', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');	
	}	

	public function deleteUser () {
		if($this->add_functions->deleteLoginUser()) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'false'));
		}		
	}

	public function newsVerwalten ()  {
		$newsError = '';
		$newsSuccess = '';
		$page = $this->uri->segment(3);			
		$config['base_url'] = '/secure/snn/admin/newsVerwalten/';
		$config['total_rows'] = $this->main_db_assets->countNews();
		$config['per_page'] = 10;
		$this->pagination->initialize($config);		

		$header = array('name' => $this->session->userdata('name'));
		$data = array(
						'newsError' => $newsError,
						'newsSuccess' => $newsSuccess,
						'news' => $this->main_db_assets->getNews($page),
						'pagination' => $this->pagination->create_links(),

			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/newsverwalten', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');	
	}	

	public function deleteNews () {
		if ($this->add_functions->deleteNews()) {
			echo json_encode(array('status' => 'success'));		
		} else {
			echo json_encode(array('status' => 'false'));		
		}
	}

	public function adsVerwalten ()  {
		$newsError = '';
		$newsSuccess = '';
		$page = $this->uri->segment(3);			
		$config['base_url'] = '/secure/snn/admin/adsVerwalten/';
		$config['total_rows'] = $this->main_db_assets->countAds();
		$config['per_page'] = 10;
		$this->pagination->initialize($config);		

		$header = array('name' => $this->session->userdata('name'));
		$data = array(
						'newsError' => $newsError,
						'newsSuccess' => $newsSuccess,
						'ads' => $this->main_db_assets->getAllAds($page),
						'pagination' => $this->pagination->create_links(),

			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/adsverwalten', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');	
	}		

	public function newAds () {	
		if ($this->input->post('sendAds')) {				
			if ($this->input->post('adstext')) {
				if ($_FILES['adsimage']['name']) {
					if ($this->add_functions->insertAds()) {
						$this->session->set_userdata('success', 'Die Werbung wurde erfolgreich erstellt.');
						redirect('admin/newAds');
					} else {
						$this->session->set_userdata('error', 'Beim Erstellen ist ein Fehler aufgetreten.');
						redirect('admin/newAds');
					}
				} else {
					$this->session->set_userdata('error', 'Bitte gib einen Bild an.');
					redirect('admin/newAds');					
				}
			} else {
				$this->session->set_userdata('error', 'Bitte gib einen Text ein.');
				redirect('admin/newAds');
			}

		}

		$header = array('name' => $this->session->userdata('name'));
		$data = array();
		$left = array(
						'show_shoutbox' => false,
						'show_messages' => false,
						'show_friends' => false,						
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/newads', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');
	}

	public function deleteAds() {
		if ($this->add_functions->deleteAds()) {
			echo json_encode(array('status' => 'success'));		
		} else {
			echo json_encode(array('status' => 'false'));		
		}		
	}

	public function editAds () {			
		if ($this->input->post('editAds')) {
			if ($this->input->post('adstext')) {
				if ($this->add_functions->editAds()) {
					$this->session->set_userdata('success', 'Die Werbung wurde erfolgreich editiert.');
					redirect('admin/editAds/'.$this->input->post('id'));
				} else {
					$this->session->set_userdata('error', 'Beim Editieren ist ein Fehler aufgetreten.');
					redirect('admin/editAds/'.$this->input->post('id'));
				}
			} else {
				$this->session->set_userdata('error', 'Bitte gib einen Text ein.');
				redirect('admin/editAds/'.$this->input->post('id'));
			}
		}
						
		$header = array('name' => $this->session->userdata('name'));
		$data = array(
				'ads' =>  $this->add_functions->getAds(),
			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/editads', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');		
	}

	public function insertCategory() {
		$catError = '';
		$catSuccess = '';
		
		if ($this->input->post('sendcat')) {						
			if ($this->input->post('cat_name')) {
				if ($this->add_functions->insertCategory()) {
					$catSuccess = "Die Kategory wurde erfolgreich eingetragen.";
				} else {
					$catError = "Beim Eintragen der Kategory ist ein Fehler aufgetreten.";
				}
			} else {
				$catError = "Bitte tragen sie einen Namen ein.";	
			}

		}

		$header = array('name' => $this->session->userdata('name'));
		$data = array(
				'catError' => $catError,
				'catSuccess' => $catSuccess,
			);
		$left = array(
						'show_shoutbox' => false,
						'show_messages' => false,
						'show_friends' => false,						
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/newcat', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');	
	}
	
	function categoryVerwalten () {
		$newsError = '';
		$newsSuccess = '';


		$header = array('name' => $this->session->userdata('name'));
		$data = array(
						'newsError' => $newsError,
						'newsSuccess' => $newsSuccess,
						'ads' => $this->main_db_assets->getAllCategories(),

			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/categoryverwalten', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');		
	}

	public function deleteCategory () {
		if ($this->add_functions->deleteCategory()) {
			echo json_encode(array('status' => 'success'));		
		} else {
			echo json_encode(array('status' => 'false'));		
		}	
	}

	public function editCategory () {
		$catError = '';
		$catSuccess = '';
		if ($this->input->post('editcat')) {
			if ($this->input->post('cat_name')) {
				if ($this->input->post('autor')) {
					if ($this->add_functions->editCategory()) {
						$catSuccess = "Die Werbung wurde erfolgreich editiert.";
					} else {
						$catError = "Beim Ändern der Werbung ist ein Fehler aufgetreten.";
					}
				} else {
					$catError = "Bitte gib einen Namen ein.";	
				}
			} else {
				$catError = "Bitte gib einen Namen ein.";
			}
		}
						
		$header = array('name' => $this->session->userdata('name'));
		$data = array(
				'ads' =>  $this->add_functions->getCategory(),
				'catError' => $catError,
				'catSuccess' => $catSuccess,
			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/editcat', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');		
	}

	public function lastOnline () {				
		$header = array('name' => $this->session->userdata('name'));
		$data = array(
				'online' =>  $this->add_functions->lastOnline(),
			);
		$left = array(
						'show_shoutbox' => false,
          				'show_messages' => false,
						'show_friends' => false,          				
			);
		$right = array('show_ads' => false);		
		$this->load->view('header');
		$this->load->view('menu_header', $header);
		$this->load->view('left_column', $left);
		$this->load->view('admin/lastonline', $data);
		$this->load->view('right_column', $right);
		$this->load->view('footer');		
	}
}