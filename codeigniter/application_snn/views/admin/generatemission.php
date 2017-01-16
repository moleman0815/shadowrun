<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');

$n_options = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'); 
$r_options = array('mensch' => 'Mensch', 'elf' => 'Elf', 'zwerg' => 'Zwerg', 'ork' => 'Ork', 'troll' => 'Troll');
$options = '';
#_debugDie($storyitems);
foreach($allganger as $g) {
	$options .= '<option value="'.$g['gid'].'">Lv. '.$g['level'].' - '.$g['ganger_name'].'</option>';
}
?>
<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?>    	
    	toggleSpecial();
	});

	function toggleMissionBoxes(id) {
		var status = $('#'+id).css('display');
		if (status == 'none') {
			$('#'+id).show();
		} else {
			$('#'+id).hide();
		}
	}

	function toggleSpecial() {
		var value = $('#special :selected').val();
		if (value == 1) {
			$('#storyitemBox').show();
		} else {
			$('#storyitemBox').hide();
		}
	}
 </script>
<div class="col-lg-8 admininterface">
	<fieldset>
		<legend class="newstitle">Generate Mission</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>					

			<div id="missiontitle_error"></div>
			<?=form_open('/admin/generateMission');?>
			<?=form_hidden('sendMission', true);?>
			<?=form_hidden('missionmember', '1')?>
			<div class="col-sm-6">
				<label class="control-label" for="missionstitle" style="width:150px">Missions Titel</label>
				<?php $js = 'onblur="checkMissionTitle(this.value)"'; ?>
				<?=form_input(array('id' => 'missionstitle', 'name' =>'missionstitle', "class" => "input-xlarge"), '', $js);?>
				<br />				
				<label class="control-label select_width" for="missionlevel" style="width:150px">Missions Level</label>
				<?=form_dropdown('missionlevel', $n_options);?>
				<br />
				<label class="control-label select_width" for="missiontype" style="width:150px">Missions Type</label>
				<?=form_dropdown('missiontype', array('combat' => 'Kampf'));?>
				<br />
				<label class="control-label select_width" for="missioncash" style="width:150px">Missions Einkommen</label>
				<?=form_input(array('id' => 'missioncash', 'name' =>'missioncash', "class" => "input-xlarge"));?>
				<br />
				<label class="control-label select_width" for="missionexpense" style="width:150px">Missions Ausgaben</label>
				<?=form_input(array('id' => 'missionexpense', 'name' =>'missionexpense', "class" => "input-xlarge"));?>
				<br />
				<label class="control-label select_width" for="missionextras" style="width:150px">Missions NSCs</label>
				<?=form_dropdown('missionextras', array('0' => '0','1' => '1','2' => '2','3' => '3','4' => '4'));?>
				<br />
				<label class="control-label select_width" for="missionextras" style="width:150px">Story Mission</label>
				<select name="special" id="special" onchange="toggleSpecial()">
					<option value="0">Nein</option>
					<option value="1">Ja</option>
				</select>
				<div id="storyitemBox" style="display:none">
					<label class="control-label select_width" for="missionextras" style="width:150px">Story Item<br /> (*Storymission only)</label>
					<select name="storyitem" id="storyitem" onchange="toggleSpecial">
						<option value="">Item ausw&auml;hlen</option>
						<?php foreach($storyitems as $s): ?>
							<option value="<?=$s['iid']?>"><?=$s['itemname']?></option>
						<?php endforeach;?>
					</select>
				</div>
				<br />
				<label class="control-label select_width" for="missionmember" style="width:150px">Story Image</label>				
				<table>
					<tr>
				<?php for($x=0; $x<count($story);$x++): ?>
					<?php if ($x%2==0) { echo "</tr><tr>"; }?>
					<td>
						<img src="/secure/snn/assets/img/combat/storyimage/<?=$story[$x];?>" style="height:100px;width:100px" />
						<?=form_radio(array('id' => substr($story[$x],0,-4), 'name' => 'storyimage', 'value' => $story[$x]), true);?>
						<br />
					</td>
				<?php endfor; ?>
					</tr>
				</table>						
			</div>
			<div class="col-sm-6">
				<label class="control-label" for="missionstext" style="width:150px;" valign="top">Missions Gegner</label>				
				<br />	
				<select name="missionganger[]" id="missionganger" multiple size="5">
					<?=$options;?>
				</select>
				<br />
				<label class="control-label select_width" for="missionmember" style="width:150px">Mr Johnson</label>				
				<table>
					<tr>
				<?php for($x=0; $x<count($johnson);$x++): ?>
					<?php if ($x%4==0) { echo "</tr><tr>"; }?>
					<td>
						<img src="/secure/snn/assets/img/combat/johnson/<?=$johnson[$x];?>" style="height:70px;width:70px" />
						<?=form_radio(array('id' => substr($johnson[$x],0,-4), 'name' => 'johnson', 'value' => $johnson[$x]), true);?>
						<br />
					</td>
				<?php endfor; ?>
					</tr>
				</table>
			</div>
			<div style="clear:both"></div>
			<div class="col-sm-6">
			<br />
				<label class="control-label" for="missionstext" style="width:150px;" valign="top">
					Missions Teasertext
					<span style="float:right;margin-left:20px;cursor:pointer" id="melee_span" onclick="toggleMissionBoxes('missionstext')"><img src="/secure/snn/assets/img/icons/add.png" /></span>
				</label>
				<br />
				<?=form_textarea(array('id' => 'missionstext', 'name' =>'missionstext', "class" => "input-xlarge", "style" => "width:400px;height:150px;display:none"));?>
				<br />
				<label class="control-label" for="missionsstorytext" style="width:150px;" valign="top">
					Missions Storytext
					<span style="float:right;margin-left:20px;cursor:pointer" id="melee_span" onclick="toggleMissionBoxes('missionsstorytext')"><img src="/secure/snn/assets/img/icons/add.png" /></span>	
				</label>
				<br />
				<?=form_textarea(array('id' => 'missionsstorytext', 'name' =>'missionsstorytext', "class" => "input-xlarge", "style" => "width:400px;height:150px;display:none"));?>
				<br />	
				<label class="control-label" for="missionswintext" style="width:200px;" valign="top">
					Missions Gewonnen Text
					<span style="float:right;margin-left:20px;cursor:pointer" id="melee_span" onclick="toggleMissionBoxes('missionswintext')"><img src="/secure/snn/assets/img/icons/add.png" /></span>
				</label>
				<br />
				<?=form_textarea(array('id' => 'missionswintext', 'name' =>'missionswintext', "class" => "input-xlarge", "style" => "width:400px;height:150px;display:none"));?>
				<br />
				<label class="control-label" for="missionslosstext" style="width:200px;" valign="top">
					Missions Verloren Text
					<span style="float:right;margin-left:20px;cursor:pointer" id="melee_span" onclick="toggleMissionBoxes('missionslosstext')"><img src="/secure/snn/assets/img/icons/add.png" /></span>
				</label>
				<br />
				<?=form_textarea(array('id' => 'missionslosstext', 'name' =>'missionslosstext', "class" => "input-xlarge", "style" => "width:400px;height:150px;display:none"));?>
				<br />															

			</div>

			<div class="col-sm-6">
				<label class="control-label" for="missionsimage" style="width:150px;" valign="top">Missions Banner</label>	
				<br />

				<?php for($x=0; $x<count($images);$x++): ?>
					<img src="/secure/snn/assets/img/combat/missionsbanner/<?=$images[$x];?>" style="height:50px;width:200px" />
					<?=form_radio(array('id' => substr($images[$x],0,-4), 'name' => 'missionsimage', 'value' => $images[$x]), true);?>
					<br /></br />
				<?php endfor; ?>
				<br />	
				<br />	
			</div>
			<div style="clear:both"></div>
			<div class="col-sm-12">
				<?=form_submit(array('id'=>'submit', 'value' => 'Mission erstellen', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;	
</div>