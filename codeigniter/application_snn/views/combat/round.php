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
		border: 1px solid white;
		padding: 7px;
	}
	select {
		color: black;
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
	});

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

			<b>Aktuelle Kampfrunde: </b><?=$combat['round']?><br />
			<b>Aktuelle Phase: </b> <?= (isset($combat['iniphase'])) ? $combat['iniphase']+1 : max(array_keys($combat['ini'])); ?><br />
			<br />
			<b>Gegner</b><br />		
			<?php foreach($combat['enemy'] as $e):?>
			
			<div style="border:1px solid white; padding: 3px;margin-bottom:3px">
					<?=$e['name']?><br />
					HP (p): 10/ <?=$e['health']?> || HP (m): 10/ <?=$e['spirit']?><br />
					<?php $hclass = ($e['status'] == 'alive') ? 'style="color:green"' : 'style="color:red"'; ?>
					Zustand: <span <?=$hclass?>><?=$e['status']?></span><br />
			</div>
			
			<?php endforeach; ?>
			</div>
		<div class="col-md-5">
			<?php 
				$mclass = ($combat['player']['ammo'] < 5) ? 'style="color:red"' : ''; 
				$mpclass = ($combat['player']['small_medipacks'] < 1) ? 'style="color:red"' : ''; 
				$hclass = ($combat['player']['status'] == 'alive') ? 'style="color:green"' : 'style="color:red"'; 
				$mode = explode(';', $combat['player']['fire_mode']);
			?>	
			<div>
			<table >
				<tbody>
				<tr>
					<td><b>Name:</b></td>
					<td><?=$combat['player']['name']?></td>
					<td colspan="2"><?=$combat['player']['weapon_name']?></td>
				</tr>
				<tr>
					<td><b>HP (p):</b></td><td>10/ <?=$combat['player']['health']?></td>
					<td><b>Schaden:</b></td><td><?=$combat['player']['weapon_soak'].$combat['player']['weapon_default']?></td>
				</tr>
				<tr>
					<td><b>HP (m):</b></td><td>10/ <?=$combat['player']['spirit']?></td>

					<td><b>Munition:</b></td><td <?=$mclass;?>><?=$combat['player']['ammo']?></td>
				</tr>	
				<tr>					
					<td><b>Zustand:</b></td><td <?=$hclass;?>><?=$combat['player']['status']?></td>
					
					<td><b>Modus:</b></td><td><?=$combat['player']['fire_mode']?></td>
				</tr>					
				<tr>
					<td><b>R&uuml;stung:</b></td><td><?=$combat['player']['armor']?></td>					
					<td><b>Medipacks:</b></td><td <?=$mpclass;?>><?=$combat['player']['small_medipacks']?></td>
				</tr>	
				<tr>
					<td><b>IniW&uuml;rfel:</b></td><td <?=$mpclass;?>><?=$combat['player']['inidice']?></td>
					<td><b>ErsatzMunition:</b></td><td <?=$mpclass;?>><?=$combat['player']['maxammo']?></td>
				</tr>
				<tr>
					<td><b>Reaktion:</b></td><td <?=$mpclass;?>><?=$combat['player']['reaction_mod']?></td>
				</tr>										
				</tbody>
			</table>
			</div>
			<br />
			
			<br />
		</div>
				<div style="clear:both"></div>		
		<br />		
		<div class="col-md-12" style="float:left;">
			<span class="newstitle">Bisheriger Kampfverlauf:</span>
			<br />
			<ul style="list-style-type: none;">
			<?php foreach($combat['combatlog'] as $c):?>
				<li><?=preg_replace('/AAA/', '', $c)?></li>
			<?php endforeach; ?>
			</ul>
			<br />
			<div>Aktuelle Runde <strong><?=$combat['round']?></strong> Iniphase <strong><?=(isset($combat['iniphase'])) ? $combat['iniphase']+1 : max(array_keys($combat['ini']));?></strong></div>		
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
			<input type="hidden" name="armor" id="armor" value="<?=$_POST['armor'];?>" />
			<?=form_hidden('sendAction', true);?>
			<div class="col-md-6" style="color: white">
			<?php if($combat['player']['ammo'] > 1): ?>
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
			<?php if($combat['player']['ammo'] > 1): ?>
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