<?php
	_debug($combat);
$combat['player']['maxammo'] = 3;
$error = $this->session->userdata('error');
$this->session->unset_userdata('error');
?>

<style>
	td {
		border: 1px solid white;
		padding: 7px;
	}
</style>



	<fieldset class="newselement">
		<legend class="newstitle">Kampfbereit</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left: 25%;" id="error">
					<b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b>
				</div>
			<?php endif; ?>				
		<div class="col-md-8" style="border: 1px solid white; padding: 10px;">
			<b>Initiativeverlauf in Kampfrunde 1:</b> <br />
				<?php foreach($combat['ini'] as $key => $value): ?>
					Phase <?=$key .' : '. $value;?><br />
				<?php endforeach; ?>
				<br />

			<b>Aktuelle Kampfrunde:</b><br />
			 <?=$combat['round']?><br />
			 <br />
			<b>Aktuelle Phase:</b><br />
			 <?=$combat['iniphase']+1?><br />
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
			<div>Aktuelle Runde <?=$combat['round']?> Iniphase <?=($combat['iniphase']+1)?></div>		
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
			<div>
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
	</fieldset>
