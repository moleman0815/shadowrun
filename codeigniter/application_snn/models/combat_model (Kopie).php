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
	var $round;


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
		$query = $this->db->get_where('missions', array('mid' => $this->uri->segment(3)));
		return $query->result_array();
	}
	 
	function getMissionGanger () {			
		$this->db->select('gid, level');
		$this->db->from('missions');
		$this->db->where('mid', $this->uri->segment(3));
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

	function calculateFight() {
		$char = $this->add_functions->getCharacter();
		$ganger = $this->getMissionGanger();
		$player_reaction = floor(($char[0]['quickness']+$char[0]['intelligence'])/2)."<br />";
		$player_initiative = (int)($this->combat->_calculateIni('3')+$player_reaction);
		
		$player['body'] = $char[0]['body'];
		$player['health'] = '10';
		$player['armor'] = '5';
		$player['reaction'] = floor(($char[0]['quickness']+$char[0]['intelligence'])/2);
		$player['combat'] = $char[0]['armed_longrange'];
		$player['name'] = $char[0]['charname'];
		$player['status'] = 'alive';
		$player['weapon_soak'] = '5';
		$player['weapon_damage'] = 'M';
		$player['weapon_default'] = 'M';
		$player['ammo'] = '30';
		$player['inidice'] = $char[0]['inidice'];
		$player['reaction_mod'] = $char[0]['reaction_mod'];


		$tmp = '"'.$player['name'].'"';
		for($x=0;$x<count($ganger);$x++) {
			$enemy[$x]['body'] = $ganger[$x][0]['body'];
			$enemy[$x]['health'] = '10';			
			$enemy[$x]['armor'] = '1';	
			$enemy[$x]['level'] = $ganger[$x][0]['level'];
			$enemy[$x]['reaction'] = $ganger[$x][0]['reaction'];
			$enemy[$x]['combat'] =  $ganger[$x][0]['armed_longrange'];
			$enemy[$x]['name'] =  $ganger[$x][0]['ganger_name'];
			$enemy[$x]['status'] = 'alive';
			$enemy[$x]['inidice'] = $this->combat->_getIniDice($ganger[$x][0]['level']);
			$enemy[$x]['weapon_soak'] = '4';
			$enemy[$x]['weapon_damage'] = 'M';
			$enemy[$x]['weapon_default'] = 'M';
			array_push($this->fighters, $enemy[$x]["name"]);
		}

		$this->player = $player;
		$this->enemy = $enemy;
		$this->enemies = count($this->enemy);
		$this->beginnFighting();
		$this->finalizeCombatlog();			
		
		#die();
		#$this->util->_debug($this->combatlog);
		$this->writeResults();
		return $this->combatlog;
	}
	
	function beginnFighting ($round = 1) {
		$this->round = $round;
		array_push($this->combatlog, 'AAA Kampfrunde '.$this->round.' beginnt.<br />');			
		$this->checkResult();
		if($this->status == 'running') {
			$this->combatRound();
			$this->checkResult();
			if($this->status == 'running') {
				$this->beginnFighting ($round+1);
			}
		} else {

			return true;
		}		
	}

	function combatRound() {	
		$this->ini = '';
		$this->getInitiative();
#$this->util->_debug($this->ini); die();
		$x=max(array_keys($this->ini));
		while($x) {
			if (in_array($x, array_keys($this->ini))) {
				$this->iniphase = $x;
				$this->shootOut($this->ini[$x], $x);
			}
			$x--;
		}
		return true;	
	}


	function shootOut($fighter, $ini) {
		$fighter = explode(';', $fighter);
		foreach ($fighter as $f) {
			$this->checkResult();
			if($this->status == 'running') {
				if (in_array($f, $this->player)) {
					$this->selectAction();
					array_push($this->combatlog, '<br />Iniphase '.$this->iniphase.' Spieler <b>'.ucfirst($this->player['name']).'</b> schiesst.');	
					$this->playerShooting();
				}
				foreach ($this->enemy as $e) {
					if ($f == $e['name']) {
						if ($e['status'] == 'alive') {
							array_push($this->combatlog, '<br />Iniphase '.$this->iniphase.' Ganger <i>'.ucfirst($e['name']).'</i> schiesst.');	
							$this->enemyShooting($e);
						}
					}
				}
			} else {
				return true;
			}
					
		}		
		return true;
	}

	function selectAction () {
		echo "Select Action: ";
		$this->load->view('header');
		$this->load->view('menu_header');		
		die();
	}

	function playerShooting() {
		for ($i=0;$i<2;$i++) {
			if($this->status == 'running') {
			$shots = array();			
				for ($x=0;$x<$this->player['combat'];$x++) {
					$roll = $this->combat->_rollDiceWithRule();
					if ($roll >= $this->combat->_getPitch($this->level)) {
						array_push($shots, $roll);
					}				
				} 	
				if (!empty($shots)){
					array_push($this->combatlog, 'Iniphase '.$this->iniphase.' Schuss Nummer '.($i+1).' von <b>'.ucfirst($this->player['name']).'</b> hat getroffen.');	
					$this->evaluatePlayerDamage($shots);
				} else {
					array_push($this->combatlog, 'Iniphase '.$this->iniphase.' Schuss Nummer '.($i+1).' von <b>'.ucfirst($this->player['name']).'</b> hat verfehlt.');		
				}					
			} else {
				return true;
			}
		}		
	}

	function enemyShooting($e) {
		for ($i=0;$i<2;$i++) {
			$shots = array();			
			if($this->status == 'running') {
				for ($x=0;$x<$e['combat'];$x++) {				
					$roll = $this->combat->_rollDiceWithRule();
					if ($roll >= $this->combat->_getPitch($this->level)) {
						array_push($shots, $roll);
					}				
				} 	

				if (!empty($shots)){
					array_push($this->combatlog, 'Iniphase '.$this->iniphase.' Schuss Nummer '.($i+1).' von <i>'.ucfirst($e['name']).'</i> hat getroffen.');	
					$this->evaluateEnemyDamage($shots, $e);
				} else {
					array_push($this->combatlog, 'Iniphase '.$this->iniphase.' Schuss Nummer '.($i+1).' von <i>'.ucfirst($e['name']).'</i> hat verfehlt.');		
				}					
			} else {
				return true;
			}
		}			
	}	

	function evaluateEnemyDamage ($shots, $enemy) {
		$this->checkResult();
		if($this->status == 'running') {
			$enemy['weapon_damage'] = $enemy['weapon_default'];
			$soaking = array();			
			
			for ($x=0;$x<$this->player['body'];$x++) {
				$roll = $this->combat->_rollDiceWithRule();
				/* Mindestwurf berechnung */
				$min = (($enemy['weapon_soak']-$this->player['armor']) < '2') ? '2' : ($enemy['weapon_soak']-$this->player['armor']);
				if ($roll > $min) {
					array_push($soaking, $roll);
				} 
			}
			if (count($shots) > 2) {
				$enemy['weapon_damage'] = $this->combat->calculateDamageIncrease($shots, $this->player['weapon_damage']);
			}
			if (count($soaking) > 1) {
				$enemy['weapon_damage'] = $this->combat->calculateDamageDecrease($soaking, $this->player['weapon_damage']);
			}				
			
			$weapondamage  = (int)($this->combat->_getWeaponDamage($this->player['weapon_damage']));
			if ($weapondamage < 1)	 {
				array_push($this->combatlog, '<i>'.ucfirst($enemy['name']).'</i> schiesst auf <b>'.$this->player['name']."</b> verursacht aber keinen Schaden. <br /><b>".$this->player['name']."</b> Leben bleibt unverletzt.");
			} else {
				$damage = $this->player['health']-$weapondamage;
				$this->player['health_before'] = $this->player['health'];

				$this->player['health'] = $damage;
				array_push($this->combatlog, "<i>".ucfirst($enemy['name']).'</i> schiesst auf <b>'.$this->player['name']."</b> und macht ".$weapondamage." Schaden. <br /><b>".$this->player['name']."</b> Leben sinkt von ".$this->player['health_before']." auf ".$this->player['health']);
				if ($this->player['health'] < 1) {
					$this->player['status'] = 'dead';
					array_push($this->combatlog, "<b>".$this->player['name']."</b> ist schwer verwundet und flüchtet aus dem Kampf.");
				}				
			}
				
			$this->checkResult();
		} else {
			return false;
		}
	}
	
		
	function evaluatePlayerDamage ($shots) {
		$this->checkResult();
		if($this->status == 'running') {
			$this->player['weapon_damage'] = $this->player['weapon_default'];
			$soaking = array();			
			$target = ($this->enemies > 1) ? $this->getTarget() : $target = 0;

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
				array_push($this->combatlog, "<b>".ucfirst($this->player['name']).'</b> schiesst auf <i>'.$this->enemy[$target]['name']."</i> verursacht aber keinen Schaden. <br /><i>".$this->enemy[$target]['name']."</i> Leben bleibt bei ".$this->enemy[$target]['health']);
			} else {
				$damage = $this->enemy[$target]['health']-$weapondamage;
				$this->enemy[$target]['health_before'] = $this->enemy[$target]['health'];

				$this->enemy[$target]['health'] = $damage;
				array_push($this->combatlog, "<b>".ucfirst($this->player['name']).'</b> schiesst auf <i>'.$this->enemy[$target]['name']."</i> und macht ".$weapondamage." Schaden. <br /><i>".$this->enemy[$target]['name']."</i> Leben sinkt von ".$this->enemy[$target]['health_before']." auf ".$this->enemy[$target]['health']);
				if ($this->enemy[$target]['health'] < 1) {
					$this->enemy[$target]['status'] = 'dead';
					array_push($this->combatlog, "<i>".$this->enemy[$target]['name']."</i> stirbt");
				}				
			}
				
			$this->checkResult();
		} else {
			return false;
		}
	}

	function getTarget() {
		$target = (rand(1,$this->enemies)-1);
		return ($this->enemy[$target]['status'] == 'dead') ? $this->getTarget() : $target;			
	}

	function getInitiative() {
		$reaction = ($this->player['reaction']+$this->player['reaction_mod']);
		$ini_player = (int)($this->combat->_calculateIni($this->player['inidice'])+$reaction);
		$this->calculateRounds($ini_player, $this->player['name']);		

		for($x=0;$x<count($this->enemy);$x++) {
			$ini_enemy = (int)($this->combat->_calculateIni($this->enemy[$x]['inidice'])+$this->enemy[$x]['reaction']);
			$this->calculateRounds($ini_enemy, $this->enemy[$x]['name']);
		}		

	}

	function calculateRounds($count, $name) {
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


	function finalizeCombatlog () {
		array_push($this->combatlog, 'Der Kampf wurde nach '.$this->round.' Runden beendet.<br />');
		if ($this->status == 'success') {
			array_push($this->combatlog, 'XXX Du warst erfolgreich.<br />');
		} else {
			array_push($this->combatlog, 'XXX Du wurdest besiegt, und schwer verletzt.<br />');
		}
	}

	function checkResult() {
		if ($this->player['status'] == 'dead') {
			$this->status = 'fail';
			return true;
		}
		$status = '';
		foreach ($this->enemy as $e) {
			if ($e['status'] == 'dead') {
				$status++;
			}
		}		
		if ($status == $this->enemies) {
			$this->status = 'success';
			return true;
		} 
		return true;
	}

	function writeResults() {
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
			$cash = $mission[0]['cash'];
			$loss = '0';			
		} else {
			$result = 'Lost';
			$cash = '0';
			$loss = (($mission[0]['level']*$tmp)*250);
		}
		
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
		return true;
	}

	function getStatistics () {
		return $this->db->get_where('combatstats', 'user_id = '.$this->session->userdata('id'))->result_array();
	}
	
}   