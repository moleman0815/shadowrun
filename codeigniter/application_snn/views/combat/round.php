<?php
	#rsort($combat['ini']);
	#_debugDie($combat['ini']);
	#$combat['player']['maxammo'] = 3;
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');		
?>

<style>
	td {
		border: 1px solid white;
		padding: 7px;
	}
</style>

<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
	});
</script>


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
				<?php foreach($combat['ini'] as $key => $value): ?>
					Phase <?=$key .' : '. $value;?><br />
				<?php endforeach; ?>
				<br />

			<b>Aktuelle Kampfrunde:</b><br />
			 <?=$combat['round']?><br />
			 <br />
			<b>Aktuelle Phase:</b><br />
			 <?= (isset($combat['iniphase'])) ? $combat['iniphase']+1 : max(array_keys($combat['ini'])); ?><br />
		</div>
				<div style="clear:both"></div>		
		<br />		
		<div class="col-md-7" style="float:left;">
			<div class="newstitle">Bisheriger Kampfverlauf:</div>
			<br />
			<?php foreach($combat['combatlog'] as $c):?>
				<?=preg_replace('/AAA/', '', $c)?>
			<?php endforeach; ?>
			<br /><br />
			<div>Aktuelle Runde <?=$combat['round']?> Iniphase <?=(isset($combat['iniphase'])) ? $combat['iniphase']+1 : max(array_keys($combat['ini']));?></div>		
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
				<thead>
					<th colspan="2"><b>Spieler</b></th>
					<th colspan="2"><b>Waffe</b></th>
				</thead>
				<tbody>
				<tr>
					<td><b>Name:</b></td>
					<td><?=$combat['player']['name']?></td>
					<td><b>Waffe:</b></td>
					<td><?=$combat['player']['weapon_name']?></td>
				</tr>
				<tr>
					<td><b>HP (physisch):</b></td><td><?=$combat['player']['health']?></td>
					<td><b>Schaden:</b></td><td><?=$combat['player']['weapon_soak'].$combat['player']['weapon_default']?></td>
				</tr>
				<tr>
					<td><b>HP (mental):</b></td><td><?=$combat['player']['spirit']?></td>

					<td><b>Munition:</b></td><td <?=$mclass;?>><?=$combat['player']['ammo']?></td>
				</tr>	
				<tr>					
					<td><b>Zustand:</b></td><td <?=$hclass;?>><?=$combat['player']['status']?></td>
					
					<td><b>Modus:</b></td><td><?=$combat['player']['fire_mode']?></td>
				</tr>					
				<tr>
					<td colspan="2" rowspan="2">
						<?php if(!empty($combat['player']['avatar'])):?>
							<img src="/secure/snn/assets/img/avatar/<?=$combat['player']['avatar'];?>" />
						<?php endif; ?>
					</td>					
					<td><b>Medipacks:</b></td><td <?=$mpclass;?>><?=$combat['player']['small_medipacks']?></td>
				</tr>	
				<tr>
					<td><b>ErsatzMunition:</b></td><td <?=$mpclass;?>><?=$combat['player']['maxammo']?></td>
				</tr>										
				</tbody>
			</table>
			</div>
			<br />
			<b>Gegner</b><br />		
			<?php foreach($combat['enemy'] as $e):?>
			
			<div style="border:1px solid white; padding: 3px">
					Name: <?=$e['name']?><br />
					HP (physisch):  <?=$e['health']?><br />
					HP (mental):  <?=$e['spirit']?><br />
					<?php $hclass = ($e['status'] == 'alive') ? 'style="color:green"' : 'style="color:red"'; ?>
					Zustand: <span <?=$hclass?>><?=$e['status']?></span><br />
			</div>
			<br />
			<?php endforeach; ?>
			<br />
		</div>
		<div style="clear:both"></div>
		<br />
		<div><b>Welche Aktion möchtest du ausführen (zählt für beide Ini-Aktionen)?</b></div>
		<?=form_open('/combatzone/nextRound');?>
		<?=form_hidden('sendAction', true);?>
	<?php if($combat['player']['ammo'] > 1): ?>
		<?php if(in_array('HM', $mode)): ?>
			<?=form_radio('action','singleshot', array('checked' => true));?>	- einzelner Schuß (-2 Munition)<br />
		<?php endif; ?>
		<?php if($combat['player']['ammo'] > 5): ?>
			<?php if(in_array('SM', $mode)): ?>
				<?=form_radio('action','salve');?>	- Salve (-6 Munition)<br />
			<?php endif; ?>
		<?php endif; ?>
		<?php if($combat['player']['ammo'] > 12): ?>		
			<?php if(in_array('AM', $mode)): ?>
				<?=form_radio('action','automatic');?>	- Automatik (-12 Munition)<br />
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<br />
	<?php if($combat['player']['health'] < 10 && $combat['player']['small_medipacks'] > 0): ?>
		<?=form_radio('action','smallheal');?>	- Medipack einwerfen (+3 Leben)<br />
	<?php endif; ?>
		<?=form_radio('action','cover');?>	- Deckung (MW für Gegner erhöht)<br />
	<?php if(($combat['player']['maxammo'] > 0) && ($combat['player']['ammo'] < $combat['player']['ammo_default'])) : ?>
		<?=form_radio('action','reload');?>	- Nachladen<br />
	<?php endif; ?>
		<?=form_radio('action','flee');?>	- Aus dem Kampf fliehen!<br />
		<br />
		<?=form_submit(array('id'=>'submit', 'value' => 'Aktion ausführen!', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
		<?=form_close();?>		
	</fieldset>
