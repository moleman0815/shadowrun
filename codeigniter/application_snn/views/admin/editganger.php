<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');

$n_options = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'); 
$r_options = array('mensch' => 'Mensch', 'elf' => 'Elf', 'zwerg' => 'Zwerg', 'ork' => 'Ork', 'troll' => 'Troll');
$tt_races = "Zwerge: +1 Kon; +2 Str; +1 Will<br />Elf: +1 Sch; +2 Cha<br />Ork: +3 Kon; +2 Str; -1 Cha; -1 Int<br />Troll: +5 Kon; +4 Str; -1 Sch; -2 Int; -2 Cha";
$tt_typ = "Ganger: +0;<br /> Lonstar: +1 Fern<br />Security: +2 Fern";
?>

<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?>    	
	});

	$('.hastip').tooltipsy();
function checkme() {
	$('#gangerwerteneu').prop('checked', true);

}
</script>
<style>

</style>
<div class="col-lg-8 admininterface">
	<fieldset>
		<legend class="newstitle">Edit Ganger</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>			
			<?=form_open('/admin/editGanger');?>
			<?=form_hidden('editGanger', true);?>
			<input type="hidden" name='lastedit' id="lastedit" value='<?=$gid;?>' />
				<input type="hidden" name='gid' id='gid' value="<?=$ganger[0]['gid'];?>" />		
			<div class="col-sm-6">
				<label class="control-label" for="gangername" style="width:150px">Ganger Name</label>
				<?=form_input(array('id' => 'gangername', 'name' =>'gangername', "class" => "input-xlarge", "value" => $ganger[0]['ganger_name']), '');?>
				<br />
				<label class="control-label select_width" for="gangerrace" style="width:150px">Ganger Rasse</label>
				<?=form_dropdown('gangerrace', $r_options, $ganger[0]['race'], 'id="gangerrace"');?>
				<span onmouseover="Tip('<?=$tt_races?>')" onmouseout="UnTip()" style="padding-left: 20px"><img src="/secure/snn/assets/img/icons/help.png" /></span>
				<br />			
				<label class="control-label select_width" for="gangergender" style="width:150px">Ganger Geschlecht</label>
				<?=form_dropdown('gangergender', array('male' => 'männlich', 'female' => 'weiblich'), $ganger[0]['gender'], 'id="gangergender"');?>
				<br />		
				<label class="control-label select_width" for="gangerlevel" style="width:150px">Ganger Level</label>
				<?=form_dropdown('gangerlevel', $n_options, $ganger[0]['level'], 'id="gangerlevel" onchange="checkme()"');?>
				<br />
				<label class="control-label select_width" for="gangertype" style="width:150px">Ganger Type</label>
				<?=form_dropdown('gangertype', array('combat' => 'Kampf', 'magic' => 'Magie'), $ganger[0]['type'], 'id="gangertype"');?>
				<br />
				<label class="control-label select_width" for="gangerarchtyp" style="width:150px">Ganger Archetyp</label>
				<?=form_dropdown('gangerarchetyp', array('ganger' => 'Ganger', 'lonestar' => 'Lonestar', 'security' => 'Security'), $ganger[0]['archetyp'], 'id="gangerarchetyp"');?>
				<span onmouseover="Tip('<?=$tt_typ?>')" onmouseout="UnTip()" style="padding-left: 20px"><img src="/secure/snn/assets/img/icons/help.png" /></span>
				<br />
				<label class="control-label" for="gangerbio" style="width:150px;" valign="top">Ganger Bio</label>
				<?=form_textarea(array('id' => 'gangerbio', 'name' =>'gangerbio', "class" => "input-xlarge", "value" => $ganger[0]['bio']));?>
				<br />						
				<label class="control-label" for="gangerbio" style="width:150px;" valign="top">Ganger Werte</label>
				<div style="margin-left:150px">
					<table>
						<tr>
							<td><b>Konstitution: </b></td><td style="padding-left: 10px"><?=$ganger[0]['body']?></td>
						</tr>
						<tr>
							<td><b>Schnelligkeit: </b></td><td style="padding-left: 10px"><?=$ganger[0]['quickness']?></td>
						</tr>
						<tr>
							<td><b>Stärke: </b></td><td style="padding-left: 10px"><?=$ganger[0]['strength']?></td>
						</tr>
						<tr>
							<td><b>Charisma: </b></td><td style="padding-left: 10px"><?=$ganger[0]['charisma']?></td>
						</tr>						
						<tr>
							<td><b>Intelligenz: </b></td><td style="padding-left: 10px"><?=$ganger[0]['intelligence']?></td>
						</tr>
						<tr>
							<td><b>Willenskraft: </b></td><td style="padding-left: 10px"><?=$ganger[0]['willpower']?></td>
						</tr>	
						<tr>
							<td><b>Magie: </b></td><td style="padding-left: 10px"><?=$ganger[0]['magic']?></td>
						</tr>
						<tr>
							<td><b>Fernkampf: </b></td><td style="padding-left: 10px"><?=$ganger[0]['armed_longrange']?></td>
						</tr>
						<tr>
							<td><b>Nahkampf: </b></td><td style="padding-left: 10px"><?=$ganger[0]['armed_combat']?></td>
						</tr>						
					</table>
				</div>
				<br />
				<label class="control-label" for="gangerwerteneu" style="width:150px;" valign="top">Ganger Werte neu würfeln?</label>
				<?=form_checkbox(array('id' => 'gangerwerteneu', 'name' => 'gangerwerteneu', 'value' => '1'), true);?>
			</div>
			<div class="col-sm-6">
				<label class="control-label" for="gangerportrait" style="width:150px">Ganger Portrait</label><br />
				<table><tr>

				<?php for($x=0; $x<count($images);$x++): ?>
					<?php echo ($x%8 == 0) ? '</tr><tr>' : '';?>
					<td style="padding-right:5px">
						<img src="/secure/snn/assets/img/combat/ganger/<?=$images[$x];?>" style="height:50px;wight:50px" /><br />
						<?php $checked = ($ganger[0]['profile'] == $images[$x]) ? ' checked="checked"' : ''?>
						<input type="radio" name="gangerportrait" value="<?=$images[$x];?>" <?=$checked;?> *>
						
					</td>

				<?php endfor; ?>
				</tr></table>
			</div>
			<div style="clear:both"></div>
			<div class="col-sm-12">
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'Ganger editieren', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<br />
	<a href="/secure/snn/admin/gangerVerwalten"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;	
</div>