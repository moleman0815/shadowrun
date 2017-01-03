<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#header("Content-Type: text/html; charset=utf-8");

class Combatzone extends CI_Controller {

	function Combatzone() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));	
		if ($this->session->userdata('login') == true) {
			$this->load->model('combat_model');
			$this->load->model('main_db_assets');		
			$this->load->model('util');			
			$this->load->model('add_functions');	
			$this->add_functions->setActive();
		} else {
			redirect('/login');
		}
	}

	function index () {
		redirect('/combatzone/combat_overview');
	}

	function marketplace () {
		if ($this->input->post('buyItems')) {
			if($this->input->post('total_cost')) {
				if($this->combat_model->buyItems()) {
					$this->session->set_userdata('success', 'Dein Einkauf war erfolgreich gewesen.');
					redirect('combatzone/marketplace');									
				} else {
					$this->session->set_userdata('error', 'Bei deinem Einkauf ist ein Fehler aufgetreten.');
					redirect('combatzone/marketplace');									
				}
			} else {
				$this->session->set_userdata('error', 'Ein leerer Warenkorb kauft sich schlecht.');
				redirect('combatzone/marketplace');				
			}			
		} else if ($this->input->post('sellItems')) {
			if($this->input->post('total_sell')) {
				if($this->combat_model->sellItems()) {
					$this->session->set_userdata('success', 'Dein Verkauf war erfolgreich gewesen.');
					redirect('combatzone/marketplace');									
				} else {
					$this->session->set_userdata('error', 'Bei deinem Verkauf ist ein Fehler aufgetreten.');
					redirect('combatzone/marketplace');									
				}
			} else {
				$this->session->set_userdata('error', 'Ein leerer Warenkorb verkauft sich schlecht.');
				redirect('combatzone/marketplace');				
			}	
		}
		$left = array(
				'show_shoutbox' => true,
          		'show_messages' => true,
          		'shoutbox' => $this->main_db_assets->getShoutbox(),
          		'column_messages' => $this->main_db_assets->getColumnMessages(),
				'show_friends' => true,
  				'friends' => $this->add_functions->getFriends(),          				          		
			);

		$char = $this->add_functions->getCharacter();
		if (!empty($char)) {
			$center = array(
					'char' => $char,			
					'weapons' => $this->combat_model->getWeapons(),
					'armor' => $this->combat_model->getArmor(),
					'inv' =>  $this->combat_model->getInventory(),
				);		
		} else {
			$center = array();
		}
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
		);
		$this->load->view('header');
		$this->load->view('menu_header');		
		$this->load->view('left_column', $left);	
		$this->load->view('div_md10');	
		$this->load->view('combat/marketplace', $center);
		$this->load->view('div_end');
		$this->load->view('footer');		
	}

	function clinic () {
		if ($this->input->post('buyCyberware')) {
			if($this->input->post('total_cyber_cost')) {
				if($this->combat_model->buyCyberware()) {
					$this->session->set_userdata('success', 'Deine neue Cyberware wurde erfolgreich eingebaut.');
					redirect('combatzone/clinic');									
				} else {
					$this->session->set_userdata('error', 'Bei deiner Operation ist ein Fehler aufgetreten.');
					redirect('combatzone/clinic');									
				}
			} else {
				$this->session->set_userdata('error', 'Ein leerer Warenkorb kauft sich schlecht.');
				redirect('combatzone/clinic');				
			}
		}
		$left = array(
				'show_shoutbox' => true,
          		'show_messages' => true,
          		'shoutbox' => $this->main_db_assets->getShoutbox(),
          		'column_messages' => $this->main_db_assets->getColumnMessages(),
				'show_friends' => true,
  				'friends' => $this->add_functions->getFriends(),          				          		
			);
		$center = array(
				'char' => $this->add_functions->getCharacter(),
				'cyberware' => $this->combat_model->getCyberware(),
				'inv' =>  $this->combat_model->getInventory(),
			);		
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
		);
		$this->load->view('header');
		$this->load->view('menu_header');		
		$this->load->view('left_column', $left);	
		$this->load->view('div_md10');	
		$this->load->view('combat/clinic', $center);
		$this->load->view('div_end');
		$this->load->view('footer');		
	}	

	function combat_overview() {
		$left = array(
				'show_shoutbox' => true,
          		'show_messages' => true,
          		'shoutbox' => $this->main_db_assets->getShoutbox(),
          		'column_messages' => $this->main_db_assets->getColumnMessages(),
				'show_friends' => true,
  				'friends' => $this->add_functions->getFriends(),          				          		
			);		
		$center = array(
				'char' => $this->add_functions->getCharacter(),
				'avatar' => $this->main_db_assets->getAvatar(),
				'missions' => $this->combat_model->getAllMissions('1'),
				'stats' =>  $this->combat_model->getStatistics(),
				'inv' => $this->combat_model->getInventory(),
			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
		);
		$this->load->view('header');
		$this->load->view('menu_header');		
		$this->load->view('left_column', $left);	
		$this->load->view('div_md8');	
		$this->load->view('combat/overview', $center);
		$this->load->view('right_column', $right);
		$this->load->view('footer');
	}
	
	function combat_mission () {		
		$left = array(
				'show_shoutbox' => true,
          		'show_messages' => true,
          		'shoutbox' => $this->main_db_assets->getShoutbox(),
          		'column_messages' => $this->main_db_assets->getColumnMessages(),
				'show_friends' => true,
  				'friends' => $this->add_functions->getFriends(),
			);		
		$center = array(
				'char' => $this->add_functions->getCharacter(),
				'ganger' => $this->combat_model->getMissionGanger(),
				'mission' => $this->combat_model->getMissionData(),
				'inv' => $this->combat_model->getInventory(),
			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
		);
		//_debugDie($center);
		$this->load->view('header');
		$this->load->view('menu_header');		
		$this->load->view('left_column', $left);	
		$this->load->view('div_md8');	
		$this->load->view('combat/mission', $center);
		$this->load->view('right_column', $right);
		$this->load->view('footer');		
	}
	
	public function calculateFight() {
		$mid = $this->input->post('mid');
		$data = $this->combat_model->calculateFight($mid);
		echo json_encode(array('status' => 'success', 'mid' => $mid));
	}

	public function fetchMissions() {
		if ($this->input->post('level') == true) {
			$data = $this->combat_model->getAllMissions($this->input->post('level'));			
			#$this->util->_debug($data);
			$mid = array();
			$level = array();
			$title = array();
			$text  = array();
			$cash = array();
			$expense = array();
			$extras = array();
			$member = array();
			$tiles = array();

			if (count($data) > '0') {
				for ($x=0; $x<count($data);$x++) {
					if (empty($data[$x]['gid'])) continue;
					$ganger = count(explode(';', $data[$x]['gid']));
					array_push(
							$tiles, array(
									'mid' => $data[$x]['mid'],
									'level' => $data[$x]['level'],
									'title' => $data[$x]['title'], 
									'text' => $data[$x]['text'],
									'cash' => $data[$x]['cash'],
									'type' => ucfirst($data[$x]['type']),
									'ganger' => $ganger,
									'expense' => $data[$x]['expense'],
									'extras' => $data[$x]['extras'],
									'member' => $data[$x]['member']								
								)
							);
				}

				echo json_encode(array('status' => 'success', 'data' => $tiles));
			} else {
				echo json_encode(array('status' => 'false'));
			}
		}		
	}

	function fight () {
		$weapon = $this->input->post('weapon');
		if (empty($weapon)) {
			$this->session->set_userdata('error', 'Du solltest besser eine Waffe auswählen, wenn du keine hast, solltest du im Marktplatz vorbei schauen.');
			redirect('combatzone/combat_mission/'.$this->input->post('mid'));
		}

		$left = array(
				'show_shoutbox' => true,
          		'show_messages' => true,
          		'shoutbox' => $this->main_db_assets->getShoutbox(),
				'column_messages' => $this->main_db_assets->getColumnMessages(),
				'show_friends' => true,
  				'friends' => $this->add_functions->getFriends(),				
			);		
		$center = array(
				'char' => $this->add_functions->getCharacter(),
				'ganger' => $this->combat_model->getMissionGanger(),
				'combat' => $this->combat_model->calculateFight(),
				'mission' => $this->combat_model->getMissionData(),
			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
		);	


		$this->load->view('header');
		$this->load->view('menu_header');		
		$this->load->view('left_column', $left);	
		$this->load->view('div_md8');	
		$this->load->view('combat/round', $center);
		$this->load->view('right_column', $right);
		$this->load->view('footer');	
	}
	
	function initiateCombat () {
		echo json_encode(array('data' => $this->combat_model->calculateFight()));
	}

	function combat_result () {
		$left = array(
				'show_shoutbox' => true,
          		'show_messages' => true,
          		'shoutbox' => $this->main_db_assets->getShoutbox(),
				'column_messages' => $this->main_db_assets->getColumnMessages(),
				'show_friends' => true,
  				'friends' => $this->add_functions->getFriends(),				
			);		

		$center = array(
				'char' => $this->add_functions->getCharacter(),
				'ganger' => $this->combat_model->getMissionGanger(),
				'combatstats' => $this->combat_model->readCombatlogDB(),
				'mission' => $this->combat_model->getMissionData(),
			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
		);	

		$this->load->view('header');
		$this->load->view('menu_header');		
		$this->load->view('left_column', $left);	
		$this->load->view('div_md8');	
		$this->load->view('combat/result', $center);
		$this->load->view('right_column', $right);
		$this->load->view('footer');	
	}

	function combat_round() {
			#_debug($this->session->all_userdata());
		$left = array('show_shoutbox' => false,'show_messages' => false, 'show_friends' => false,
  				'friends' => $this->add_functions->getFriends(),);		
		$right = array('show_ads' => false,);	
		$center = array('combat' => $this->combat_model->getInfightData(),);					
		$this->load->view('header');
		$this->load->view('menu_header');		
		$this->load->view('left_column', $left);	
		$this->load->view('div_md8');	
		$this->load->view('combat/round', $center);
		$this->load->view('right_column', $right);
		$this->load->view('footer');			
	}
	
	function nextRound() {
		$action = $this->input->post('action');
		if (empty($action)) {
				$this->session->set_userdata('error', 'Du hast keine Aktion ausgewählt!');
				redirect('combatzone/combat_round');		
		} else {
			$this->combat_model->returnFromCombatRound();
			
		}
	}

	function inventory () {
		$left = array(
				'show_shoutbox' => true,
          		'show_messages' => true,
          		'shoutbox' => $this->main_db_assets->getShoutbox(),
          		'column_messages' => $this->main_db_assets->getColumnMessages(),
				'show_friends' => true,
  				'friends' => $this->add_functions->getFriends(),          				          		
			);		
		$center = array(
				'char' => $this->add_functions->getCharacter(),			
				'inv' => $this->combat_model->getInventory(),
			);
		$right = array(
						'show_ads' => true,
						'ads' => $this->main_db_assets->getAds(),
		);
		$this->load->view('header');
		$this->load->view('menu_header');		
		$this->load->view('left_column', $left);	
		$this->load->view('div_md8');	
		$this->load->view('combat/inventory', $center);
		$this->load->view('right_column', $right);
		$this->load->view('footer');
	}	
}
?>