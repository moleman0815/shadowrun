<?php

/**
 * App_Data_Model
 * 
 * @package App
 */

class Combat_model extends CI_Model
{
	var $player = array();
	var $enemy = array();
	var $ini = array();
	var $iniphase;
	var $enemies;
	var $status = 'running';
	var $combatlog = array();
	var $fighters = array();
	var $level;
	var $round = 1;
	var $fighterinround;
	var $inicounter;
	var $cash;
	var $lost;

	function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('add_functions');	
		$this->load->library('combat');		
    }
	
	function getAllMissions ($level) {
		$this->db->select('*');
		$this->db->from('missions');
		$this->db->where('level', $level);
		$query = $this->db->get();
        return $query->result_array();
	}
	
	function getMissionData () {
		$mid = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->session->userdata['missionlevel'];
		$query = $this->db->get_where('missions', array('mid' => $mid));
		return $query->result_array();
	}
	
	function getInventory () {
		$inv = $this->db->get_where('inventory', array('cid' => $this->session->userdata('charid')))->result_array();
		#echo $inv[0]['wid'];
		if (!empty($inv[0]['wid'])) {
			$weapons = explode(';', $inv[0]['wid']);
			for ($x=0;$x<count($weapons);$x++) {
				$weapon = $this->db->get_where('weapons', array('wid' => $weapons[$x]))->result_array();		
#				_debug($weapon);
				$inv[0]['weapon'][$x] = $weapon[0];
			}
		}

		if (!empty($inv[0]['aid'])) {
			$armor = explode(';', $inv[0]['aid']);
			for ($x=0;$x<count($armor);$x++) {
				$armors = $this->db->get_where('weapons', array('wid' => $armor[$x]))->result_array();		
				$inv[0]['armor'][$x] = $armors[0];
			}
		}

		if (!empty($inv[0]['cyberid'])) {
			$cyber = explode(';', $inv[0]['cyberid']);
			for ($x=0;$x<count($cyber);$x++) {
				$cybers = $this->db->get_where('weapons', array('wid' => $cyber[$x]))->result_array();		
				$inv[0]['cyberware'][$x] = $cybers[0];
			}
		}
		return $inv;
	}

	function getInternInventory() {
		return $this->db->get_where('inventory', array('cid' => $this->session->userdata('charid')))->result_array();
	}

	function sellItems () {
		$inv = $this->getInternInventory();
		$weapons = $this->input->post('weapon');
		$armor = $this->input->post('armor');
		$money = $this->input->post('total_sell');
		if (empty($weapon) && (empty($armor))) {
			return false;
		}

		if (!empty($weapons)) {
			$old_weapons = explode(';', $inv[0]['wid']);			
			foreach ($weapons as $w) {
				if(($key = array_search($w, $old_weapons)) !== false) {
				    unset($old_weapons[$key]);
				}
			}
			$weapons = implode(';', $old_weapons);
		} else {
			$weapons = $inv[0]['wid'];
		}
		if (!empty($armor)) {
			$old_armor = explode(';', $inv[0]['aid']);			
			foreach ($armor as $w) {
				if(($key = array_search($w, $old_armor)) !== false) {
				    unset($old_armor[$key]);
				}
			}
			$armor = implode(';', $old_armor);
		} else {
			$armor = $inv[0]['aid'];
		}	

		$buy = array(
				'money' => $inv[0]['money']+$this->input->post('total_sell'),
				'wid' => $weapons,
				'aid' => $armor,
			);

		$this->db->where('cid', $inv[0]['cid']);
		return ($this->db->update('inventory', $buy)) ? true : false;
	}

	function buyCyberware() {
		$inv = $this->getInternInventory();
		if ($this->input->post('total_cost') > $inv[0]['money']) {
			return false;
		}
		if ($this->input->post('essenz') <= 0) {
			return false;
		}

		$cyberware = $this->input->post('cyberware');		
		if (!empty($cyberware)) {
			$cyber = implode(';', $cyberware);
			if (empty($inv[0]['cyberid'])) {
				$cyberid = $cyber;
			} else {
				$cyberid =  explode(';', $inv[0]['cyberid']);	
				array_push($cyberid, $cyber);
				$cyberid =  implode(';', $cyberid);	
			}
		} else {
			$cyberid = $inv[0]['cyberid'];			
		}

		$buy = array(
				'money' => $inv[0]['money']-$this->input->post('total_cyber_cost'),
				'cyberid' => $cyberid,
			);
		$char = array(
				'essence' => $this->input->post('essenz'),
			);
		$this->db->where('cid', $inv[0]['cid']);
		if ($this->db->update('chars', $char)) {
			$this->db->where('cid', $inv[0]['cid']);
			return ($this->db->update('inventory', $buy)) ? true : false;		
		} else {
			return false;
		}
	}

	function buyItems() {
		$inv = $this->getInternInventory();
		if ($this->input->post('total_cost') > $inv[0]['money']) {
			return false;
		}

		$newWeapon = $this->input->post('weapon_id');
		$newArmor = $this->input->post('armor_id');
		if (!empty($newWeapon)) {
			if (empty($inv[0]['wid'])) {						
				$weapons = $this->input->post('weapon_id');
			} else {
				$weapons = explode(';', $inv[0]['wid']);	
				array_push($weapons, $this->input->post('weapon_id'));
				$weapons = implode(';', $weapons);
			}	
		} else {
			$weapon = $inv[0]['wid'];
		}

		if (!empty($newArmor)) {
			if (empty($inv[0]['aid'])) {						
				$armor = $this->input->post('armor_id');
			} else {			
				$armor = explode(';', $inv[0]['aid']);			
				array_push($armor, $this->input->post('armor_id'));
				$armor = implode(';', $armor);
			}
		} else {
			$armor = $inv[0]['aid'];
		}

		$ammo = ($this->input->post('ammo')*10);
		$buy = array(
				'money' => $inv[0]['money']-$this->input->post('total_cost'),
				'wid' => $weapons,
				'aid' => $armor,
				'grenades' => $inv[0]['grenades']+$this->input->post('grenades'),
				'medipacks' => $inv[0]['medipacks']+$this->input->post('medipack'),
				'maxammo' => $inv[0]['maxammo']+$ammo,
			);
		$this->db->where('cid', $inv[0]['cid']);
		return ($this->db->update('inventory', $buy)) ? true : false;

	}



	function getWeapons() {
		$this->db->order_by('name', 'ASC');
		return $this->db->get_where('weapons', array('type' => 'weapon'))->result_array();
	}
	function getArmor() {
		$this->db->order_by('name', 'ASC');
		return $this->db->get_where('weapons', array('type' => 'armor'))->result_array();
	}	
	function getCyberware() {
		$this->db->order_by('name', 'ASC');
		return $this->db->get_where('weapons', array('type' => 'cyberware'))->result_array();		
	}
	 
	function getMissionGanger () {		
		$mid = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->session->userdata['missionlevel'];
	
		$this->db->select('gid, level');
		$this->db->from('missions');
		$this->db->where('mid', $mid);
		$query = $this->db->get();
        $gid = $query->result_array();
		
		if (is_array($gid[0])) {
			$this->level = $gid[0]['level'];
			$runner = array();
			$tmp = explode(';', $gid[0]['gid']);
			foreach ($tmp as $t) {
				$query = $this->db->get_where('ganger', array('gid' => $t));
				$data = $query->result_array();				
				array_push($runner, $data);
			}
		}

		return $runner;
	}

	/*

		Wird aufgerufen, sobald eine Mission gestartet wird
	*/
	function calculateFight() {
		$this->deleteCombatlogDB();
		$this->db->delete('infight', array('id' => $this->session->userdata('id')));
		$this->session->set_userdata('missionlevel', $this->uri->segment(3));
		$char = $this->add_functions->getCharacter();
		$ganger = $this->getMissionGanger();
		$inv = $this->getInventory();
		$player_reaction = floor(($char[0]['quickness']+$char[0]['intelligence'])/2)."<br />";
		$player_initiative = (int)($this->combat->_calculateIni('3')+$player_reaction);
		$data = array();

		$c_ini = 0;
		$c_armor = 0;
		$c_reaction = 0;
		$c_mw = 0;

		/* cyberware not implemented */
		if (!empty($inv[0]['cyberware'][0])) {
			foreach ($inv[0]['cyberware'] as $c) {
				$c_ini = ($c['cyberware_ini'] > 0) ? $c_ini+(int)($c['cyberware_ini']) : $c_ini;	
				$c_armor = ($c['cyberware_armor'] > 0) ? $c_armor+(int)($c['cyberware_armor']) : $c_armor;
				$c_reaction = ($c['cyberware_reaction'] > 0) ? $c_reaction+(int)($c['cyberware_reaction']) : $c_reaction;
				$c_mw = ($c['cyberware_mw'] > 0) ? $c_mw+(int)($c['cyberware_mw']) : $c_mw;
			}
		}

#_debugDie($inv);
		/* SC Rüstung wird kalkuliert */
		$aid = $this->input->post('armor');
		if (!empty($aid)) {
			for ($x=0;$x<count($inv[0]['armor']);$x++) {
				if ($aid == $inv[0]['armor'][$x]['wid']) {
					$armor = $inv[0]['armor'][$x];
				}
			}
		} else {
			$armor['armor'] = '0';
		}

		/* SC Waffe */
		$wid = $this->input->post('weapon');
		for ($x=0;$x<count($inv[0]['weapon']);$x++) {
			if ($wid == $inv[0]['weapon'][$x]['wid']) {
				$weapon = $inv[0]['weapon'][$x];
			}
		}		

		/* SC Werte werden ausgelesen und gespeichert */
		$player['body'] = $char[0]['body'];
		$player['cid'] = $char[0]['cid'];
		$player['health'] = '10';
		$player['spirit'] = '10';
		$player['armor'] = $armor['armor']+$c_armor;
		$player['reaction'] = floor(($char[0]['quickness']+$char[0]['intelligence'])/2);
		$player['combat'] = $char[0]['armed_longrange'];
		$player['name'] = $char[0]['charname'];
		$player['status'] = 'alive';
		$player['weapon_name'] = $weapon['name'];
		$player['weapon_soak'] = substr($weapon['damage'], 0,-1);
		$player['weapon_soak_default'] = substr($weapon['damage'], 0,-1);
		$player['weapon_damage'] = substr($weapon['damage'], -1);
		$player['weapon_default'] = substr($weapon['damage'], -1);
		$player['weapon_reduce'] = $weapon['reduce'];
		$player['fire_mode'] = $weapon['mode'];
		$player['ammo'] = $weapon['ammo'];
		$player['ammo_default'] = $weapon['ammo'];
		$player['action'] = '';
		$player['small_medipacks'] = $inv[0]['medipacks'];
		$player['inidice'] = $char[0]['inidice']+$c_ini;
		$player['reaction_mod'] = $char[0]['reaction_mod']+$c_reaction;
		$player['money'] = $inv[0]['money'];
		$player['maxammo'] = $inv[0]['maxammo'];
		$player['avatar'] = $char[0]['avatar'];
		$player['mw'] = $c_mw;

#_debugDie($player);
		$tmp = '"'.$player['name'].'"';
		for($x=0;$x<count($ganger);$x++) {
			$enemy[$x]['body'] = $ganger[$x][0]['body'];
			$enemy[$x]['health'] = '10';			
			$enemy[$x]['spirit'] = '10';
			$enemy[$x]['armor'] = '1';	
			$enemy[$x]['level'] = $ganger[$x][0]['level'];
			$enemy[$x]['reaction'] = $ganger[$x][0]['reaction'];
			$enemy[$x]['combat'] =  $ganger[$x][0]['armed_longrange'];
			$enemy[$x]['name'] =  $ganger[$x][0]['ganger_name'];
			$enemy[$x]['status'] = 'alive';
			$enemy[$x]['inidice'] = $this->combat->_getIniDice($ganger[$x][0]['level']);
			$enemy[$x]['weapon_soak'] = $this->combat->_getNSCWeaponSoak($ganger[$x][0]['level']);
			$enemy[$x]['weapon_soak_default'] = $this->combat->_getNSCWeaponSoak($ganger[$x][0]['level']);
			$enemy[$x]['weapon_damage'] = $this->combat->_getNSCWeaponDamage($ganger[$x][0]['level']);
			$enemy[$x]['weapon_default'] = $this->combat->_getNSCWeaponDamage($ganger[$x][0]['level']);
			array_push($this->fighters, $enemy[$x]["name"]);
		}

		$this->player = $player;
		$this->enemy = $enemy;
		$this->enemies = count($this->enemy);
		
		$this->_writeToSession();
		
		$data['player'] = $player;
		$data['enemy'] = $enemy;
		$data['enemies'] = count($this->enemy);
		
		_debug($this->getInitiative());
		
		#$this->beginnFighting(1);
		#return $data;
	}


	function beginnFighting ($round=1) {
	error_log('in beginnFighting');	
		$this->round = $round;
		array_push($this->combatlog, 'AAA Kampfrunde '.$this->round.' beginnt.<br />');			
		$this->checkResult();
		if($this->status == 'running') {
			$this->ini = '';
			$this->getInitiative();			
			$this->iniphase = max(array_keys($this->ini));
			$this->combatRound();
			$this->checkResult();
		
			if($this->status == 'running') {
				$this->beginnFighting ($round+1);
			}
		} else {			
			$this->finalizeFight();
		}		
	}

	function combatRound() {	
		#die('heer');
		$x = $this->iniphase;
		error_log('in combatRound before itter: '.$this->iniphase.' x ->'.$x);	
		while($x) {
		#for($x=$this->iniphase;$x=0;$x--){
			if ($this->iniphase == 0) {
				$this->round = $this->round+1;
				$this->beginnFighting($this->round);
			}
			$this->checkResult();
			if (in_array($x, array_keys($this->ini))) {
				$this->fighterinround = $this->ini[$x];
				$this->shootOut();
			} else {
				$this->iniphase = $this->iniphase-1;				
			}

			$x = $this->iniphase;			
			error_log('in combatRound after itter : '.$this->iniphase.' -> '.$x);						
		}		
	}


	function shootOut() {	
		if($this->status == 'running') {
			$fighter = explode(';', $this->fighterinround); 
			foreach ($fighter as $f) {		
				if ($f == $this->player['name']) {
					array_push($this->combatlog, '<br />Iniphase '.($this->iniphase).' Spieler <b>'.ucfirst($this->player['name']).'</b> agiert.<br />');	
					$this->playerShooting($this->round);				
				}
				foreach ($this->enemy as $e) {
					if ($f == $e['name']) {
						if ($e['status'] != 'dead') {
							array_push($this->combatlog, '<br />Iniphase '.($this->iniphase).' Ganger <i>'.ucfirst($e['name']).'</i> schiesst.<br />');	
							$this->enemyShooting($e);
						}
					}
				}
			}
		} else {
			$this->finalizeFight();
		}
		$this->iniphase = (int)($this->iniphase-1);
		$this->combatRound();		
	}

	function shootOut2() {
	error_log('in shootOut');
		$this->iniphase = $this->iniphase-1;	
		if ($this->iniphase == 0) {
			$round = ($this->round+1);
			$this->beginnFighting($round);
		}

		$fighter = explode(';', $this->fighterinround); 

	foreach ($fighter as $f) {
			if($this->status == 'running') {
				if (in_array($f, $this->player)) {
					array_push($this->combatlog, '<br />Iniphase '.($this->iniphase+1).' Spieler <b>'.ucfirst($this->player['name']).'</b> agiert.<br />');	
					$this->playerShooting('1');
				}
				foreach ($this->enemy as $e) {
	error_log('in shootOut '.$f);					
					if ($f == $e['name']) {
						if ($e['status'] != 'dead') {
							array_push($this->combatlog, '<br />Iniphase '.($this->iniphase+1).' Ganger <i>'.ucfirst($e['name']).'</i> schiesst.<br />');	
							$this->enemyShooting($e);
						}
					}
				}
			} else {
				$this->finalizeFight();
			}				
		}	
		#$this->iniphase = (int)($this->iniphase-1);
		$this->combatRound();
	}

	function enterCombatRound() {
 		error_log('in enterCombatRound');	
		$this->_writeToSession();
		redirect('/combatzone/combat_round');
	}

	function returnFromCombatRound() {
 		error_log('in returnFromCombatRound');		
		unset($_POST['sendAction']);

		$this->_readFromSession();
		$this->player['action'] = $this->input->post('action');

		if ($this->player['action'] == 'cover') {
			array_push($this->combatlog, 'Iniphase '.($this->iniphase+1).' <b>'.ucfirst($this->player['name']).'</b> geht in Deckung.<br />');	
			$this->combatRound();			
		} else if ($this->player['action'] == 'reload') {
			array_push($this->combatlog, 'Iniphase '.($this->iniphase+1).' <b>'.ucfirst($this->player['name']).'</b> l&auml;dt nach.<br />');	
			if ($this->player['maxammo'] >= $this->player['ammo_default']) {
				$this->player['ammo'] = $this->player['ammo_default'];
				$this->player['maxammo'] = $this->player['maxammo']-$this->player['ammo_default'];
			} else {
				$this->player['ammo'] = $this->player['maxammo'];
				$this->player['maxammo'] = 0;
			}
			$this->combatRound();	
		} else if ($this->player['action'] == 'smallheal') {
			$this->player['health'] = ($this->player['health']+3);
			if($this->player['health'] > 10) {
				$this->player['health'] = '10';
			}
			$this->player['small_medipacks'] = $this->player['small_medipacks']-1;
			$this->combatRound();	
		} else if ($this->player['action'] == 'flee') {
			$this->status = 'flee';
			$this->finalizeFight();
		} else {
			$this->playerShooting($this->round);
			error_log('in returnFromCombatRound after shootout');	
		}
	}
	
	function playerShooting($inround) {
 		error_log('in playerShooting');
		$this->checkResult();
		/*
		if ($inround == 1) {
			if($this->status == 'running') {
				$this->enterCombatRound();
			} else {
				$this->finalizeFight();
			}
		}
*/
			
		for ($i=0;$i<2;$i++) {
			if($this->status == 'running') {		
				if ($this->player['action'] == 'salve') {
					$this->player['ammo'] = (int)($this->player['ammo']-3);
				} else if ($this->player['action'] == 'automatic') {
					$this->player['ammo'] = (int)($this->player['ammo']-6);			
				} else {
					$this->player['ammo'] = (int)($this->player['ammo']-1);
				}					
				$shots = array();		
				$allfired = array();	
				for ($x=0;$x<$this->player['combat'];$x++) {
					$roll = $this->combat->_rollDiceWithRule();
					$mw = $this->combat->_getPitch($this->level)-(int)($this->player['mw']);
					
					if ($this->player['action'] == 'salve') {
						if ($i==0) {
							$mw = $mw+2-$this->player['weapon_reduce'];
						} else {
							$mw = $mw+2-$this->player['weapon_reduce'];
						}
					} else if ($this->player['action'] == 'automatic') {
						$mw = $mw+5-$this->player['weapon_reduce'];	
						$i++;						
					}
					array_push($allfired, $roll);

					if ($roll >= $mw) {
						array_push($shots, $roll);
					}				
				} 	
				$fired = '';
				foreach ($allfired as $s) {
					$fired .= $s.', ';
				}

				array_push($this->combatlog, ucfirst($this->player['name']).' w&uuml;rfelt '.$fired.' gegen den Mindestwurf: '.$mw.'.<br />');										
				if (!empty($shots)){
					array_push($this->combatlog, 'Schuss Nummer '.($i+1).' von <b>'.ucfirst($this->player['name']).'</b> hat mit '.count($shots).' Erfolgen getroffen.<br />');	
					$this->evaluatePlayerDamage($shots);
				} else {	
					array_push($this->combatlog, 'Schuss Nummer '.($i+1).' <b>'.ucfirst($this->player['name']).'</b> hat verfehlt.<br />');		
				}
				error_log('in playerShooting shooting');				
			} else {
				$this->finalizeFight();
			}
		}
		error_log('in playerShooting return');
		#$this->iniphase = (int)($this->iniphase-1);
		$this->shootOut();
	}

	function evaluatePlayerDamage ($shots) {
 		error_log('in evaluatePlayerDamage');			
		if($this->status == 'running') {
			#$this->player['weapon_damage'] = $this->player['weapon_default'];
			$soaking = array();			
			$target = ($this->enemies > 1) ? $this->getTarget() : $target = 0;

			error_log('in evaluatePlayerDamage target: '.$target);	
			
			if ($this->player['action'] == 'salve') {
				$this->player['weapon_soak'] = (int)($this->player['weapon_soak']+3);
				$this->player['weapon_damage'] = $this->combat->_adjustBurstDamage($this->player['weapon_damage']);
			} else if ($this->player['action'] == 'automatic') {
				$this->player['weapon_soak'] = (int)($this->player['weapon_soak']+6);
				$this->player['weapon_damage'] = 'T';
			}
	
			for ($x=0;$x<$this->enemy[$target]['body'];$x++) {
				$roll = $this->combat->_rollDiceWithRule();
				/* Mindestwurf berechnung */
				$min = (($this->player['weapon_soak']-$this->enemy[$target]['armor']) < '2') ? '2' : ($this->player['weapon_soak']-$this->enemy[$target]['armor']);
				if ($roll > $min) {
					array_push($soaking, $roll);
				} 
			}

			if (count($shots) > 2) {
				$this->player['weapon_damage'] = $this->combat->calculateDamageIncrease($shots, $this->player['weapon_damage']);
			}
			if (count($soaking) > 1) {
				$this->player['weapon_damage'] = $this->combat->calculateDamageDecrease($soaking, $this->player['weapon_damage']);
			}					
			$weapondamage  = (int)($this->combat->_getWeaponDamage($this->player['weapon_damage']));
					
			if ($weapondamage < 1)	 {
				array_push($this->combatlog, "<b>".ucfirst($this->player['name']).'</b> schiesst auf <i>'.$this->enemy[$target]['name']."</i> verursacht aber keinen Schaden. <br /><i>".$this->enemy[$target]['name']."</i> Leben bleibt bei ".$this->enemy[$target]['health']."<br />");
			} else {
				$damage = $this->enemy[$target]['health']-$weapondamage;
				$this->enemy[$target]['health_before'] = $this->enemy[$target]['health'];

				$this->enemy[$target]['health'] = $damage;
				array_push($this->combatlog, "<b>".ucfirst($this->player['name']).'</b> schiesst auf <i>'.$this->enemy[$target]['name']."</i> und macht ".$weapondamage." Schaden. <br /><i>".$this->enemy[$target]['name']."</i> Leben sinkt von ".$this->enemy[$target]['health_before']." auf ".$this->enemy[$target]['health']."<br />");
				$this->enemy[$target]['status'] = 'wounded';
				if ($this->enemy[$target]['health'] < 1) {
					$this->enemy[$target]['status'] = 'dead';
					array_push($this->combatlog, "<i>".$this->enemy[$target]['name']."</i> stirbt");
				}				
			}
			$this->player['weapon_soak'] = $this->player['weapon_soak_default'];
			$this->player['weapon_damage'] = $this->player['weapon_default'];
			error_log('in evaluatePlayerDamage finalized: ');				
		} else {
			$this->finalizeFight();
		}
	}	
		
	function enemyShooting($e) {
 		error_log('in enemyShooting');				
		for ($i=0;$i<2;$i++) {
			$shots = array();			
			$allfired = array();
			if($this->status == 'running') {
				for ($x=0;$x<$e['combat'];$x++) {		
					$mod = ($this->player['action'] == 'cover')	? 3 : 0;
					$roll = $this->combat->_rollDiceWithRule();
					$mw =  $this->combat->_getPitch($this->level)+$mod;

					array_push($allfired, $roll);
					if ($roll >= $mw) {
						array_push($shots, $roll);
					}				
				} 	
				$fired = '';
				foreach ($allfired as $s) {
					$fired .= $s.', ';
				}
				array_push($this->combatlog, ucfirst($e['name']).' w&uuml;rfelt '.$fired.' gegen den Mindestwurf: '.$mw.'.<br />');					

				if (!empty($shots)){
					array_push($this->combatlog, 'Schuss Nummer '.($i+1).' von <i>'.ucfirst($e['name']).'</i> hat mit '.count($shots).' Erfolgen getroffen.<br />');	
					$this->evaluateEnemyDamage($shots, $e);
				} else {
					array_push($this->combatlog, 'Schuss Nummer '.($i+1).' von <i>'.ucfirst($e['name']).'</i> hat verfehlt.<br />');		
				}					
			} else {
				$this->finalizeFight();
			}
		}
		error_log('in enemyShooting return');		
		$this->iniphase = (int)($this->iniphase-1);
		$this->combatRound();		
	}	


	function evaluateEnemyDamage ($shots, $enemy) {
 		error_log('in evaluateEnemyDamage');					
		
		#$this->checkResult();
		if($this->status == 'running') {
			#$enemy['weapon_damage'] = $enemy['weapon_default'];
			$soaking = array();	
			$allrolls = array();		
			
			for ($x=0;$x<$this->player['body'];$x++) {
				$roll = $this->combat->_rollDiceWithRule();
				/* Mindestwurf berechnung */
				$min = (($enemy['weapon_soak']-$this->player['armor']) < '2') ? '2' : ($enemy['weapon_soak']-$this->player['armor']);
				
				array_push($allrolls, $roll);
				if ($roll >= $min) {
					array_push($soaking, $roll);
				} 		
			}
			$rolls = '';
			foreach ($allrolls as $s) {
				$rolls .= $s.', ';
			}
			array_push($this->combatlog, $this->player['name'].' versucht dem Schaden zu wiederstehen und w&uuml;rfelte '.$rolls.' das sind '.count($soaking).' Erfolge gegen einen Mindestwurf von '.$min.'<br />');						


			if (count($shots) > 2) {
				$enemy['weapon_damage'] = $this->combat->calculateDamageIncrease($shots, $enemy['weapon_damage']);
			}

			if (count($soaking) > 1) {
				$enemy['weapon_damage'] = $this->combat->calculateDamageDecrease($soaking, $enemy['weapon_damage']);
			}				




			 $weapondamage  = (int)($this->combat->_getWeaponDamage($enemy['weapon_damage']));
			if ($weapondamage < 1)	 {
				array_push($this->combatlog, '<i>'.ucfirst($enemy['name']).'</i> schiesst auf <b>'.$this->player['name']."</b> verursacht aber keinen Schaden. <br />");
			} else {
				$damage = $this->player['health']-$weapondamage;
				$this->player['health_before'] = $this->player['health'];

				$this->player['health'] = $damage;
				array_push($this->combatlog, "<i>".ucfirst($enemy['name']).'</i> schiesst auf <b>'.$this->player['name']."</b> und macht ".$weapondamage." Schaden. <br /><b>".$this->player['name']."</b> Leben sinkt von ".$this->player['health_before']." auf ".$this->player['health']."<br />");
				$this->player['status'] = 'wounded';
				if ($this->player['health'] < 1) {
					$this->player['status'] = 'dead';
					array_push($this->combatlog, "<b>".$this->player['name']."</b> ist schwer verwundet und flüchtet aus dem Kampf.");
				}				
			}
					
				
			#$enemy['weapon_soak'] = $enemy['weapon_soak_default'];
			#$enemy['weapon_damage'] = $enemy['weapon_default'];
			error_log('in evaluateEnemyDamage finalized: ');
		} else {
			$this->finalizeFight();
		}
	}
	
		

	function getTarget() {
 		error_log('in getTarget');
		$dead='0';
		foreach($this->enemy as $e){
			if ($e['status'] == 'dead') {
				$dead++;
			}
		}
		if ($dead == $this->enemies) {
			$this->status = 'success';
			$this->finalizeFight();
		}
			
		$target = (rand(1,$this->enemies)-1);
		return ($this->enemy[$target]['status'] == 'dead') ? $this->getTarget() : $target;			
	}
 
	function getInitiative() {
 		error_log('in getInitiative');	
		$reaction = ($this->player['reaction']+$this->player['reaction_mod']);
		$ini_player = (int)($this->combat->_calculateIni($this->player['inidice'])+$reaction);
		$this->calculateRounds($ini_player, $this->player['name']);		

		for($x=0;$x<count($this->enemy);$x++) {
			$ini_enemy = (int)($this->combat->_calculateIni($this->enemy[$x]['inidice'])+$this->enemy[$x]['reaction']);
			$this->calculateRounds($ini_enemy, $this->enemy[$x]['name']);
		}		

	}

	function calculateRounds($count, $name) {
 		error_log('in calculateRounds');	
		if ($count > 10) {	
			for ($x=$count;$x>0;$x-=10) {
				if (empty($this->ini[$x]))	 {
					$this->ini[$x] = $name;
				} else {
					$this->ini[$x] = $this->ini[$x].";".$name;
				}
			}
		} else {
			if (empty($this->ini[$count]))	 {
				$this->ini[$count] = $name;
			} else {
				$this->ini[$count] = $this->ini[$count].";".$name;
			}
		}
	}	

	
	function finalizeFight() {	
 		error_log('in finalizeFight');		

		$this->writeResults();	
		$this->finalizeCombatlog();	
		$this->writeCombatlogDB();
		redirect('/combatzone/combat_result', 'refresh');
	}

	function finalizeCombatlog () {
 		error_log('in finalizeCombatlog');			
		array_push($this->combatlog, 'Der Kampf wurde nach '.$this->round.' Runden beendet.');
		if ($this->status == 'success') {
			array_push($this->combatlog, 'XXX Du warst erfolgreich.');
		} else if ($this->status == 'flee') {
			array_push($this->combatlog, 'XXX Du bist verletzte aus dem Kampf geflohen.');
		} else {
			array_push($this->combatlog, 'XXX Du wurdest besiegt, und schwer verletzt.');
		}
	}

	function writeCombatlogDB () {
		$sql = sprintf("
			INSERT INTO %s (uid, combatlog, status, cash, lost) VALUES ('%s', '%s', '%s', '%s', '%s')
			ON DUPLICATE KEY UPDATE combatlog='%s', status='%s', cash='%s', lost='%s'
			", 
			'newsnet_combatlog', $this->session->userdata('id'), json_encode($this->combatlog), $this->status, $this->cash, $this->lost,
			json_encode($this->combatlog), $this->status, $this->cash, $this->lost
			);
 		
		return $this->db->query($sql);
	}

	function readCombatlogDB () {
		return $this->db->get_where('combatlog', array('uid' => $this->session->userdata('id')))->result_array();
	}	

	function deleteCombatlogDB () {
		return $this->db->delete('combatlog', array('uid' => $this->session->userdata('id')));
	}	

	function checkResult() {
 		error_log('in checkResult Ini: '.$this->iniphase);			
		if ($this->player['status'] == 'dead') {
			error_log('in checkResult - player dead');	
			$this->status = 'fail';
			$this->finalizeFight();
		}
		$status = '';
		foreach ($this->enemy as $e) {
			if ($e['status'] == 'dead') {
				$status++;
			}
		}		
		if ($status == $this->enemies) {
			error_log('in checkResult - enemy dead');	
			$this->status = 'success';
			$this->finalizeFight();
		} 	
		if ($this->iniphase <= '0') {
			$this->round = $this->round+1;
			$this->beginnFighting($this->round);
		}
		error_log('in checkResult - else');	
	}

	function writeResults() {
 		error_log('in writeResults');	
		$mission = $this->getMissionData();
		$kills = 0;
		foreach ($this->enemy as $e) {
			if ($e['status'] == 'dead') {
				$kills++;
			}
		}
		$tmp = count(explode(';', $mission[0]['gid']));

		if ($this->status == 'success') {
			$result = 'Win';
			$cash = $mission[0]['cash']+$this->combat->_calculateRandomLoot($this->level);
			$loss = '0';	
			$money = $this->player['money']+$cash;
			array_push($this->combatlog, 'Der Run hat dir '.$cash.' &yen; gebracht.<br />');			
		} else if ($this->status == 'flee') {
			$result = 'Fleed';
			$cash = '0';
			$loss = $mission[0]['cash'];
			$money = $this->player['money']+$loss;
			array_push($this->combatlog, 'Der Run hat dich '.$loss.' &yen; gekostet.<br />');						
		} else {
			$result = 'Lost';
			$cash = '0';
			$loss = (($mission[0]['level']*$tmp)*250);
			$money = $this->player['money']+$loss;	
			array_push($this->combatlog, 'Durch deine Verletzungen verlierst du '.$loss.' &yen;.<br />');								
		}
		
		$inv = array(
			'money' => $money,
		);
		$this->cash = $cash;
		$this->lost = $loss;

		$this->db->where('cid', $this->player['cid']);
		$this->db->update('inventory', $inv);
	
		$combat = array(
				'user_id' => $this->session->userdata('id'),
				'runner' => $this->player['name'],
				'result' => $result,
				'level' => $mission[0]['level'],
				'title' => $mission[0]['title'],
				'kills' => $kills,
				'cash' => $cash,
				'loss' => $loss,
				'verlauf' => json_encode($this->combatlog),
			);
		$this->db->insert('combatstats', $combat);
	
	}

	function getStatistics () {
 		error_log('in getStatistics');		
		return $this->db->get_where('combatstats', 'user_id = '.$this->session->userdata('id'))->result_array();
	}

	
	function _writeToSession() {
 		error_log('in _writeToSession');	

 		$data = array(
				'player' => $this->player, 
				'enemy' => $this->enemy, 
				'enemies' => $this->enemies,
				'status' => $this->status,
				'combatlog' => $this->combatlog,
				'fighters' => $this->fighters,
				'combatlog' => $this->combatlog,
				'level' => $this->level,
				'round' => $this->round,
				'ini' => $this->ini,
				'iniphase' => $this->iniphase,
				'inicounter' => $this->inicounter,
				);
 		$json = json_encode($data);
		$sql = sprintf("
					INSERT INTO %s (userid, json) VALUES ('%s','%s')
					ON DUPLICATE KEY UPDATE json='%s'
					", 				
						'newsnet_infight', $this->session->userdata('id'), $json,
						$json
					);
			$this->db->query($sql); 		
	}

	function _readFromSession() {
		error_log('in _readFromSession ');
		$data = $this->getInfightData();
	
		$this->player = $data['player'];
		$this->enemy = $data['enemy'];
		$this->enemies = $data['enemies'];
		$this->status = $data['status'];
		$this->combatlog = $data['combatlog'];
		$this->fighters = $data['fighters'];
		$this->combatlog = $data['combatlog'];
		$this->level = $data['level'];
		$this->round = $data['round'];
		$this->ini = $data['ini'];
		$this->iniphase = $data['iniphase'];
		$this->inicounter = $data['inicounter'];	
	}	

	function getInfightData () {
		$query = $this->db->get_where('infight', "userid = '".$this->session->userdata('id')."'")->result_array();
		return json_decode($query[0]['json'], true);
	}
	
}   