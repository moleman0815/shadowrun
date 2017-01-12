<?php
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');	

	$opt_weapon = '<option value="">Fernkampfwaffe wählen</option>';
	$opt_melee = '<option value="">Nahkampfwaffe wählen</option>';
	$opt_armor = '<option value="">Rüstung wählen</option>';
	if (!empty($inv[0]['weapon'])) {
		foreach ($inv[0]['weapon'] as $w) {
			if ($w['type'] == 'weapon') {
				$opt_weapon .= '<option value="'.$w['wid'].'">'.$w['name'].' ('.$w['damage'].')</option>';
			} else if ($w['type'] == 'melee') {
				$opt_melee .= '<option value="'.$w['wid'].'">'.$w['name'].' ('.$w['damage'].')</option>';
			}
		}
	}
	if (!empty($inv[0]['armor'])) {
		foreach ($inv[0]['armor'] as $w) {
			$opt_armor .= '<option value="'.$w['wid'].'">'.$w['name'].' ('.$w['armor'].')</option>';
		}
	}
	#_debugDie($inv);
?>

<style>
.tile {  
background: linear-gradient(135deg, rgba(97, 100, 101, 0.3) 0%, rgba(226, 244, 255, 0.3) 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    color: #000000;
    font-size: 12px;
    padding: 6px 6px 6px;
    height: 160px;
    z-index: 1;
}

.blue {
    background: #7E83A3;
	}
.mission_bg {
	background: url("/secure/snn/assets/img/combat/missions.jpg") no-repeat center center fixed;
}
.ganger_name {
	font-size: 16px;
	font-weight: bold;
}
	
select {
	color: black;
}
</style>
<script>
$( document ).ready(function() {
	<?php if($error): ?>	
		$("#error").fadeOut(8000);    	
	<?php endif; ?>	

	$("a#image1").fancybox({
		'titleShow' : false
	}); 

});




 function calculateFight(mid) {
 	$.ajax({
		type: 'POST',
		url: '/secure/snn/combatzone/calculateFight', 
		data: {mid: mid}, 
		success: function (data) {
			//var json = jQuery.parseJSON(data);	

			var html = 'Resultat';
			
			$('#combat_result').html(html);				
	}});				
 }
</script>


	<a href="/secure/snn/combatzone"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;		
	<br />
	<div class="row-fluid">
		<?php if($error): ?>
			<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left: 25%;border:3px solid black" id="error"><b><?=$error?></b></div>
			<br />
		<?php endif; ?>
	<?php if($char): ?>
		<?php $anzahl = count(explode(';', $mission[0]['gid'])); ?>
		<div class="col-md-12">
			<fieldset class="newselement">
				<legend class="newstitle"><?=$mission[0]['title']?></legend>
				<?php if($mission[0]['image']): ?>
					<div class="col-sm-12">
						<img src="/secure/snn/assets/img/combat/missionsbanner/<?=$mission[0]['image']?>" style="width:100%"/>
					</div>
					<br />
				<?php endif; ?>
				<div class="col-sm-3">
					Level: <?=$mission[0]['level']?><br />
					Ganger: <?=$anzahl;?><br />
					Missionstyp: <?=count($mission[0]['type'])?><br />
				</div>
				<div class="col-sm-3">
					Gewinn: <?=$mission[0]['cash']?> &#165; <br />
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
			</fieldset>
		</div>
		<div style="clear:both"></div>
		<br />
		<div class="col-md-12">
		<fieldset class="newselement">
			<legend class="newstitle" style="cursor:pointer" onclick="$('#text_story').toggle('fast');">Story Hintergrund<span style="float:right; margin-right: 15px">(click to open)</span></legend>		
			<div id="text_story" style="display:none">
			<?php if($mission[0]['johnson']): ?>
				<div style="float:left;margin: 0 10px 10px 0">
					<img src="/secure/snn/assets/img/combat/johnson/<?=$mission[0]['johnson']?>" alt="Mr. Johnson" title="Mr. Johnson"/>
				</div>
			<?php endif; ?>
				<?php if($mission[0]['storyimage']): ?>
					<div style="float:right;margin: 0 0 10px 10px">
						<a id="image1" href="/secure/snn/assets/img/combat/storyimage/<?=$mission[0]['storyimage']?>">
							<img src="/secure/snn/assets/img/combat/storyimage/<?=$mission[0]['storyimage']?>" style="width:220px;height:220px" alt="image1" />
						</a>
					</div>
				<?php endif; ?>	
				<div style="float:none;width:70%">			
					<?=$mission[0]['text_story']?>
				</div>
				
			</div>
		</fieldset>
		</div>
		<div style="clear:both"></div>
		<br />
		<div class="col-md-12">
			<fieldset class="newselement">
				<?=form_open_multipart('combatzone/fight/'.$this->uri->segment(3));?>
				<?=form_hidden('mid', $this->uri->segment(3));?>
				<div class="newstitle">Ausrüstung wählen</div>
				<br />
				<div class="col-sm-3">
					<b>Fernkampfwaffe auswählen:</b>
					<br /><br />
					<select name="weapon">
						<?=$opt_weapon;?>
					</select>
				</div>
				<div class="col-sm-3">
					<b>Nahkampfwaffe auswählen:</b>
					<br /><br />
					<select name="melee">
						<?=$opt_melee;?>
					</select>
				</div>
				<div class="col-sm-3">
					<b>Rüstung auswählen:</b>
					<br /><br />
					<select name="armor">
						<?=$opt_armor;?>
					</select>
				</div>
			</fieldset>			
		</div>
		<div style="clear:both"></div>
		<br />		
		<?php if(!empty($ganger[0])): ?>
			<?php foreach($ganger as $r): ?>			
				<div class="col-md-12">
				<fieldset class="newselement">
					<span class="ganger_name"><?=$r[0]['ganger_name']?> </span><br />
					<div class="col-md-4">
						<div class="col-md-6">
							
							<img src="/secure/snn/assets/img/combat/ganger/<?=$r[0]['profile'];?>" alt="<?=$r[0]['ganger_name'];?>" title="<?=$r[0]['ganger_name'];?>" />
						</div>
						<div class="col-md-6">

							Rasse: <?=ucfirst($r[0]['race'])?> <?php echo ($r[0]['gender'] == 'male') ? '<i class="fa fa-mars"></i>' : '<i class="fa fa-venus"></i>'; ?><br />
							Level: <?=$r[0]['level']?><br />
							Typ: <?=ucfirst($r[0]['archetyp'])?><br /><br />		
							
						</div>
					</div>
					<div class="col-sm-6">
						<table>
							<tr>
								<td>Konstitution:</td>
								<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['body']?></span></td>
								<td>Intelligenz:</td>
								<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['intelligence']?></span></td>
								<td>Fernkampf:</td>
								<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['armed_longrange']?></span></td>								
							</tr>
							<tr>
								<td>Schnelligkeit:</td>
								<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['quickness']?></span></td>
								<td>Willenskraft:</td>
								<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['willpower']?></span></td>	
								<td>Nahkampf:</td>
								<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['armed_combat']?></span></td>
							</tr>
							<tr>
								<td>St&auml;rke:</td>
								<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['strength']?></span></td>
								<?php if($r[0]['type'] == 'magic'): ?>
									<td>Magie:</td>
									<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['magic']?></span></td>								
								<?php else: ?>
									<td></td>
								<?php endif; ?>
							</tr>
							<tr>
								<td>Charisma:</td>
								<td><span class="btn btn-danger btn-xs" STYLE="margin:0 10px"><?=$r[0]['charisma']?></span></td>
								<td></td>
							</tr>
						</table>
					</div>
					<div style="clear:both"></div>
					<br />
					<div class="col-md-12" >
						<br />
						<?=$r[0]['bio']?>		
					</div>
				</fieldset>
				</div>
				<div style="clear:both"></div>			
				<br />
			<?php endforeach; ?>
		<?php endif; ?>
			<div class="col-sm-12 newselement">
          	<center><?=form_submit(array('id'=>'submit', 'value' => 'Start Mission', 'name' => 'submit', 'class' => 'btn btn-danger'),'', 'style="width: 300px"');?></center>
			<?=form_close();?>					
				<br />
				<div id="combat_result"></div>
			</div>
	<?php else: ?>
		<div class="errormsg">
			Um an Missionen teilnehmen zu können, musst du erst deinen Charakter hinterlegen.<br />
			<a href="/secure/snn/desktop/einstellungen/">HIER</a> gehts lang ....
		</div>
		<br />
	<?php endif; ?>
		<br />&nbsp;	
	</div>

