
<?php
	$status = $combatstats[0]['status'];
	$combat = json_decode($combatstats[0]['combatlog']);
	#$status = 'loss';

?>

	<a href="/secure/snn/combatzone"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />
	<div class="row-fluid">
		<?php if($status == 'success'): ?>
			<div class="col-sm-12 newselement">		
				<div align="center"><img src="/secure/snn/assets/img/layout/combat_win.png" alt="Gewonnen" title="Gewonnen" /></div>
			</div>
			<div style="clear:both"></div>				
			<br />
		<?php else: ?>
			<div class="col-sm-12 newselement">		
				<div align="center"><img src="/secure/snn/assets/img/layout/combat_loss.png" alt="Verlore" title="Verlore" /></div>
			</div>
			<div style="clear:both"></div>				
			<br />
		<?php endif; ?>
		<?php if($status == 'success' && $mission[0]['text_win']): ?>
				<div class="newstitle">Mission: <?=$mission[0]['title']?></div>			
				<div class="tile col-sm-12 newselement">				
					<div class="col-sm-2">
						Level: <?=$mission[0]['level']?><br />
						Gewinn: <?=$combatstats[0]['cash']?> &#165; <br />
						Missionstyp: <?=count($mission[0]['type'])?>
					</div>
					<div class="col-sm-9">
						<?=$mission[0]['text_win']?>
					</div>
				</div>
		<?php elseif($status == 'fail' && $mission[0]['text_loss']): ?>
				<div class="newstitle">Mission: <?=$mission[0]['title']?></div>			
				<div class="tile col-sm-12 newselement">
					<div class="col-sm-3">
						Level: <?=$mission[0]['level']?><br />
						Verlust: <?=$combatstats[0]['lost']?> &#165; <br />
						Missionstyp: <?=count($mission[0]['type'])?>
					</div>
					<div class="col-sm-8">
						<?=$mission[0]['text_loss']?>
					</div>
				</div>	
		<?php else: ?>
			<?php $anzahl = count(explode(';', $mission[0]['gid'])); ?>
				<div class="newstitle">Mission: <?=$mission[0]['title']?></div>
				<div class="tile col-sm-12 newselement">
					<?php if($mission[0]['image']): ?>
						<div class="col-sm-12">
							<center><img src="/secure/snn/assets/img/combat/missionsbanner/<?=$mission[0]['image']?>" /></center>
						</div>
						<br />
					<?php endif; ?>
					<div class="col-sm-3">
						Level: <?=$mission[0]['level']?><br />
						Ganger: <?=$anzahl;?><br />
						Missionstyp: <?=count($mission[0]['type'])?><br />
					</div>
					<div class="col-sm-3">
						Gewinn: <?=$combatstats[0]['cash']?> &#165; <br />
						Kosten: <?=$mission[0]['expense']?> &#165; <br />
						NPCs: <?=$mission[0]['extras']?><br />
					</div>
					<div class="col-sm-3">
						Runner: <?=$mission[0]['member']?><br />
					</div>				
					<div style="clear:both"></div>
					<div class="col-sm-12">
						<br />
						<?=$mission[0]['text']?><br />
					</div>					
				</div>
		<?php endif; ?>
		<div style="clear:both"></div>		
	</div>
	<br >
	<div class="newstitle">Du hast folgende Ganger bekämpft</div>
	<?php foreach($ganger as $r): ?>
			
				<div class="tile col-sm-12 newselement">
					<div class="col-sm-3">
						<img src="/secure/snn/assets/img/combat/ganger/<?=$r[0]['profile'];?>" alt="<?=$r[0]['ganger_name'];?>" title="<?=$r[0]['ganger_name'];?>" />
					</div>
					<div class="col-sm-9">
						<span class="ganger_name"><?=$r[0]['ganger_name']?> </span>
					</div>
					<div class="col-sm-3" style="margin-left: -110px">
						Rasse: <?=ucfirst($r[0]['race'])?> <?php echo ($r[0]['gender'] == 'male') ? '<i class="fa fa-mars"></i>' : '<i class="fa fa-venus"></i>'; ?><br />
						Level: <?=$r[0]['level']?><br />
						Typ: <?=ucfirst($r[0]['archetyp'])?><br /><br />		
						
					</div>					
				</div>
			<div style="clear:both"></div>			
			<br />
		<?php endforeach; ?>
	<div style="clear:both"></div>
	<style>
		li {
			list-style-type: none;
		}
	</style>

	<div class="newstitle" style="cursor:pointer" onclick="$('#combatlog').toggle('fast');"><?=substr($combat[count($combat)-1], 4);?> (Combatlog anzeigen)</div>
	<div id="combatlog" style="display:none">
		<div style="list-style-type: none;" class="newselement">
			<ul>
		<?php foreach ($combat as $c): ?>
			<?php if (preg_match("/AAA/", $c)): ?>
				<b></ul><br /><?=substr($c ,4)?><ul></b>
			<?php elseif (preg_match("/XXX/", $c)): ?>
				<?php continue;?>
			<?php else: ?>
				<li><?=$c;?></li>
			<?php endif; ?>
		<?php endforeach; ?>
			</ul>
		</div>
		<br />&nbsp;	
	</div>
