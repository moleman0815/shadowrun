<?php
	$rank = array('5' => 'User', '1' => 'Superadmin', '2' => 'Admin', '3' => 'NPC');
	$c_opt = '';
	foreach($rank as $key => $value) {
		$c_opt .= '<option value="'.$key.'">'.$value.'</option>';
	}
?>
<div class="col-lg-8 adminpanel">
	<br />
		<fieldset>
		<legend>User erstellen</legend>
			<?php if($newsError): ?>
				<div class="alert alert-error"><?=$newsError?></div>
				<br />
			<?php endif; ?>
			<?php if($newsSuccess): ?>
				<div class="alert alert-success"><?=$newsSuccess?></div>
				<br />
			<?php endif; ?>			
			<div id="newstitle_error"></div>
			<?=form_open('/admin/newUser');?>
			<?=form_hidden('sendUser', true);?>
			<div class="col-sm-6">
				<label class="control-label" for="username" style="width:150px">Username</label>				
				<?=form_input(array('id' => 'username', 'name' =>'username', "class" => "input-xlarge"));?>
				<br />
				<label class="control-label" for="password" style="width:150px">Passwort</label>				
				<?=form_input(array('id' => 'password', 'name' =>'password', "class" => "input-xlarge"));?>
				<br />	
				<label class="control-label" for="rank" style="width:150px">Rang</label>	
				<select name="rank" id="rank">
					<?=$c_opt;?>
				</select>			
				<br />				
				<label class="control-label" for="type" style="width:150px">Betatester</label>	
				<select name="style" id="style">
					<option value="0">kein Betatester</option>
					<option value="2">Betatester</option>
				</select>						
			</div>
			<div style="clear:both"></div>
			<div class="col-sm-12">
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'User erstellen', 'name' => 'submit', 'class' => 'btn btn-warning btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<a href="/secure/snn/admin/overview"><- zurück</a>			
</div>