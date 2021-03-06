<?php

/**
 * User_Model
 * 
 * @package Users
 */

class Add_functions extends CI_Model {
	
	  function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('util');
       # $this->load->model('combat_model');
		$this->load->library('upload');
        #$this->load->library('image_lib');
    }	

    function setActive () {
    	$sql = sprintf("
    		INSERT INTO %s
    			(uid, lastactive, page)
    		VALUES ('%s', '%s', '%s')
    		ON DUPLICATE KEY UPDATE lastactive='%s', page='%s'
    		", 
    		'newsnet_active', $this->session->userdata('id'), time(), $this->uri->segment('1').'/'.$this->uri->segment('2'),
    		time(), $this->uri->segment('1').'/'.$this->uri->segment('2')
    	);
    	$this->db->query($sql);
    }    
    
    function activateFriend() {
    	if($this->uri->segment('3')== '1') {
    		$this->db->where('uid', $this->uri->segment('5'));
    		$this->db->where('fid', $this->uri->segment('6'));
    		$this->db->where('accepted', $this->uri->segment('4'));
    		$data = array('accepted' => '1');
    		if ($this->db->update('friends', $data)) {
    			$data = array(
					'uid' => $this->uri->segment('6'),
					'fid' => $this->uri->segment('5'),
					'accepted' => '1',
					'rejected' => '0'
					);
    			$this->db->insert('friends', $data);
    			return true;
    		}
    	}
    	die();
    }

    function getFriends () {
    	if($this->session->userdata('rank') < '1') {
			$this->db->select('login.nickname, login.id');
	        $this->db->from('login');			
	        $this->db->where('rank != ', '1');
	        $friends = $this->db->get()->result_array();   	        
    	} else {
	        $this->db->select('login.nickname, login.id, friends.accepted');
	        $this->db->from('friends');
	        $this->db->where('friends.uid', $this->session->userdata('id'));
	        $this->db->where('accepted', '1');
	        $this->db->where('friends.rejected', '0');        
	       $this->db->join('login', 'friends.fid = login.id');
	       $this->db->group_by('nickname');
	        $friends = $this->db->get()->result_array();   
	    }
        for ($x=0;$x<count($friends);$x++) {
        	$this->db->select('lastactive');
        	$active = $this->db->get_where('active', array('uid' => $friends[$x]['id']))->result_array();
        	$friends[$x]['lastactive'] = '';
        	if(!empty($active)) {
        		$friends[$x]['lastactive'] = $active[0]['lastactive'];
        	}
        }
#_debugDie($friends);
        return $friends;
    }

    function getMainFriends () {
        $this->db->select('login.nickname, login.id, friends.accepted');
        $this->db->from('friends');
        $this->db->where('friends.uid', $this->session->userdata('id'));
        #$this->db->where('accepted', '1');
        $this->db->where('friends.rejected', '0');        
        $this->db->join('login', 'friends.fid = login.id');
        $friends = $this->db->get()->result_array();   
        for ($x=0;$x<count($friends);$x++) {
        	$this->db->select('lastactive');
        	$active = $this->db->get_where('active', array('uid' => $friends[$x]['id']))->result_array();
        	if(!empty($active)) {
        		$friends[$x]['lastactive'] = $active[0]['lastactive'];
        	}
        }

        return $friends;
    }

    function removeFriend () {
    	$this->db->where('fid', $this->input->post('id'));
    	$this->db->where('uid', $this->session->userdata('id'));
    	$this->db->delete('friends');

    	$this->db->where('uid', $this->input->post('id'));
    	$this->db->where('fid', $this->session->userdata('id'));
    	$this->db->delete('friends');    	
    	return true;
    }

    function addFriend () {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		srand((double)microtime()*1000000);
		$i = 0; // Counter auf null
		$length = '20';
		$pass = '';
			while ($i < $length) { 
			$num = rand() % strlen($chars);
			$tmp = substr($chars, $num, 1);
		    $pass = $pass . $tmp;
		    $i++;
		}    	
		$is_friend = $this->db->get_where('friends', array('uid' => $this->session->userdata('id'), 'fid' => $this->input->post('id')))->result_array();
		$data = array(
			'uid' => $this->session->userdata('id'),
			'fid' => $this->input->post('id'),
			'accepted' => $pass,
			'rejected' => '0'
			);		
		if (empty($is_friend)) {
			if($this->db->insert('friends', $data)) {
				$this->sendInternMessage($pass);	
				return true;	
			} else {
				return false;
			}
		} else {
			$this->db->where('uid', $this->session->userdata('id'));
			$this->db->where('fid', $this->input->post('id'));
			if($this->db->update('update', $data)) {
				$this->sendInternMessage($pass);	
				return true;	
			} else {
				return false;
			}
		}
    }
    
    function insertSpell () {
    	#_debugDie($this->input->post());
    	$data= array (
    			'name' => $this->input->post('itemname'),
    			'typ' => $this->input->post('typ'),
    			'subtype' => $this->input->post('subtype'),
    			'mw' => $this->input->post('mw'),
    			'entzug' => $this->input->post('entzug'),
    			'wirkung' => $this->input->post('wirkung'),
    			'target' => $this->input->post('target'),
    			'cost' => $this->input->post('cost'),
    	);
    	return ($this->db->insert('spells', $data)) ? true : false;
    }

    function insertItem() {
    	$bmode=$this->input->post('mode');
    	if (!empty($bmode)) {
    		$mode = implode(';', $this->input->post('mode'));
    	} else {
    		$mode = '';
    	}
    	$damage = ($this->input->post('type') == 'melee') ? $this->input->post('melee_damage') : $this->input->post('damage');
    		  
    	$essenz = str_replace(',', '.', $this->input->post('cyberware_essence'));
    	$data = array(
    			'name' => $this->input->post('itemname'),
    			'cost' => $this->input->post('cost'),
    			'description' => $this->input->post('description'),
    			'type' => $this->input->post('type'),
    			'ammo' => $this->input->post('ammo'),
    			'damage' => $damage,
    			'mode' => $mode,
    			'reduce' => $this->input->post('reduce'),
    			'armor' => $this->input->post('armor'),
    			'reach' => $this->input->post('reach'),
    			'cyberware_type' => $this->input->post('cyberware_type'), 
    			'cyberware_ini' => $this->input->post('cyberware_ini'), 
    			'cyberware_reaction' => $this->input->post('cyberware_reaction'), 
    			'cyberware_armor' => $this->input->post('cyberware_armor'), 
    			'cyberware_mw' => $this->input->post('cyberware_mw'), 
    			'cyberware_body' => $this->input->post('cyberware_body'),
    			'cyberware_quickness' => $this->input->post('cyberware_quickness'),
    			'cyberware_essence' => $essenz,
    			'cyberware_strength' => $this->input->post('cyberware_strength'),
    			'cyberware_intelligence' => $this->input->post('cyberware_intelligence'),

    		);
		return ($this->db->insert('weapons', $data)) ? true : false;
    }


    function editItem () {
    	$bmode=$this->input->post('mode');
    	if (!empty($bmode)) {
    		$mode = implode(';', $this->input->post('mode'));
    	}
    	$essenz = str_replace(',', '.', $this->input->post('cyberware_essence'));    	
    	$damage = ($this->input->post('type') == 'melee') ? $this->input->post('melee_damage') : $this->input->post('damage');

    	$data = array(
    			'name' => $this->input->post('itemname'),
    			'cost' => $this->input->post('cost'),
    			'description' => $this->input->post('description'),
    			'type' => $this->input->post('type'),
    			'ammo' => $this->input->post('ammo'),
    			'damage' => $damage,
    			'mode' => $mode,
    			'reduce' => $this->input->post('reduce'),
    			'armor' => $this->input->post('armor'), 
    			'reach' => $this->input->post('reach'),
    			'cyberware_type' => $this->input->post('cyberware_type'), 
    			'cyberware_ini' => $this->input->post('cyberware_ini'), 
    			'cyberware_reaction' => $this->input->post('cyberware_reaction'), 
    			'cyberware_armor' => $this->input->post('cyberware_armor'), 
    			'cyberware_mw' => $this->input->post('cyberware_mw'), 
    			'cyberware_body' => $this->input->post('cyberware_body'),
    			'cyberware_quickness' => $this->input->post('cyberware_quickness'),
    			'cyberware_essence' => $essenz,
    			'cyberware_strength' => $this->input->post('cyberware_strength'),
    			'cyberware_intelligence' => $this->input->post('cyberware_intelligence'),   			
    		);
    		$this->db->where('wid', $this->input->post('wid'));
		return ($this->db->update('weapons', $data)) ? true : false;
    }

	function getAllItems () {
		$this->db->order_by('name', 'ASC');
		return $this->db->get('weapons')->result_array();
	}    

	function getItemById () {
		$wid = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('wid');
		return $this->db->get_where('weapons', array('wid' => $wid))->result_array();
	}

	function sendInternMessage($pass) {
		$user = $this->db->get_where('login', array('id' => $this->session->userdata('id')))->result_array();

		$msg = ucfirst($user[0]['nickname'])." hat dir eine Freundschaftsanfrage gesendet.<br /><br />";
		$msg .= "Klicke auf Annehmen um diese zu akzeptieren, oder auf Ablehnen um sie zu blocken.<br /><br />";
		$msg .= "<a href='/secure/snn/desktop/friendship/1/".$pass."/".$this->session->userdata('id')."/".$this->input->post('id')."'><b>Annehmen</b></a><br /><br />";
		$msg .= "<a href='/secure/snn/desktop/friendship/0/".$pass."/".$this->session->userdata('id')."/".$this->input->post('id')."'><b>Ablehnen</b></a><br />";

		$data = array(
			'title' => "Freundschaftsanfrage von ".ucfirst($user[0]['nickname']),
			'msg_text' => $msg,
			'send_to' =>  $this->input->post('id'),
			'send_from' =>  $this->session->userdata('id'),
			'parent' => '0',
			'date' => time(),			
		);		
        return ($this->db->insert('messages', $data)) ? true : false;            

	}
	

	/** Utility Methods **/
	function GetUser($username, $pass) {
			$this->db->select('id, name, nickname, type, rank');
			$this->db->where('name', $username);	
			$this->db->where('privatekey', $pass);	
			$query = $this->db->get("login");
			return $query->result_array();
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

	function editCharacter() {
		$this->db->select('cid');
		$this->db->from('chars');
		$this->db->where('uid', $this->session->userdata('id'));
		$query = $this->db->get();
		$cid = $query->result_array();

		$reaction = floor(($this->input->post('quickness')+$this->input->post('intelligence'))/2);
		$essence = '6';
		$inidice = '1';
		$char = array(
			'uid' => $this->session->userdata('id'),
			'charname' => ucfirst($this->input->post('charname')),
			'race' => $this->input->post('race'),
			'body' => $this->input->post('body'),
			'quickness' => $this->input->post('quickness'),
			'strength' => $this->input->post('strength'),
			'charisma' => $this->input->post('charisma'),
			'intelligence' => $this->input->post('intelligence'),
			'willpower' => $this->input->post('willpower'),
			'essence' => $essence,
			'magic' => $this->input->post('magic'),
			'armed_longrange' => $this->input->post('armed_longrange'),
			'armed_combat' => $this->input->post('armed_combat'),
			'inidice' => $inidice,
			'reaction_mod' => 0,

		);

		if ($cid[0]['cid']) {
			$this->db->where('cid', $cid[0]['cid']);
			return ($this->db->update('chars', $char)) ? true : false;			
		} else {
			if ($this->db->insert('chars', $char)) {
				$inv = array(
					'cid' => $this->db->insert_id(),
					'money' => '1000',
					'wid' => '',
					'medipacks' => '0',
					'grenades' => '0',
					'maxammo' => '0',
					'aid' => '',
				);
				return ($this->db->insert('inventory', $inv)) ? true : false;
			}			
		}
		#echo "TEST ".$this->session->userdata('id');
		#$this->util->_debug($this->input->post());
		#$this->util->_debug($cid);
	}

	function getAllCharacters() {
		return $this->db->get('chars')->result_array();
	}
	
	function getCharacter() {
		$this->db->select('*');
		$this->db->from('chars');
		$this->db->where('uid', $this->session->userdata('id'));
		$query = $this->db->get()->result_array();

		if (!empty($query)) {
			$avatar = $this->db->get_where('login', array('id' => $query[0]['uid']))->result_array();
			$query[0]['avatar'] = $avatar[0]['avatar'];
			
			$this->session->set_userdata('charname', $query[0]['charname']);
			$this->session->set_userdata('charid', $query[0]['cid']);
			
			return $query;
		} else {
			return false;
		}
	}	
	
	function getCharacterAndInventory() {
		$this->db->select('*');
		$this->db->from('chars');
		$this->db->where('uid', $this->session->userdata('id'));
		$query = $this->db->get()->result_array();
		$data = array();
		$inv = $this->combat_model->getInventory();
		
		if (!empty($query)) {
			$avatar = $this->db->get_where('login', array('id' => $query[0]['uid']))->result_array();
			$query[0]['avatar'] = $avatar[0]['avatar'];	
			$query[0]['inidice_mod'] = 0;
			if (!empty($inv[0]['cyberware'])) {
				foreach($inv[0]['cyberware'] as $c) {
					$query[0]['reaction_mod'] = ($c['cyberware_reaction'] > 0) ? ($query[0]['reaction_mod']+$c['cyberware_reaction']) : $query[0]['reaction_mod'];					
					$query[0]['inidice_mod'] = ($c['cyberware_ini'] > 0) ?  ($query[0]['inidice_mod']+$c['cyberware_ini']) : $query[0]['inidice_mod'];

#					$c_armor = ($c['cyberware_armor'] > 0) ? $c_armor+(int)($c['cyberware_armor']) : $c_armor;
#					$c_mw = ($c['cyberware_mw'] > 0) ? $c_mw+(int)($c['cyberware_mw']) : $c_mw;
					$query[0]['strength'] =($c['cyberware_strength'] > 0) ? $query[0]['strength']+(int)($c['cyberware_strength']).'*' : $query[0]['strength'];
					$query[0]['quickness'] = ($c['cyberware_quickness'] > 0) ? $query[0]['quickness']+(int)($c['cyberware_quickness']).'*' : $query[0]['quickness'];
					$query[0]['body'] = ($c['cyberware_body'] > 0) ? $query[0]['body']+(int)($c['cyberware_body']).'*' : $query[0]['body'];
					$query[0]['intelligence'] = ($c['cyberware_intelligence'] > 0) ? $query[0]['intelligence']+(int)($c['cyberware_intelligence']).'*' : $query[0]['intelligence'];
				}
			}
			$data['char'] = $query;
			$data['inv'] = $inv;

			return $data;
		} else {
			return false;
		}
	}

	function getForeignCharacterAndInventory() {
		$this->db->where('uid', $this->session->userdata('id'));
		$this->db->where('fid', $this->uri->segment(3));
		$friend = $this->db->count_all_results('friends');

		if ($this->session->userdata('rank') <= 1 || $friend == 1) {
			$this->db->select('*');
			$this->db->from('chars');
			$this->db->where('uid', $this->uri->segment(3));
			$query = $this->db->get()->result_array();
			$data = array();

			$inv = $this->combat_model->getInventory($query[0]['cid']);
		
			if (!empty($query)) {
				$avatar = $this->db->get_where('login', array('id' => $query[0]['uid']))->result_array();
				$query[0]['avatar'] = $avatar[0]['avatar'];
				$query[0]['inidice_mod'] = 0;
				if (!empty($inv[0]['cyberware'])) {
					foreach($inv[0]['cyberware'] as $c) {
						$query[0]['reaction_mod'] = ($c['cyberware_reaction'] > 0) ? $query[0]['reaction_mod']+$c['cyberware_reaction'] : $query[0]['reaction_mod'];
						$query[0]['inidice_mod'] = ($c['cyberware_ini'] > 0) ?  $query[0]['inidice_mod']+$c['cyberware_ini'] : $query[0]['inidice_mod'];
		
						#					$c_armor = ($c['cyberware_armor'] > 0) ? $c_armor+(int)($c['cyberware_armor']) : $c_armor;
						#					$c_mw = ($c['cyberware_mw'] > 0) ? $c_mw+(int)($c['cyberware_mw']) : $c_mw;
						$query[0]['strength'] =($c['cyberware_strength'] > 0) ? $query[0]['strength']+(int)($c['cyberware_strength']).'*' : $query[0]['strength'];
						$query[0]['quickness'] = ($c['cyberware_quickness'] > 0) ? $query[0]['quickness']+(int)($c['cyberware_quickness']).'*' : $query[0]['quickness'];
						$query[0]['body'] = ($c['cyberware_body'] > 0) ? $query[0]['body']+(int)($c['cyberware_body']).'*' : $query[0]['body'];
						$query[0]['intelligence'] = ($c['cyberware_intelligence'] > 0) ? $query[0]['intelligence']+(int)($c['cyberware_intelligence']).'*' : $query[0]['intelligence'];
					}
				}
				$data['char'] = $query;
				$data['inv'] = $inv;
		
				return $data;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getAllGanger() {
		$this->db->select('*');
		$this->db->from('ganger');
		$this->db->order_by('level');
		$query = $this->db->get();
		return $query->result_array();
	}	

	public function getGanger($gid) {
		$id = ($gid) ? $gid : $this->input->post('gid');
		$this->db->select('*');
		$this->db->from('ganger');
		$this->db->where('gid', $id);
		$query = $this->db->get();
		return $query->result_array();
	}		

	function checkGangerName() {
		if(strlen($this->input->post('gangername')) > '3') {
			$this->db->select('gid');
			$this->db->from('ganger');
			$this->db->where('ganger_name', $this->input->post('gangername'));
			$query = $this->db->get();
			$gid = $query->result_array();
			if (is_array($gid[0])) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}


	function getGangerValues($level) {
		$stats = array();
		$attributes = array('body', 'quickness', 'strength', 'charisma', 'intelligence', 'willpower', 'magic');
		foreach ($attributes as $a) {
			$stats[$a] = rand($level,($level+2));
		}
		$stats['armed_combat'] = $level;
		$stats['armed_longrange'] = $level;
		return $stats;
	}	

	function removeArchetypValues ($stats, $oldtyp) {
		$ganger = array('armed_combat' => '1');
		$lonestar = array('armed_longrange' => '1');
		$security = array('armed_longrange' => '2');
		foreach ($stats as $key => $value) {
			if(in_array($key, array_keys(${$oldtyp}))) {
				if (substr(${$oldtyp}[$key], 0,1) == '+') {
					$stats[$key] = $stats[$key]+${$oldtyp}[$key];
				} else {
					$stats[$key] = ($stats[$key]-${$oldtyp}[$key] < '1') ? '1' : $stats[$key]-${$oldtyp}[$key];

				}
			}		
		}
	}

	function addArchetypValues ($stats, $typ) {
		$ganger = array('armed_combat' => '+1');
		$lonestar = array('armed_longrange' => '+1');
		$security = array('armed_longrange' => '+2');
		foreach ($stats as $key => $value) {
			if(in_array($key, array_keys(${$typ}))) {
				if (substr(${$typ}[$key], 0,1) == '+') {
					$stats[$key] = $stats[$key]+${$typ}[$key];
				} else {
					$stats[$key] = ($stats[$key]-${$typ}[$key] < '1') ? '1' : $stats[$key]-${$typ}[$key];

				}
			}		
		}			
		return $stats;
	}

	function removeRaceValues($stats, $oldrace) {
		if ($oldrace != 'mensch') {
			$elf = array('quickness' => '1', 'charisma' => '2');
			$zwerg = array('body' => '1', 'strength' => '2', 'willpower' => '1');
			$ork = array('body' => '3', 'strength' => '2', 'charisma' => '+1', 'intelligence' => '+1');
			$troll = array('body' => '5', 'strength' => '4', 'quickness' => '+1', 'charisma' => '+2', 'intelligence' => '+2');
			foreach ($stats as $key => $value) {
				if(in_array($key, array_keys(${$oldrace}))) {

					if (substr(${$oldrace}[$key], 0,1) == '+') {
						$stats[$key] = $stats[$key]+${$oldrace}[$key];
					} else {
						$stats[$key] = ($stats[$key]-${$oldrace}[$key] < '1') ? '1' : $stats[$key]-${$oldrace}[$key];

					}
				}
			}			
		}
		return $stats;
	}

	function addRaceValues ($stats, $race) {
		if ($race != 'mensch') {
			$elf = array('quickness' => '+1', 'charisma' => '+2');
			$zwerg = array('body' => '+1', 'strength' => '+2', 'willpower' => '+1');
			$ork = array('body' => '+3', 'strength' => '+2', 'charisma' => '1', 'intelligence' => '1');
			$troll = array('body' => '+5', 'strength' => '+4', 'quickness' => '1', 'charisma' => '2', 'intelligence' => '2');

			foreach ($stats as $key => $value) {
				if(in_array($key, array_keys(${$race}))) {

					if (substr(${$race}[$key], 0,1) == '+') {
						$stats[$key] = $stats[$key]+${$race}[$key];
					} else {
						$stats[$key] = ($stats[$key]-${$race}[$key] < '1') ? '1' : $stats[$key]-${$race}[$key];

					}
				}
			}
		}
		return $stats;				
	}

	function deleteGanger () {
		$this->db->select('mid, gid');
		$this->db->where('gid LIKE ', '%'.$this->input->post('gid').'%');
		$missions = $this->db->get('missions')->result_array();
		if (!empty($missions)) {
			foreach($missions as $m) {
				$tmp = explode(';', $m['gid']);
				if(($key = array_search($this->input->post('gid'), $tmp)) !== false) {
					unset($tmp[$key]);
				}
				$updated = implode(';', $tmp);
				$arr = array('gid' => $updated);
				$this->db->where('mid', $m['mid']);
				$this->db->update('missions', $arr);
			
			}
		}

		$this->db->where('gid', $this->input->post('gid'));
		return ($this->db->delete('ganger')) ? 'true' : 'false';
	}

	function editGanger () {
		$old = $this->getGanger($this->input->post('gid'));
		$ganger = array(
			'ganger_name' => $this->input->post('gangername'),
			'race' => $this->input->post('gangerrace'),
			'gender' => $this->input->post('gangergender'),
			'level' => $this->input->post('gangerlevel'),
			'type' => $this->input->post('gangertype'),
			'bio' => $this->input->post('gangerbio'),
			'profile' => $this->input->post('gangerportrait'),
			'archetyp' => $this->input->post('gangerarchetyp'),
		);			
		if ($this->input->post('gangerwerteneu') == '1'){
			$stats = $this->getGangerValues($this->input->post('gangerlevel'));

			$stats = $this->addRaceValues($stats, $this->input->post('gangerrace'));

			$ganger['body'] = $stats['body'];
			$ganger['quickness'] = $stats['quickness'];
			$ganger['strength'] = $stats['strength'];
			$ganger['charisma'] = $stats['charisma'];
			$ganger['intelligence'] = $stats['intelligence'];
			$ganger['willpower'] = $stats['willpower'];
			$ganger['magic'] = $stats['magic'];
			$ganger['reaction'] = floor(($stats['quickness']+$stats['intelligence'])/2);
			$stats = $this->addArchetypValues($stats, $this->input->post('gangerarchetyp'));
			$ganger['armed_combat'] = $stats['armed_combat'];
			$ganger['armed_longrange'] = $stats['armed_longrange'];

		} else {
			$stats = array(
				'body' => $old[0]['body'], 
				'quickness' => $old[0]['quickness'], 
				'strength' => $old[0]['strength'], 
				'charisma' => $old[0]['charisma'], 
				'intelligence' => $old[0]['intelligence'], 
				'willpower' => $old[0]['willpower'], 
				'magic' => $old[0]['magic'], 
				'armed_combat' => $old[0]['armed_combat'], 
				'armed_longrange' => $old[0]['armed_longrange'], 
			);
			if ($old[0]['race'] != $this->input->post('gangerrace')) {				
				$stats = $this->addRaceValues($stats, $this->input->post('gangerrace'));
				$stats = $this->removeRaceValues($stats, $old[0]['race']);					
					$ganger['body'] = $stats['body'];
					$ganger['quickness'] = $stats['quickness'];
					$ganger['strength'] = $stats['strength'];
					$ganger['charisma'] = $stats['charisma'];
					$ganger['intelligence'] = $stats['intelligence'];
					$ganger['willpower'] = $stats['willpower'];
					$ganger['magic'] = $stats['magic'];
					$ganger['reaction'] = floor(($stats['quickness']+$stats['intelligence'])/2);
				
				
				#_debug($ganger);
				#die();				
			}
			if ($old[0]['archetyp'] != $this->input->post('gangerarchetyp')) {
				$stats = $this->addArchetypValues($stats, $this->input->post('gangerarchetyp'));
				$stats = $this->removeArchetypValues($stats, $old[0]['archetyp']);
				$ganger['armed_combat'] = $stats['armed_combat'];
				$ganger['armed_longrange'] = $stats['armed_longrange'];
			}					
		}

		$this->db->where('gid', $this->input->post('gid'));
		return ($this->db->update('ganger', $ganger)) ? 'true' : 'false';
		
	}

	function generateGanger() {
		$this->db->select('gid');
		$this->db->from('ganger');
		$this->db->where('ganger_name', $this->input->post('gangername'));
		$query = $this->db->get();
		$gid = $query->result_array();

		if (!empty($gid)) {
			return false; 
		} else {
			$stats = $this->getGangerValues($this->input->post('gangerlevel'), $this->input->post('gangerrace'));
			$stats = $this->addRaceValues($stats, $this->input->post('gangerrace'));
			$stats = $this->addArchetypValues($stats, $this->input->post('gangerarchetyp'));			
			$ganger = array(
					'ganger_name' => $this->input->post('gangername'),
					'race' => $this->input->post('gangerrace'),
					'gender' => $this->input->post('gangergender'),
					'level' => $this->input->post('gangerlevel'),
					'type' => $this->input->post('gangertype'),
					'bio' => $this->input->post('gangerbio'),
					'profile' => $this->input->post('gangerportrait'),
					'archetyp' => $this->input->post('gangerarchetyp'),
					'body' => $stats['body'],
					'quickness' => $stats['quickness'],
					'strength' => $stats['strength'],
					'charisma' => $stats['charisma'],
					'intelligence' => $stats['intelligence'],
					'willpower' => $stats['willpower'],
					'magic' => $stats['magic'],
					'armed_combat' => $stats['armed_combat'],
					'armed_longrange' => $stats['armed_longrange'],
					'reaction' => floor(($stats['quickness']+$stats['intelligence'])/2),					

				);
			return ($this->db->insert('ganger', $ganger)) ? true : false;
		}
	}



	function checkMissionTitle() {
		return true;
	}


	function getAllMissions() {
		$this->db->select('mid, title');
		$this->db->from('missions');
		$this->db->order_by('title');
		$query = $this->db->get();
		return $query->result_array();		
	}

	function getMission () {
		$this->db->select('*');
		$this->db->from('missions');
		$this->db->where('mid', $this->input->post('mid'));
		$query = $this->db->get();
		return $query->result_array();		
	}
	function getSpecialMission () {
		$this->db->where('charid', $this->session->userdata('charid'));
		$this->db->where('charid != ', '');
		$this->db->where('mission_played', 0);
		return $this->db->get('missions')->result_array();
	}
	
	

	function generateMission () {
		#_debugDie($this->input->post());
		$this->db->select('mid');
		$this->db->from('missions');
		$this->db->where('title', $this->input->post('missionstitle'));
		$query = $this->db->get();
		$mid = $query->result_array();

		if (!empty($mid)) {
			return false; 
		} else {
			$mission = array(
					'title' => $this->input->post('missionstitle'),
					'level' => $this->input->post('missionlevel'),
					'text' => $this->input->post('missionstext'),
					'text_win' => $this->input->post('missionswintext'),
					'text_loss' => $this->input->post('missionslosstext'),
					'text_story' => $this->input->post('missionsstorytext'),
					'johnson' => $this->input->post('johnson'),					
					'storyimage' => $this->input->post('storyimage'),
					'type' => $this->input->post('missiontype'),
					'cash' => $this->input->post('missioncash'),
					'expense' => $this->input->post('missionexpense'),
					'extras' => $this->input->post('missionextras'),
					'member' => $this->input->post('missionmember'),
					'image' => $this->input->post('missionsimage'),
					'gid' => implode(';', $this->input->post('missionganger')),
					'special' => $this->input->post('special'),				
					'specialitem' => $this->input->post('storyitem'),
					'charid' => $this->input->post('character'),
			);
			
			return ($this->db->insert('missions', $mission)) ? true : false;
		}		
	}

	function editMission() {
		#_debugDie($this->input->post());
		if ($this->input->post('missiondelete')) {
			$this->db->where('mid', $this->input->post('mid'));
			return ($this->db->delete('missions')) ? 'true' : 'false';
		} else {
			$mission = array(
					'title' => $this->input->post('missionstitle'),
					'level' => $this->input->post('missionlevel'),
					'text' => $this->input->post('missionstext'),
					'text_win' => $this->input->post('missionswintext'),
					'text_loss' => $this->input->post('missionslosstext'),
					'text_story' => $this->input->post('missionsstorytext'),
					'johnson' => $this->input->post('johnson'),					
					'storyimage' => $this->input->post('storyimage'),					
					'type' => $this->input->post('missiontype'),
					'cash' => $this->input->post('missioncash'),
					'expense' => $this->input->post('missionexpense'),
					'extras' => $this->input->post('missionextras'),
					'member' => $this->input->post('missionmember'),
					'image' => $this->input->post('missionsimage'),
					'gid' => implode(';', $this->input->post('missionganger')),
				);
			$this->db->where('mid', $this->input->post('mid'));
			return ($this->db->update('missions', $mission)) ? 'true' : 'false';
		}
	}	

	function getCategories () {
		$this->db->order_by('cat_name', 'ASC');
		$query = $this->db->get('category');
		return $query->result_array();
	}

	function insertNews () {
		$teaser = $this->input->post('newstext');
		$teaser = strip_tags($teaser);
		
		$news = array (
				'title' => $this->input->post('title'),
				'newstext' => $this->input->post('newstext'),
				'category' => $this->input->post('category'),
				'date' => time(),
				'teaser' => $teaser,
			);
		return ($this->db->insert('news', $news)) ? true : false;
	}
	
	function insertSystemNews () {
		$news = array (
				'title' => $this->input->post('title'),
				'text' => $this->input->post('newstext'),
				'online' => 0,
		);
		return ($this->db->insert('notes', $news)) ? true : false;
	}

	function editNews () {
		$teaser = $this->input->post('newstext');
		$teaser = strip_tags($teaser);
		
		$news = array (
				'title' => $this->input->post('title'),
				'newstext' => $this->input->post('newstext'),
				'category' => $this->input->post('category'),
				'date' => time(),
				'teaser' => $teaser,
			);	
		return ($this->db->update('news', $news, array('id' => $this->input->post('id')))) ? true : false;	

	}

	function getNews () {
		$id = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id');
		$query = $this->db->get_where('news', array('id' => $id));
		return  $query->result_array();
	}

	function countNews() {
		$this->db->where('deleted', '0');
		return $this->db->count_all('news');
	}

	function deleteNews () {
		$news = array (
				'deleted' => '1',
			);	
		return ($this->db->update('news', $news, array('id' => $this->input->post('id')))) ? true : false;
	}

	function avatar () {

		$config['upload_path'] = FCPATH.'assets/img/avatar/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = TRUE;
		$config['file_name'] = $this->session->userdata('id').'_avatar';
		$this->upload->initialize($config);

		if($this->upload->do_upload('avatar')) {
			$upload_data = $this->upload->data();
			$img_config['image_library'] = 'gd2';
	        $img_config['source_image'] = $upload_data['full_path'];
	        $img_config['maintain_ratio'] = TRUE;
	        $img_config['width'] = '75';
	        $img_config['height'] = '100';

	        $this->load->library('image_lib', $img_config); 
        	if ($this->image_lib->resize()) {
        		$news = array (
					'avatar' => $upload_data['orig_name'],
				);	
				return ($this->db->update('login', $news, array('id' => $this->session->userdata('id')))) ? true : false;
        	} else {
    			return false;
			}
		} else {
			return false;
		}					
	}
	
	function insertAds () {
		$config['upload_path'] = FCPATH.'assets/img/uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = TRUE;
		$config['file_name'] = 'banner_'.time().'_'.$_FILES['adsimage']['name'];
		
		$this->upload->initialize($config);
		
		
		if($this->upload->do_upload('adsimage')) {
			$upload_data = $this->upload->data();
			
			$img_config['image_library'] = 'gd2';
	        $img_config['source_image'] = $upload_data['full_path'];			
	        $img_config['maintain_ratio'] = TRUE;
	        $img_config['width'] = '250';
			$img_config['height'] = '350';
			$this->load->library('image_lib', $img_config); 
			
			if ($this->image_lib->resize()) {
        		$news = array (
					'image' => $upload_data['orig_name'],
					'text' => $this->input->post('adstext'),
					'title' => $this->input->post('title'),
				);	
				return ($this->db->insert('banner', $news)) ? true : false;
        	} else {
    			return false;
			}
		} else {
			return false;
		}						
	}

	function editAds () {
		if ($this->input->post('deleteold')) {
			unlink(FCPATH.'assets/img/uploads/'.$this->input->post('old_image'));
			$config['upload_path'] = FCPATH.'assets/img/uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite'] = TRUE;
			$config['file_name'] = 'banner_'.time().'_'.$_FILES['adsimage']['name'];
		
			$this->upload->initialize($config);
			if($this->upload->do_upload('adsimage')) {
				$upload_data = $this->upload->data();
				
				$img_config['image_library'] = 'gd2';
		        $img_config['source_image'] = $upload_data['full_path'];			
		        $img_config['maintain_ratio'] = TRUE;
		        $img_config['width'] = '250';
				$img_config['height'] = '350';
				$this->load->library('image_lib', $img_config); 
				
				$this->image_lib->resize();
				$image = $upload_data['orig_name'];
        	} else {
    			return false;
			}			
		} else{
			$image = $this->input->post('old_image');
		}

		$news = array (
				'image' => $image,
				'text' => $this->input->post('adstext'),
				'title' => $this->input->post('title'),				
			);
		return ($this->db->update('banner', $news, array('id' => $this->input->post('id')))) ? true : false;
	}
	
	function insertCategory () {
		$config['upload_path'] = FCPATH.'assets/img/news/icons/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = TRUE;
		$config['file_name'] = 'news_cat_'.time().'_'.$_FILES['catimage']['name'];
		
		$this->upload->initialize($config);
		if($this->upload->do_upload('catimage')) {
			$upload_data = $this->upload->data();
			
			$img_config['image_library'] = 'gd2';
	        $img_config['source_image'] = $upload_data['full_path'];			
	        $img_config['maintain_ratio'] = TRUE;
	        $img_config['width'] = '100';
			$img_config['height'] = '75';
			$this->load->library('image_lib', $img_config); 
			
			if ($this->image_lib->resize()) {
        		$news = array (
					'icon' => $upload_data['orig_name'],
					'cat_name' => $this->input->post('cat_name'),
					'autor' => $this->input->post('autor'),
				);	
				return ($this->db->insert('category', $news)) ? true : false;
        	} else {
    			return false;
			}
		} else {
			return false;
		}	
	}

	function deleteAvatar() {
		$query = $this->db->get_where('login', array('id' => $this->input->post('id')));
		$data =  $query->result_array();
		$news = array (
				'avatar' => '',
			);	
		return ($this->db->update('login', $news, array('id' => $this->input->post('id')))) ? true : false;		
	}

	function deleteAds () {
		return ($this->db->delete('banner', array('id' => $this->input->post('id')))) ? true : false;	
	}

	function getAds () {
		$id = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id');
		$query = $this->db->get_where('banner', array('id' => $id));
		return $query->result_array();
	}

	function deleteCategory () {
		return ($this->db->delete('category', array('id' => $this->input->post('id')))) ? true : false;			
	}

	function getCategory () {
		$id = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id');
		$query = $this->db->get_where('category', array('id' => $id));
		return $query->result_array();
	}

	function editCategory () {
		#die($this->util->_debug($this->input->post()));
		if ($this->input->post('delete_oldimage')) {
			unlink(FCPATH.'assets/img/news/icons/'.$this->input->post('old_icon'));
			$config['upload_path'] = FCPATH.'assets/img/news/icons/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite'] = TRUE;
			$config['file_name'] = 'cat_news_'.time().'_'.$_FILES['catimage']['name'];
		
			$this->upload->initialize($config);
			if($this->upload->do_upload('catimage')) {
				$upload_data = $this->upload->data();
				
				$img_config['image_library'] = 'gd2';
		        $img_config['source_image'] = $upload_data['full_path'];			
		        $img_config['maintain_ratio'] = TRUE;
		        $img_config['width'] = '100';
				$img_config['height'] = '75';
				$this->load->library('image_lib', $img_config); 
				
				$this->image_lib->resize();
				$image = $upload_data['orig_name'];
        	} else {
    			return false;
			}			
		} else{
			$image = $this->input->post('old_icon');
		}

		$news = array (
				'icon' => $image,
				'cat_name' => $this->input->post('cat_name'),
				'autor' => $this->input->post('autor'),
			);
		return ($this->db->update('category', $news, array('id' => $this->input->post('id')))) ? true : false;
	}

	function uploadImages () {
		$config['upload_path'] = FCPATH.'assets/img/combat/'.$this->input->post('imagetype').'/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = TRUE;
		$config['file_name'] = $this->input->post('imagetype').'_'.time();

		$this->upload->initialize($config);
		if($this->upload->do_upload('image')) {
			if($this->input->post('imagetype') == 'storyimage') {
				return true;
			}
			$upload_data = $this->upload->data();
			
			$img_config['image_library'] = 'gd2';
	        $img_config['source_image'] = $upload_data['full_path'];			
	        $img_config['maintain_ratio'] = TRUE;
			if($this->input->post('imagetype') == 'johnson' || $this->input->post('imagetype') == 'ganger') {
		        $img_config['width'] = '110';
				$img_config['height'] = '110';			
			} else if($this->input->post('imagetype') == 'missionsbanner') {
				$img_config['master_dim'] = 'width';
		        $img_config['width'] = '800';
				$img_config['height'] = '100';		
			}

			$this->load->library('image_lib', $img_config); 
			
			$this->image_lib->resize();
			$image = $upload_data['orig_name'];	
			return true;
		} else {
			#echo $this->upload->display_errors();die();
			return false;
		}	
	}

	function deleteUpload () {
		if(unlink(FCPATH.'assets/img/combat/'.$this->input->post('type').'/'.$this->input->post('source'))) {
			return true;
		} else {
			return false;
		}
	}

	function newUser () {
		$user = array(
				'name' => $this->input->post('username'), 
				'nickname' => $this->input->post('username'), 
				'privatekey' => md5($this->input->post('password')),
				'rank' => $this->input->post('rank'), 
				'type' => $this->input->post('type'), 
				'avatar' => '',
			);
		return ($this->db->insert('login', $user)) ? true : false;
	}

	function getLoginUsers () {
		return $this->db->get('login')->result_array();
	}

	function deleteLoginUser () {
		return ($this->db->delete('login', array('id' => $this->input->post('id')))) ? true : false;		
	}

	function lastOnline () {
		$this->db->select('login.name, active.lastactive, active.page');
		$this->db->from('active');
		$this->db->join('login', 'login.id = active.uid');
		$this->db->order_by('active.lastactive', 'DESC');
		return $this->db->get()->result_array();
	}
	
	function writeSettings () {
		$data = array (
			'show_shoutbox' => $this->input->post('show_shoutbox'),
			'show_friends' => $this->input->post('show_friends'),
			'show_msgbox' => $this->input->post('show_msgbox'),
			'show_ads' => $this->input->post('show_ads'),
			'show_own_messages' => $this->input->post('show_own_messages'),
		);
		return ($this->db->update('login', $data, array('id' => $this->session->userdata('id')))) ? true : false;
	}
	
	function readSettings () {
		$this->db->select('show_shoutbox, show_friends, show_msgbox, show_ads, show_own_messages');
		$this->db->from('login');
		$this->db->where('id', $this->session->userdata('id'));
		return $this->db->get()->result_array();
	}
	
	function importItems () {
		require(FCPATH.'assets/excel/php-excel-reader/excel_reader2.php');
		require(FCPATH.'assets/excel/SpreadsheetReader_XLSX.php');

		$config['upload_path'] = FCPATH.'tmp';
		$config['allowed_types'] = 'xlsx';
		$config['overwrite'] = TRUE;
		$config['max_size']= '1000';
		
		$this->upload->initialize($config);
		
		if(!$this->upload->do_upload('itemfile')) {
			return false;
		} else {
			$data = array();
			$zdata = array('upload_data' => $this->upload->data());
			$Reader = new SpreadsheetReader_XLSX($zdata['upload_data']['full_path']);
			foreach ($Reader as $row) {
				$data = array (
						"name" => $row[0],
						"ammo" => $row[1],
						"damage" => $row[2],
						"description" => $row[3],
						"mode" => $row[4],
						"cost" => $row[5],
						"reduce" => $row[6],
						"type" => $row[7],
						"subtype" => $row[8],
						"armor" => $row[9],
						"reach" =>  $row[10],
						"cyberware_type" =>  $row[11],
						"cyberware_ini" =>  $row[12],
						"cyberware_reaction" =>  $row[13],
						"cyberware_armor" =>  $row[14],
						"cyberware_mw" =>  $row[15],
						"cyberware_essence" =>  $row[16],
						"cyberware_strength" =>  $row[17],
						"cyberware_quickness" =>  $row[18],
						"cyberware_body" =>  $row[19],
						"cyberware_intelligence" =>  $row[20]
				);
				if(!$this->db->insert('weapons', $data)) {
					return false;
				}
			}
			return true;
		}
	}
	
	function generateStoryitem () {
		$config['upload_path'] = FCPATH.'assets/img/combat/storyitem/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = TRUE;
		$config['file_name'] = 'storyitem_'.time();
		
		$this->upload->initialize($config);
// 		_debug($_FILES);
// 		_debug($this->upload->data('image'));
// 		_debugDie($this->input->post());
		if ($_FILES['image'][tmp_name] != '') {
			if($this->upload->do_upload('image')) {
				$upload_data = $this->upload->data();
					
				$img_config['image_library'] = 'gd2';
				$img_config['source_image'] = $upload_data['full_path'];
				$img_config['maintain_ratio'] = TRUE;
				$img_config['width'] = '400';
				$img_config['height'] = '400';
			
				$this->load->library('image_lib', $img_config);
					
				$this->image_lib->resize();
				$image = $upload_data['file_name'];
				
				$data = array(
						'itemname' => $this->input->post('itemname'),
						'itemtext' => $this->input->post('itemtext'),
						'image' => $image,
				);
			} else {
				return false;
			}
		} else {
			$data = array(
					'itemname' => $this->input->post('itemname'),
					'itemtext' => $this->input->post('itemtext'),
					'image' => '',
			);
		}
		return ($this->db->insert('items', $data)) ? true : false;
	}
	
	function getStoryItems () {
		$this->db->order_by('itemname', 'ASC');
		return $this->db->get('items')->result_array();
	}
	
	function getSystemNews () {
		$data = array('online' => '1');
		return $this->db->get_where('notes', $data)->result_array();
	}
}

?>	