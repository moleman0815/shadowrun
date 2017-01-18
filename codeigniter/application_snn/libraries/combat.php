<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Combat {

	function _calculateIni($ammount) {
		$ini = 0;
		for ($x=0; $x<$ammount;$x++) {
			$roll = $this->_rollDice();
			$ini = $ini + $roll;
		}		
		return $ini;
	}
	
	function _rollDice() {
		return (int) rand(1,6);
	}
	
	function _rollDiceWithRule($roll = '') {
		$dice = (int) rand(1,6);		
		$roll = (int)($roll+$dice);
		return ($dice == 6) ? $this->_rollDiceWithRule($roll) : $roll;
	}

	function _calculateRandomLoot ($level) {
		$bonus = array(
				'1' => rand(0,4)*10,
				'2' => rand(1,5)*10,
				'3' => rand(2,7)*10,
				'3' => rand(3,9)*10,
				'4' => rand(5,12)*10,
				'5' => rand(8,15)*20,
				'6' => rand(10,15)*30,
				'7' => '7',
				'8' => '8',
				'9' => '8',
				'10' => '9',
			);
		return $bonus[$level];
	}

	function _getNSCWeaponSoak($level) {			
		$pitch = array(
				'1' => '4',
				'2' => '6',
				'3' => '6',
				'4' => '7',
				'5' => '8',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '8',
				'10' => '9',
				);
		return $pitch[$level];
	}

	function _getNSCWeaponDamage($level) {			
		$pitch = array(
				'1' => 'L',
				'2' => 'L',
				'3' => 'M',
				'4' => 'M',
				'5' => 'M',
				'6' => 'S',
				'7' => 'S',
				'8' => 'S',
				'9' => 'T',
				'10' => 'T',
				);
		return $pitch[$level];
	}	

	function _getPitch($level) {			
		$pitch = array(
				'1' => '4',
				'2' => '4',
				'3' => '5',
				'4' => '6',
				'5' => '6',
				'6' => '7',
				'7' => '7',
				'8' => '8',
				'9' => '8',
				'10' => '9',
				);
		return $pitch[$level];
	}

	function _getDamageReduction($level) {
		$pitch = array(
				'2' => '-3',
				'3' => '-3',
				'4' => '-6',
				'5' => '-6',
				'6' => '-9',
				'7' => '-9',

				);
		return $pitch[$level];
	}

	function _getIniDice($level) {
		$dice = array(
				'1' => '1',
				'2' => '1',
				'3' => '1',
				'4' => '2',
				'5' => '2',
				'6' => '2',
				'7' => '3',
				'8' => '3',
				'9' => '4',
				'10' => '4',
			);
		return $dice[$level];
	}
	
	function _getWeaponDamage($type) {
		if ($type == "") {
			return 0;
		}
		$damage = array(
			'0' => "0",
			'L' => "1",
			'M' => "3",
			'S' => "6",
			'T' => "10",
		);

		return $damage[$type];
	}

	function _adjustBurstDamage ($type) {
		$damage = array(
			'L' => "M",
			'M' => "S",
			'S' => "T",
			'T' => "T",
		);
		return $damage[$type];
	}

	
	function calculateDamageIncrease($shots, $damage) {
		if (count($shots) == '1' || count($shots) == '2') {
			return $damage;
		} else if (count($shots) == '3' || count($shots) == '4') {
			if ($damage == 'L') {
				return 'M';
			} else if ($damage == 'M') {
				return 'S';
			} else {
				return 'T';
			}
		} else if (count($shots) == '5' || count($shots) == '6') {
			if ($damage == 'L') {
				return 'S';
			} else if ($damage == 'M') {
				return 'T';
			} else {
				return 'T';
			}		
		} else {
			return 'T';
		}
	}
	
	function calculateMeleeDamageIncrease($shots, $damage) {
		if (count($shots) == '1'){
			return $damage;
		} else if (count($shots) == '2' || count($shots) == '3') {
			if ($damage == 'L') {
				return 'M';
			} else if ($damage == 'M') {
				return 'S';
			} else {
				return 'T';
			}
		} else if (count($shots) == '4' || count($shots) == '5') {
			if ($damage == 'L') {
				return 'S';
			} else if ($damage == 'M') {
				return 'T';
			} else {
				return 'T';
			}
		} else {
			return 'T';
		}
	}
	
	function calculateDamageDecrease($shots, $damage) {
		if (count($shots) == 0 || count($shots) == 1) {
			return $damage;
		} else if (count($shots) == '2' || count($shots) == '3') {
			if ($damage == 'L') {
				return '0';
			} else if ($damage == 'M') {
				return 'L';
			} else if ($damage == 'S') {
				return 'M';
			} else {
				return 'S';
			}	
		} else if (count($shots) == '4' || count($shots) == '5') {
			if ($damage == 'L' || $damage == 'M') {
				return '0';
			} else if ($damage == 'S') {
				return 'L';
			} else {
				return 'M';
			}
		} else if (count($shots) == '6' || count($shots) == '7') {
			if ($damage == 'L' || $damage == 'M' || $damage == 'S') {
				return '0';			
			} else {
				return 'L';
			}		
		} else {
			return '0';
		}
	}
	
	function mwModByDamage($health) {
		$damage = (10-$health);
		
		if ($damage == 0) {
			return 0;
		} else if ($damage == 1 || $damage == 2) {
			return 1;
		} else  if ($damage > 2 && $damage < 6) {
			return 2;
		}  else  if ($damage >= 6) {
			return 3;
		} 
	}
}