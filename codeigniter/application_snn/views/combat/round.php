<?php
	#rsort($combat['ini']);
	#_debugDie($combat);
	#$combat['player']['maxammo'] = 3;
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');
	#echo count($combat['ini']);
	#_debugDie($combat['ini']);

?>

<style>
	td {
		border: 1px solid #BEC6C8;
		padding: 3px 7px;
	}
	select {
		color: black;
	}
	.combatlog {
		color: #000000;
		padding: 0px;
		background-image: url(/secure/snn/assets/img/layout/trans4.png);
		font-weight: bold;
		width: 100%;
		display: block;
		margin-bottom: -5px;
		border-radius: 3px;
	}
</style>

<script>
// window.addEventListener("beforeunload", function (e) {
// 	  var confirmationMessage = "Reload ist nicht gestattet.";

// 	  e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
// 	  return confirmationMessage;              // Gecko, WebKit, Chrome <34
// 	});
	
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	$('input').click(function(e){
        	var action = $(this).val();
        	if(action == 'magic') {
				$('#spellbox').show();
        	} else {
        		$('#spellbox').hide();
        	}
    	});
	});

	function toggleCombatRound (id) {
		$('#'+id).toggle();
	}

	function checkCombatSpell() {
		var type = $('select#spell :selected').attr('data-typ');
		if (type == 'kampf') {
			$('#spelllevel').show();
			$('#spelldamage').show();
		} else {
			$('#spelllevel').hide();
			$('#spelldamage').hide();
		}
	}

</script>
	<div class="col-md-12" style="color: white">
	<fieldset class="newselement">
		<legend class="newstitle">Kampfrunde</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left: 25%;" id="error">
					<b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b>
				</div>
			<?php endif; ?>		
					
		<div class="col-md-7" style="border: 1px solid white; padding: 10px;">
			<b>Initiativeverlauf in Kampfrunde <?=$combat['round']?>:</b> <br />
				<?php
					if (count($combat['ini']) > 1) {
						for($x=0;$x<count($combat['ini']);$x++) {
							foreach($combat['ini'][$x] as $key => $value){
								echo "Phase ".($x+1).".".$key." : ". $value."<br />";
							}
							echo "<br />";
						}
					} else {
						foreach($combat['ini'][0] as $key => $value){
							echo "Phase 1.".$key." : ". $value."<br />";
						}
					}

				?>
				<br />
			<b>Gegner</b><br />		
			<?php foreach($combat['enemy'] as $e):?>
			
			<div style="border:1px solid #BEC6C8; padding: 3px;margin-bottom:3px;background-color: #2E323B">
					<?php $hclass = ($e['status'] == 'alive') ? 'style="color:green"': 'style="color:red"'; ?>
					<b><?=$e['name']?></b>
					<i class="fa fa-caret-right" aria-hidden="true"></i>
					&nbsp; 
					<i class="fa fa-heart" aria-hidden="true" <?=$hclass?>></i> <?=$e['health']?>/ 10 HP<br />				
			</div>
			
			<?php endforeach; ?>
			</div>
		<div class="col-md-5">
			<?php 
				$mclass = ($combat['player']['ammo'] < 5) ? 'style="color:red"' : ''; 
				$mpclass = ($combat['player']['small_medipacks'] < 1) ? 'style="color:red"' : ''; 
				$hclass = ($combat['player']['health'] == 10) ? 'style="color:green"' : 'style="color:red"';
				$sclass = ($combat['player']['spirit'] == 10) ? 'style="color:green"' : 'style="color:red"';
				$mode = explode(';', $combat['player']['fire_mode']);
				#_debugDie($combat['player']);
			?>	
			<div>
			<table>
				<tbody>
				<tr style="background-color: #2E323B">
					<td colspan="4"><b><?=$combat['player']['name']?></b></td>
				</tr>
				<tr>
					<td colspan="1"><b>Physisch: </b></td>
					<td colspan="3"><i class="fa fa-heart" aria-hidden="true" <?=$hclass?>></i> <?=$combat['player']['health']?>/ 10 HP</td>
				</tr>
				<tr>
					<td colspan="1"><b>Geistig: </b></td>
					<td colspan="3"><i class="fa fa-heart" aria-hidden="true" <?=$sclass?>></i> <?=$combat['player']['spirit']?>/ 10 HP</td>
				</tr>
				<tr>
					<td><b>Initiative:</b></td>
					<td colspan="3">
						<?=$combat['player']['inidice']?>D +
						<?=($combat['player']['reaction']+$combat['player']['reaction_mod'])?>						
					</td>		
				</tr>			
				<tr>
					<td><b>Medipacks:</b></td><td <?=$mpclass;?>><?=$combat['player']['small_medipacks']?></td>
					<td><b>Granaten:</b></td><td><?=$combat['player']['grenades']?></td>
				</tr>	
				<tr>
					<td><b>R&uuml;stung:</b></td><td><?=$combat['player']['armor']?></td>									
				</tr>						

				<?php if($combat['player']['weapon_name']): ?>
					<tr><td colspan="4" style="padding:4px;border:none"></td></tr>
					<tr style="background-color: #2E323B">				
						<td><b>Fernkampf:</b></td>
						<td colspan="3"><?=$combat['player']['weapon_name']?></td>
					</tr>
					<tr>
						<td><b>Schaden:</b></td><td><?=$combat['player']['weapon_soak'].$combat['player']['weapon_default']?></td>
						<td><b>Modus:</b></td><td><?=$combat['player']['fire_mode']?></td>
					</tr>
					<tr>
						<td><b>Munition:</b></td><td <?=$mclass;?>><?=$combat['player']['ammo']?></td>
						<td><b>ErsatzMunition:</b></td><td><?=$combat['player']['maxammo']?></td>
					</tr>
				<?php endif; ?>
				<?php if($combat['player']['melee_name']): ?>
					<tr><td colspan="4" style="padding: 4px;border:none;"></td></tr>
					<tr style="background-color: #2E323B">				
						<td><b>Nahkampfwaffe:</b></td>
						<td colspan="3"><?=$combat['player']['melee_name']?></td>
					</tr>
					<tr>
						<td><b>Schaden</b></td><td><?=($combat['player']['strength']+$combat['player']['melee_add_damage']).$combat['player']['melee_default']?></td>
						<td><b>Reichweite:</b></td><td>+<?=$combat['player']['melee_reach']?></td>
					</tr>
				<?php endif; ?>
				<?php if($combat['player']['magic'] > 0 && !empty($combat['player']['spells'])): ?>
					<tr><td colspan="4" style="padding: 4px;border:none;"></td></tr>
					<tr style="background-color: #2E323B">				
						<td colspan="4"><b>Zauber:</b></td>
					</tr>
					<?php foreach($combat['player']['spells'] as $s): ?>
						<tr>
							<td colspan="4"><?=$s['name']; ?></td>
						</tr>
					<?php endforeach;?>
				<?php endif; ?>
				</tbody>
			</table>
			</div>
			<br />			
		</div>
		<div style="clear:both"></div>
		<br />
		<div class="col-md-12" style="float:left;">
			<span class="newstitle">Bisheriger Kampfverlauf:</span>
			<br />
			<ul style="list-style-type: none;">
			<?php foreach($combat['combatlog'] as $c):?>
			<?php 
				if (preg_match('/systemcreateheader/i', $c)) {
					$tmp = explode(';', $c);
					echo "<div class='combatlog' style='cursor: pointer' title='click to open' onclick=\"toggleCombatRound('".$tmp[1]."')\">";
				} else if (preg_match('/systemcloseheader/i', $c)) {
					echo "</div>";
				} else if (preg_match('/systemcreatediv/i', $c)) {
					echo "<div id='".$tmp[1]."' style='display: none'>";
				} else if (preg_match('/systemclosediv/i', $c)) {
					echo "</div>";
				} else {				
					echo "<li>".$c."</li>";
				}
			?>
				
			<?php endforeach; ?>
			
			</ul>
		</div>
		
		<div style="clear:both"></div>
		<br />
		<div class="col-md-12" style="color: white">
			<fieldset class="newselement">
			<div><b>Welche Aktion möchtest du ausführen (zählt für beide Ini-Aktionen)?</b></div>
			<br />

			<form action="/secure/snn/combatzone/nextRound" method="post" enctype="text/html" id="shootout-form" />
			<input type="hidden" name="round" id="round" value="<?=$combat['round'];?>" />
			<input type="hidden" name="weapon" id="weapon" value="<?=$_POST['weapon'];?>" />
			<input type="hidden" name="weapon" id="melee" value="<?=$_POST['melee'];?>" />
			<input type="hidden" name="armor" id="armor" value="<?=$_POST['armor'];?>" />
			<?=form_hidden('sendAction', true);?>
			<div class="col-md-6" style="color: white">
			<?php if($combat['player']['weapon_name']): ?>
				<?php if($combat['player']['ammo'] > 1): ?>
				<b>Fernkampf</b><br />
					<?php if(in_array('HM', $mode)): ?>
						<input type="radio" name="action" id="action" value="singleshot" />	- einzelner Schuß (-2 Munition)<br />
					<?php endif; ?>
					<?php if($combat['player']['ammo'] > 5): ?>
						<?php if(in_array('SM', $mode)): ?>
							<input type="radio" name="action" id="action" value="salve" /> - Salve (-6 Munition)<br />
						<?php endif; ?>
					<?php endif; ?>
					<?php if($combat['player']['ammo'] > 12): ?>		
						<?php if(in_array('AM', $mode)): ?>
							<input type="radio" name="action" id="action" value="automatic" />	- Automatik (-12 Munition)<br />
						<?php endif; ?>
					<?php endif; ?>					
				<?php endif; ?>
			<?php endif; ?>
			<?php if($combat['player']['grenades'] > 0):?>
			<br />
				<b>Granaten</b><br />
				<input type="radio" name="action" id="action" value="grenade" /> - Granate werfen<br />
			<?php endif; ?>
			<?php if($combat['player']['melee_name']): ?>
			<br />
				<b>Nahkampf</b><br />
				<input type="radio" name="action" id="action" value="melee" />	- Nahkampf<br />
			<?php endif; ?>
			<?php if($combat['player']['magic'] > 0 && !empty($combat['player']['spells'])): ?>
			<br />
				<b>Zauber:</b><br />
				<input type="radio" name="action" id="action" value="magic" />	- Zaubern<br />
				<div id="spellbox" style="display:none">
					<select name="spell" id="spell" onchange="checkCombatSpell()">
						<option value="">Zauber ausw&auml;hlen</option>
						<option value=""></option>
					<?php foreach($combat['player']['spells'] as $s): ?>�
						<option value="<?=$s['zid']?>" data-typ="<?=$s['typ']?>"><?=$s['name']?></option>
					<?php endforeach; ?>
					</select>
					<br />
					<select name="spelllevel" id="spelllevel" style="display:none">
						<option value="">Stufe ausw&auml;hlen</option>
						<option value=""></option>
						<?php for($x=1; $x<($combat['player']['magic']+1);$x++) : ?>
							<option value="<?=$x?>"><?=$x?></option>
						<?php endfor; ?>
					</select>
					<select name="spelldamage" id="spelldamage" style="display:none">
						<option value="">Schaden ausw&auml;hlen</option>
						<option value=""></option>
						<option value="L">L</option>
						<option value="M">M</option>
						<option value="S">S</option>
						<option value="T">T</option>
					</select>
				</div>
			<?php endif; ?>
			<br /><br />
			<?php if($combat['player']['health'] < 10 && $combat['player']['small_medipacks'] > 0): ?>		
				<input type="radio" name="action" id="action" value="smallheal" /> - Medipack einwerfen (+3 Leben)<br />
			<?php endif; ?>
				<input type="radio" name="action" id="action" value="cover" /> - Deckung (MW für Gegner erhöht)<br />
			<?php if(($combat['player']['maxammo'] > 0) && ($combat['player']['ammo'] < $combat['player']['ammo_default'])) : ?>
				<input type="radio" name="action" id="action" value="reload" /> - Nachladen<br />
			<?php endif; ?>
				<input type="radio" name="action" id="action" value="flee" /> - Aus dem Kampf fliehen!<br />
			</div>
			<div class="col-md-6" style="color: white">
			<?php if($combat['player']['ammo'] > 1 || $combat['player']['melee_name']): ?>
				Auf Gegner zielen: 
				<select name="target">
					<option value="">kein spezielles Ziel</option>
					<?php foreach($combat['enemy'] as $e): ?>
		
						<?php if($e['status'] != 'dead'): ?>
							<option value="<?=$e['name']?>"><?=$e['name']?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			<?php endif; ?>
			</div>
			<br />
			<div class="col-md-12" style="color: white">
				<br />
				<?=form_submit(array('id'=>'submit', 'value' => 'Aktion ausführen!', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			</form>	
			</fieldset>
		</div>
	</fieldset>
	</div>