<?php

	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');	
?>
<style>
input, select, textarea {
	color: #000000;
}
.small {
	font-size: 12px;
}
</style>
<script>
    $(document).ready(function () {
        var id = $('#type').val();
        if (id) {
        	changeItemType(id);
        }
        
    });
function changeItemType (id) {
	$('#type_armor').hide('fast');
	$('#type_weapon').hide('fast');
	$('#type_'+id).show('fast');
}
</script>

<div class="col-lg-8 admininterface">
	<fieldset>
		<legend class="newstitle">Gegenstand hinzufügen</legend>
				<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>			
			<div id="gangername_error"></div>
			<?=form_open('/admin/insertItem');?>
			<?=form_hidden('sendItem', true);?>
			<div class="col-sm-6">
				<label class="control-label" for="itemname" style="width:150px">Item Name</label>
				<?=form_input(array('id' => 'itemname', 'name' =>'itemname', "class" => "input-xlarge"), '');?>
				<br />
				<label class="control-label select_width" for="type" style="width:150px">Gegenstandstyp</label>
				<?=form_dropdown('type', array('' => 'Typ wählen', 'weapon' => 'Waffe', 'armor' => 'Rüstung'), '', 'id="type" onchange="changeItemType(this.value)"');?>
				<br />
				<label class="control-label" for="cost" style="width:150px">Kosten</label>
				<?=form_input(array('id' => 'cost', 'name' =>'cost', "class" => "input-xlarge"), '');?>								
				<br />	
				<br />	
				<label class="control-label" for="description" style="width:150px;" valign="top">Beschreibung</label>
				<?=form_textarea(array('id' => 'description', 'name' =>'description', "class" => "input-xlarge"));?>
				<br />								
			</div>
			<div class="col-sm-6">
				<div id="type_weapon" style="display:none">
					<label class="control-label" for="ammo" style="width:150px">Magazingröße</label>
					<?=form_input(array('id' => 'ammo', 'name' =>'ammo', "class" => "input-xlarge"), '');?>
					<br />
					<label class="control-label" for="damage" style="width:150px">Schaden<br /><span class="small">(zB 8M)</span></label>
					<?=form_input(array('id' => 'damage', 'name' =>'damage', "class" => "input-xlarge"), '');?>
					<br />						
					<label class="control-label" for="mode" style="width:150px">Feuermodus</label>
					<?=form_multiselect('mode[]', array('HM' => 'HM', 'SM' => 'SM', 'AM' => 'AM'));?>
					<br />
					<label class="control-label" for="reduce" style="width:150px">Rückstoßdämpfung</label>
					<?=form_input(array('id' => 'reduce', 'name' =>'reduce', "class" => "input-xlarge"), '');?>
					<br />
				</div>
				<div id="type_armor" style="display:none">						
					<label class="control-label" for="armor" style="width:150px">Rüstungswert<br /><span class="small">(nur ballistisch)</span></label>
					<?=form_input(array('id' => 'armor', 'name' =>'armor', "class" => "input-xlarge"), '');?>												
				</div>		
				<div id="type_armor" style="display:none">						
					<label class="control-label" for="armor" style="width:150px">Rüstungswert<br /><span class="small">(nur ballistisch)</span></label>
					<?=form_input(array('id' => 'armor', 'name' =>'armor', "class" => "input-xlarge"), '');?>												
				</div>						
			</div>			
		<div style="clear:both"></div>
			<div class="col-sm-12">
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'Gegenstand erstellen', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;
</div>			