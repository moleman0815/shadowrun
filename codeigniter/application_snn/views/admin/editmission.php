<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');

$n_options = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'); 
$r_options = array('mensch' => 'Mensch', 'elf' => 'Elf', 'zwerg' => 'Zwerg', 'ork' => 'Ork', 'troll' => 'Troll');
$options = '';
foreach($allganger as $g) {
	$options .= '<option value="'.$g['gid'].'">'.$g['ganger_name'].' ('.$g['level'].')</option>';
}
$g_options = '<option value="">Mission auswählen</option>';
foreach ($allmissions as $a) {
	$g_options .= '<option value="'.$a['mid'].'">'.$a['title'].'</option>';
}
?>
<script>
$( document ).ready(function() {
	var mid = $('#lastedit').val();
	if (mid) {
		getMission(mid);
	}
	var mid2 = '<?=$this->uri->segment(3)?>';
	if (mid2) {
		getMission(mid2);
	}

	<?php if($error): ?>	
		$("#error").fadeOut(7000);    	
	<?php endif; ?>
	<?php if($success): ?>
		$("#success").fadeOut(7000);    	
	<?php endif; ?> 	
});

 </script>
<div class="col-lg-8 admininterface">
	<fieldset>
		<legend class="newstitle">Edit Mission</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>				
			<div id="missiontitle_error"></div>
			<?=form_open('/admin/editMission');?>
			<?=form_hidden('editMission', true);?>
			<input type="hidden" name='mid' id='mid' />
			<input type="hidden" name='lastedit' id="lastedit" value='<?=$mid;?>' />
				<label class="control-label" for="gangername" style="width:150px">Select Mission</label>
				<select name="mission" id="mission" onchange="getMission(this.value)">
					<?=$g_options;?>
				</select>
				<br />
				<hr />
				<br />				
			<div class="col-sm-6">
				<label class="control-label" for="missionstitle" style="width:150px">Missions Titel</label>
				<?php $js = 'onblur="checkMissionTitle(this.value)"'; ?>
				<?=form_input(array('id' => 'missionstitle', 'name' =>'missionstitle', "class" => "input-xlarge"), '', $js);?>
				<br />				
				<label class="control-label select_width" for="missionlevel" style="width:150px">Missions Level</label>
				<?=form_dropdown('missionlevel', $n_options, '', 'id="missionlevel"');?>
				<br />
				<label class="control-label select_width" for="missiontype" style="width:150px">Missions Type</label>
				<?=form_dropdown('missiontype', array('combat' => 'Kampf', 'magic' => 'Magie'), '', 'id="missiontype"');?>
				<br />
				<label class="control-label select_width" for="missioncash" style="width:150px">Missions Einkommen</label>
				<?=form_input(array('id' => 'missioncash', 'name' =>'missioncash', "class" => "input-xlarge"));?>
				<br />
				<label class="control-label select_width" for="missionexpense" style="width:150px">Missions Ausgaben</label>
				<?=form_input(array('id' => 'missionexpense', 'name' =>'missionexpense', "class" => "input-xlarge"));?>
				<br />
				<label class="control-label select_width" for="missionextras" style="width:150px">Missions NSCs</label>
				<?=form_dropdown('missionextras', array('0' => '0','1' => '1','2' => '2','3' => '3','4' => '4'), '', 'id="missionextras"');?>
				<br />
				<label class="control-label select_width" for="missionmember" style="width:150px">Mission Runner</label>
				<?=form_dropdown('missionmember', array('1' => '1'), '', 'id="missionmember"');?>
				<br /><br />
				<label class="control-label select_width" for="missionmember" style="width:150px">Mr Johnson</label>				
				<table>
					<tr>
				<?php for($x=0; $x<count($johnson);$x++): ?>
					<?php if ($x%4==0) { echo "</tr><tr>"; }?>
					<td>
						<img src="/secure/snn/assets/img/combat/johnson/<?=$johnson[$x];?>" style="height:110px;width:110px" />
						<?=form_radio(array('id' => substr($johnson[$x],0,-4), 'name' => 'johnson', 'value' => $johnson[$x]), true);?>
						<br />
					</td>
				<?php endfor; ?>
					</tr>
				</table>
				<br />
				<label class="control-label select_width" for="missionmember" style="width:150px">Story Image</label>				
				<table>
					<tr>
				<?php for($x=0; $x<count($story);$x++): ?>
					<?php if ($x%2==0) { echo "</tr><tr>"; }?>
					<td>
						<img src="/secure/snn/assets/img/combat/storyimage/<?=$story[$x];?>" style="height:220px;width:220px" />
						<?=form_radio(array('id' => substr($story[$x],0,-4), 'name' => 'storyimage', 'value' => $story[$x]), true);?>
						<br />
					</td>
				<?php endfor; ?>
					</tr>
				</table>	
				<br /><br />
				<label class="control-label" for="missiondelete" style="width:150px;" valign="top">Mission löschen</label>
				<?=form_checkbox(array('id' => 'missiondelete', 'name' => 'missiondelete', 'value' => '1'), true);?>
						
			</div>
			<div class="col-sm-6">
				<label class="control-label" for="missionstext" style="width:150px;" valign="top">Missions Teasertext</label>
				<br />
				<?=form_textarea(array('id' => 'missionstext', 'name' =>'missionstext', "class" => "input-xlarge", "style" => "width:400px;height:150px"));?>
				<br />
				<label class="control-label" for="missionsstorytext" style="width:150px;" valign="top">Missions Storytext</label>
				<br />
				<?=form_textarea(array('id' => 'missionsstorytext', 'name' =>'missionsstorytext', "class" => "input-xlarge", "style" => "width:400px;height:150px"));?>
				<br />	
				<label class="control-label" for="missionswintext" style="width:200px;" valign="top">Missions Gewonnen Text</label>
				<br />
				<?=form_textarea(array('id' => 'missionswintext', 'name' =>'missionswintext', "class" => "input-xlarge", "style" => "width:400px;height:150px"));?>
				<br />
				<label class="control-label" for="missionslosstext" style="width:200px;" valign="top">Missions Verloren Text</label>
				<br />
				<?=form_textarea(array('id' => 'missionslosstext', 'name' =>'missionslosstext', "class" => "input-xlarge", "style" => "width:400px;height:150px"));?>
				<br />												
				<label class="control-label" for="missionstext" style="width:150px;" valign="top">Missions Ganger</label>				
				<br />	
				<select name="missionganger[]" id="missionganger" multiple size="5">
					<?=$options;?>
				</select>
			</div>
			<div style="clear:both"></div>
			<div class="col-sm-12">
				<label class="control-label" for="missionsimage" style="width:150px;" valign="top">Missions Banner</label>	
				<br />

				<?php for($x=0; $x<count($images);$x++): ?>
						<img src="/secure/snn/assets/img/combat/missionsbanner/<?=$images[$x];?>" style="height:100px;width:800px" />
						<?=form_radio(array('id' => substr($images[$x],0,-4), 'name' => 'missionsimage', 'value' => $images[$x]), true);?>
						<br />
						<br />
				<?php endfor; ?>
				<br />	
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'Mission editieren/ löschen', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;	
</div>