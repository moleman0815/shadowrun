<?php
#	_debug($data['inv'][0]['spells']);
?>

	<?php if(!empty($data['char'][0])): ?>
	<div>
		<?php if($data['char'][0]['avatar']): ?>
			<div class="newstitle">Portrait</div>
			<table class="table table-condensed newselement">
				<tbody>
					<tr>
						<td><img src="/secure/snn/assets/img/avatar/<?=$data['char'][0]['avatar'];?>" alt="" /></td>
					</tr>
				</tbody>
			</table>
		<?php endif; ?>
		<div class="newstitle">Charakter <span class="small"> (* modifizierte Werte)</span></div>
		<table class="table table-condensed newselement">
			<thead>
				<tr>
					<th>Char</th>
					<th>Rasse</th>
					<th>KON</th>
					<th>SCH</th>
					<th>STR</th>
					<th>CHA</th>
					<th>INT</th>					
					<th>WIL</th>
					<th>ESS</th>
					<th>MAG</th>		
				</tr>
			</thead>
			<tbody>				
				<tr>
					<td><?=ucfirst($data['char'][0]['charname']);?></td>
					<td><?=ucfirst($data['char'][0]['race']);?></td>
					<td><?=ucfirst($data['char'][0]['body']);?></td>
					<td><?=ucfirst($data['char'][0]['quickness']);?></td>
					<td><?=ucfirst($data['char'][0]['strength']);?></td>
					<td><?=ucfirst($data['char'][0]['charisma']);?></td>
					<td><?=ucfirst($data['char'][0]['intelligence']);?></td>
					<td><?=ucfirst($data['char'][0]['willpower']);?></td>
					<td><?=ucfirst($data['char'][0]['essence']);?></td>
					<td><?=ucfirst($data['char'][0]['magic']);?></td>					
				</tr>
			</tbody>
		</table>
		
		
		<div class="newstitle">Werte</div>
		<table class="table table-condensed newselement">
			<thead>
				<tr>
					<th>Nahkampf</th>
					<th>Fernkampf</th>
					<th>IniW&uuml;rfel</th>
					<th>Reaktion</th>	
					<?php if($data['char'][0]['magic'] > 0): ?>
						<th>Magie</th>
					<?php endif;?>	
				</tr>
			</thead>
			<tbody>				
				<tr>
					<td><?=$data['char'][0]['armed_combat'];?></td>
					<td><?=$data['char'][0]['armed_longrange'];?></td>
					<td><?=floor($data['char'][0]['inidice']+$data['char'][0]['inidice_mod']);?>D6</td>
					<td>+<?=floor(($data['char'][0]['quickness']+$data['char'][0]['intelligence'])/2)+$data['char'][0]['reaction_mod'];?></td>
					<?php if($data['char'][0]['magic'] > 0): ?>
						<td><?=$data['char'][0]['magic'];?></td>
					<?php endif; ?>							
				</tr>
			</tbody>
		</table>

		<div class="newstitle">Inventar</div>				
		<table class="table table-condensed newselement">
			<thead>
				<tr>
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
					<td><?=$data['inv'][0]['money']?> &yen;</td>
					<td>
						<?php if(!empty($data['inv'][0]['weapon'])): ?>						
							<?php foreach($data['inv'][0]['weapon'] as $w): ?>
							<?php $tt_weapon = "<b>Name: </b>".$w['name']."<br /><b>Magazin :</b>".$w['ammo']."<br /><b>Modus :</b>".$w['mode']."<br /><b>Damage: </b>".$w['damage'];?>
							<?=$w['name']?>&nbsp;<span onmouseover="Tip('<span style=\'width: 350px\'><?=$tt_weapon?></span>')" onmouseout="UnTip()" style="padding-left: 20px;cursor:pointer"><img src="/secure/snn/assets/img/icons/help.png" /></span><br />
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
					<td><?=$data['inv'][0]['maxammo']?></td>
					<td><?=$data['inv'][0]['medipacks']?></td>
					<td><?=$data['inv'][0]['grenades']?></td>
					<td>
						<?php if(!empty($data['inv'][0]['armor'])): ?>
							<?php foreach($data['inv'][0]['armor'] as $w): ?>
								<?php $tt_armor = "<b>Name: </b>".$w['name']."<br /><b>Ballistisch :</b>".$w['armor'];?>
								<?=$w['name']?>&nbsp;<span onmouseover="Tip('<span style=\'width: 350px\'><?=$tt_armor?></span>')" onmouseout="UnTip()" style="padding-left: 20px;cursor:pointer"><img src="/secure/snn/assets/img/icons/help.png" /></span><br />
							<?php endforeach; ?>
						<?php else: ?>
							0				
						<?php endif; ?>		
					</td>					
					<td>
						<?php if(!empty($data['inv'][0]['cyberware'])): ?>
						<?php foreach($data['inv'][0]['cyberware'] as $w): ?>
							<?=$w['name']?>&nbsp;<br />
						<?php endforeach; ?>				
						<?php endif; ?>		
					</td>					
				</tr>
			</tbody>
		</table>
		<?php if($data['char'][0]['magic'] > 0): ?>
		<div class="newstitle">Zauber</div>				
			<table class="table table-condensed newselement">
				<thead>
					<tr>
						<th>Name</th>
						<th>Typ</th>
						<th>Subtype</th>
						<th>MW</th>
						<th>Entzug</th>
						<th>Wirkung/ Schaden</th>						
					</tr>
				</thead>
				<tbody>	
					<?php foreach($data['inv'][0]['spells'] as $s):?>
					<tr>
						<td><?=$s['name']?></td>
						<td><?=ucfirst($s['typ'])?></td>
						<td><?=($s['subtype'] == 'p') ? 'physisch' : 'mental'?></td>
						<td><?=($s['mw'] == 'k') ? 'Konstitution' : 'Speziell'?></td>
						<td>
							<?php 
								$tmp = explode(';', $s['entzug']);
								echo ($tmp[0] < 0) ? $tmp[0] : '+'.$tmp[0];
								echo "(".ucfirst($tmp[1]).")";
							?>
						</td>
						<td>
							<?php 
								if (empty($s['wirkung'])) {
									echo "selbst gew&auml;hlt";
								} else {
									$tmp = explode(';', $s['wirkung']);
									echo ($tmp[0] < 0) ? $tmp[0] : '+'.$tmp[0];
									echo "(".ucfirst($tmp[1]).")";
								}
							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
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
