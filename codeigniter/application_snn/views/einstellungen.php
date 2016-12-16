<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');	

	$n_options = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'); 
	$m_options = array('0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'); 
	$r_options = array('mensch' => 'Mensch', 'elf' => 'Elf', 'zwerg' => 'Zwerg', 'ork' => 'Ork', 'troll' => 'Troll');
	$c_options = array('' => '', '' => '', '' => '', '' => '', '' => '');

	$nofriends = array('16', '15', $this->session->userdata('id'));
	foreach ($friends as $f) {
		array_push($nofriends, $f['id']);		
	}
?>
<style>
	input, select {
		color: black;
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$( "#tabs" ).tabs();
	var last_index = $.cookie("lasttab");
    $("#tabs").tabs( "option", "active", last_index );
   <?php if($error): ?>
    	$("#error").fadeOut(7000);    
   <?php endif; ?>
   <?php if($success): ?>
    	$("#success").fadeOut(7000);    
   <?php endif; ?>   
});

$(function() {
	$("li").click(function() {
	    var current_index = $("#tabs").tabs("option","active");
	    $.cookie("lasttab", current_index);
	});
});
		
</script>
<style>

.ui-widget-content {
	background-image: none;
	background-color: black;
	
	color: #FFFFFF;
}
.ui-widget-header {
	background-color: black;
	background-image: none;
	color: #FFFFFF;	
}

input {
	background-color: #FFFFFF;
}
fieldset {
	border: 1px solid #ffffff;
}
legend {
	color: #FFFFFF;
}
.flashmsg {
	width: 600px;
	left: 50%;
	top: 135px;
	height: 70px;
	position: absolute;
	z-index: 100;
	margin-left: -300px;
	display: none;
	font-weight: bold;
	text-align:center;
}
</style>

	<div class="advbroad" style="width: 100%">
		Hier kannst du einige Einstellungen für die Plattform konfigurieren.
	</div>
	<br />
		<?php if($error): ?>
			<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left: 25%;border: 3px solid black" id="error"><b><?=$error?></b></div>
			<br />
		<?php endif; ?>
		<?php if($success): ?>
			<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left: 25%;border: 3px solid black" id="success"><?=$success?></div>
			<br />
		<?php endif; ?>	
<div id="tabs">
	<ul>
		<li><a href="#nickname">Nickname</a></li>
	  	<li><a href="#passwort">Passwort</a></li>
		<li><a href="#avatar">Avatar</a></li>
		<li><a href="#charakter">Charakter</a></li>
		<li><a href="#freunde">Freunde</a></li>
	</ul>
	<div id="freunde">
		<fieldset>
			<legend>Freunde</legend>
			<div id="friendmsg"></div>	
			<div class="col-sm-6">			
			Meine Freunde:<br />
				<?php if(!empty($friends)): ?>
				<br />

					<?php foreach($friends as $f): ?>
						<div style="width:200px;cursor:pointer">
							<?=ucfirst($f['nickname'])?>&nbsp;
							<?php if($f['accepted'] == '1'):?>
								<span style="margin-left:20px;float:right"><img src="/secure/snn/assets/img/icons/no.png" alt="Freund entfernen" title="Freund entfernen" onclick="removeFriend('<?=$f['id'];?>')" /></span><br />
							<?php else: ?>
								<span style="margin-left:20px;float:right"><img src="/secure/snn/assets/img/icons/turn_right.png" alt="Anfrage läuft" title="Anfrage läuft" /></span><br />
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
				
			</div>
			<div class="col-sm-6">
			Verfügbare User:<br /><br />

			<?php foreach($all_users as $f): ?>
				<?php if(in_array($f['id'], $nofriends)) {continue;} ?>
				<div style="width:200px;cursor:pointer" ><?=ucfirst($f['nickname'])?>
					<span style="margin-left:20px;float:right"><img src="/secure/snn/assets/img/icons/yes.png" alt="<?=ucfirst($f['nickname'])?> als Freund hinzufügen" title="<?=ucfirst($f['nickname'])?> als Freund hinzufügen" onclick="addFriend('<?=$f['id'];?>')" /></span><br />
				</div>
			<?php endforeach; ?>				
			</div>
		</fieldset>
	
	</div>
	<div id="charakter" data-type="box">
		<fieldset>
			<legend>Charakter</legend>
			<p>Hier kannst du dir deinen Charakter anlegen oder editieren.</p>
			<?php if($charError): ?>
				<div class="errormsg"><?=$charError?></div>
				<br />
			<?php endif; ?>
			<?=form_open('/desktop/einstellungen');?>
			<?=form_hidden('sendChar', true);?>
			<div class="col-sm-6">
				<label class="control-label select_width" for="charname">Charname</label>
				<?php $so = (!empty($char)) ? $char[0]['charname'] : ''; ?>
				<?=form_input(array('id' => 'charname', 'name' =>'charname', "class" => "input-xlarge", "value" => $so))?>
				<br />
				<label class="control-label select_width" for="race">Rasse</label>
				<?php $so = (!empty($char)) ? array($char[0]['race']) : '';?>
				<?=form_dropdown('race', $r_options, $so);?>
				<br />
				<label class="control-label select_width" for="body">Konstitution</label>
				<?php $so = (!empty($char)) ? array($char[0]['body']) : '';?>
				<?=form_dropdown('body', $n_options, $so);?>
				<br />		
				<label class="control-label select_width" for="quickness">Schnelligkeit</label>
				<?php $so = (!empty($char)) ? array($char[0]['quickness']) : '';?>
				<?=form_dropdown('quickness', $n_options, $so);?>
				<br />
				<label class="control-label select_width" for="strength">Stärke</label>
				<?php $so = (!empty($char)) ? array($char[0]['strength']) : '';?>
				<?=form_dropdown('strength', $n_options, $so);?>
				<br />
				<label class="control-label select_width" for="charisma">Charisma</label>
				<?php $so = (!empty($char)) ? array($char[0]['charisma']) : '';?>
				<?=form_dropdown('charisma', $n_options, $so);?>
				<br />
				<label class="control-label select_width" for="intelligence">Intelligenz</label>
				<?php $so = (!empty($char)) ? array($char[0]['intelligence']) : '';?>
				<?=form_dropdown('intelligence', $n_options, $so);?>
				<br />
				<label class="control-label select_width" for="willpower">Willenskraft</label>
				<?php $so = (!empty($char)) ? array($char[0]['willpower']) : '';?>
				<?=form_dropdown('willpower', $n_options, $so);?>
				<br />
				<label class="control-label select_width" for="essence">Essenz</label>
				<?=form_input('essence', "6", "readonly='readonly' style='width:45px'");?>
				<br />
				<label class="control-label select_width" for="magic">Magie</label>
				<?php $so = (!empty($char)) ? array($char[0]['magic']) : '';?>
				<?=form_dropdown('magic', $m_options, $so);?>
				<br />						
			</div>
			<div class="col-sm-6">
				<br />
				<label class="control-label select_width" for="armed_longrange">Fernkampf</label>
				<?php $so = (!empty($char)) ? array($char[0]['armed_longrange']) : '';?>
				<?=form_dropdown('armed_longrange', $n_options, $so);?>
				<br />
				<label class="control-label select_width" for="armed_combat">Nahkampf</label>
				<?php $so = (!empty($char)) ? array($char[0]['armed_combat']) : '';?>
				<?=form_dropdown('armed_combat', $n_options, $so);?>
				<br />				
<!--								
				<label class="control-label select_width" for="reaction_mod">Reaktions Modifikator</label>
				<?php $so = (!empty($char)) ? array($char[0]['reaction_mod']) : '';?>
				<?=form_dropdown('reaction_mod', $m_options, $so);?>
				<br />				
				<label class="control-label select_width" for="inidice">Initiative Würfel</label>
				<?php $so = (!empty($char)) ? array($char[0]['inidice']) : '';?>
				<?=form_dropdown('inidice', $n_options, $so);?>
				<br />						
-->				
			</div>
			<div style="clear:both"></div>
			<br /><br />

			<?=form_submit(array('id'=>'submit', 'value' => 'Charakter erstellen/ editieren', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			<?=form_close();?>
		</fieldset>
		<br />		
	</div>	

	<div id="nickname" data-type="box">
		<fieldset style="border: 1px solid #FFFFFF">
			<legend style="color: #FFFFFF">Nickname</legend>
			<p>Hier kannst du deinen Anzeigenamen ändern, dein Loginname bleibt davon unberührt.</p>
			<?php if($nickError): ?>
				<div class="errormsg"><?=$nickError?></div>
				<br />
			<?php endif; ?>
			<?=form_open('/desktop/einstellungen');?>
			<?=form_hidden('sendNick', true);?>
			<label class="control-label" for="nickname">Nickname</label>
			<br />
			<?=form_input(array('id' => 'nickname2', 'name' =>'nickname', "class" => "input-xlarge", "style" => "background-color: #FFFFFF;color: #000000", "value" => ucfirst($this->session->userdata('nickname'))));?>
			<br /><br />
			<?=form_submit(array('id'=>'submit', 'value' => 'Nickname ändern', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			<?=form_close();?>
		</fieldset>
		<br />		
	</div>
	<div id="passwort" data-type="box">
		<fieldset>
			<legend>Passwort</legend>
			<p>Hier kannst du dein Passwort ändern.</p>
			<?php if($passError): ?>
				<div class="errormsg"><?=$passError?></div>
				<br />
			<?php endif; ?>
			<?=form_open('/desktop/einstellungen');?>
			<?=form_hidden('sendPass', true);?>
			<label class="control-label" for="oldpassword">Altes Passwort</label>
			<br />
			<?=form_input(array('id' => 'oldpassword', 'name' =>'oldpassword', "class" => "input-xlarge"));?>
			<br />
			<label class="control-label" for="newpassword">Neues Passwort</label>
			<br />
			<?=form_input(array('id' => 'newpassword', 'name' =>'newpassword', "class" => "input-xlarge"));?>
			<br />
			<br />
			<?=form_submit(array('id'=>'submit', 'value' => 'Passwort ändern', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			<?=form_close();?>	
		</fieldset>
		<br />			
	</div>
	<div id="avatar" data-type="box">
		<fieldset>
			<legend>Avatar</legend>
			<p>Hier kannst du dir einen Avatar hochladen, der zB bei den Nachrichten oder deinem Char erscheint.</p>
			<?php if($avatarError): ?>
				<div class="errormsg"><?=$avatarError?></div>
				<br />
			<?php endif; ?>
			<?php if($avatarSuccess): ?>
				<div class="alert alert-success"><?=$avatarSuccess?></div>
				<br />
			<?php endif; ?>	
			<?=form_open_multipart('/desktop/einstellungen');?>
			<?=form_hidden('sendAvatar', true);?>
			<br />
			<label class="control-label" for="avatar">Avatar</label>
			<br />

	        <div class="col-lg-6 col-sm-6 col-12">	        	
			<?php if(!empty($avatar[0]['avatar'])): ?>
				<div>
					<img src="/secure/snn/assets/img/avatar/<?=$avatar[0]['avatar'];?>" alt="" />
					<img src="/secure/snn/assets/img/icons/delete.png" alt="delete" title="delete" style="cursor:pointer" onclick="deleteAvatar('<?=$this->session->userdata('id')?>')" />
					<br /><br />
				</div>
			<?php else: ?>
				<div>
					Bisher wurde noch kein Avatar hochgeladen.
				</div>
			<?php endif; ?>	        	
	            <div class="input-group">
	                <span class="input-group-btn">
	                    <span class="btn btn-primary btn-file">
	                        Browse&hellip; <input type="file" name="avatar" title="avatar">
	                    </span>
	                </span>
	                <input type="text" class="form-control" readonly>
	            </div>
	        </div>
			<div style="clear:both"></div>
			<br />
			<?=form_submit(array('id'=>'submit', 'value' => 'Avatar hochladen', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			<?=form_close();?>		
		</fieldset>
		<br />
	</div>	
</div>	
