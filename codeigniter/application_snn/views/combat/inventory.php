<?php
#	_debug($inv);
?>

	<br />
	<?php if(!empty($char)): ?>
	<div>
			<br />
		<div class="newstitle">Inventar</div>
		<br />				
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>Char</th>
					<th>Geld</th>
					<th>Waffen</th>
					<th>Ersatzmunition</th>
					<th>Medipack</th>
					<th>Granaten</th>
					<th>Rüstungen</th>					
					<th>Cyberware</th>		
				</tr>
			</thead>
			<tbody>
				
				<tr>
					<td><?=ucfirst($char[0]['charname']);?></td>
					<td><?=$inv[0]['money']?> &yen;</td>
					<td>
						<?php if(!empty($inv[0]['weapon'])): ?>						
							<?php foreach($inv[0]['weapon'] as $w): ?>
							<?php $tt_weapon = "<b>Name: </b>".$w['name']."<br /><b>Magazin :</b>".$w['ammo']."<br /><b>Modus :</b>".$w['mode']."<br /><b>Damage: </b>".$w['damage'];?>
							<?=$w['name']?>&nbsp;<span onmouseover="Tip('<span style=\'width: 350px\'><?=$tt_weapon?></span>')" onmouseout="UnTip()" style="padding-left: 20px;cursor:pointer"><img src="/secure/snn/assets/img/icons/help.png" /></span><br />
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
					<td><?=$inv[0]['maxammo']?></td>
					<td><?=$inv[0]['medipacks']?></td>
					<td><?=$inv[0]['grenades']?></td>
					<td>
						<?php if(!empty($inv[0]['armor'])): ?>
						<?php foreach($inv[0]['armor'] as $w): ?>
							<?php $tt_armor = "<b>Name: </b>".$w['name']."<br /><b>Ballistisch :</b>".$w['armor'];?>
							<?=$w['name']?>&nbsp;<span onmouseover="Tip('<span style=\'width: 350px\'><?=$tt_armor?></span>')" onmouseout="UnTip()" style="padding-left: 20px;cursor:pointer"><img src="/secure/snn/assets/img/icons/help.png" /></span><br />
						<?php endforeach; ?>				
						<?php endif; ?>		
					</td>					
					<td>
						<?php if(!empty($inv[0]['cyberware'])): ?>
						<?php foreach($inv[0]['cyberware'] as $w): ?>
							<?=$w['name']?>&nbsp;<br />
						<?php endforeach; ?>				
						<?php endif; ?>		
					</td>					
				</tr>
			</tbody>
		</table>
		<br />
		<a href="/secure/snn/combatzone/marketplace"><button class="btn btn-warning btn-lb"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;zum Marktplatz</button></a>
		<br />		
	</div>
	<?php else: ?>
	<br />
		<div class="errormsg">
			Um an Missionen teilnehmen zu können, musst du erst deinen Charakter hinterlegen.<br />
			<a href="/secure/snn/desktop/einstellungen/">HIER</a> gehts lang ....
		</div>
		<br />
	<?php endif; ?>
	<br />&nbsp;	
