<?php
$op_mode = array();
if (!empty($item[0]['mode'])) {
	$mode = explode(';', $item[0]['mode']);
	foreach($mode as $m) {
		$op_mode[] = $m;
	}
}

	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');	

?>
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
<style>
input, select {
	color: #000000;
}
</style>
<div class="col-lg-8 admininterface">
	<fieldset>
		<legend class="newstitle">Gegenstand bearbeiten</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger"><?=$error?></div>
				<br />
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success"><?=$success?></div>
				<br />
			<?php endif; ?>	
			<div id="gangername_error"></div>
			<?=form_open('/admin/editItem');?>
			<?=form_hidden('sendItem', true);?>
			<?=form_hidden('wid', $item[0]['wid']);?>
			<div class="col-sm-6">
				<label class="control-label" for="itemname" style="width:150px">Item Name</label>
				<?=form_input(array('id' => 'itemname', 'name' =>'itemname', "class" => "input-xlarge"), $item[0]['name']);?>
				<br />
				<label class="control-label select_width" for="type" style="width:150px">Gegenstandstyp</label>
				<?=form_dropdown('type', array('' => 'Typ wählen', 'weapon' => 'Waffe', 'armor' => 'Rüstung'), $item[0]['type'], 'id="type" onchange="changeItemType(this.value)"');?>
				<br />
				<label class="control-label" for="cost" style="width:150px">Kosten</label>
				<?=form_input(array('id' => 'cost', 'name' =>'cost', "class" => "input-xlarge"), $item[0]['cost']);?>								
				<br />	
				<br />	
				<label class="control-label" for="description" style="width:150px;" valign="top">Beschreibung</label>				
				<?=form_textarea(array('id' => 'description', 'name' =>'description', "class" => "input-xlarge", "value" => $item[0]['description']));?>
				<br />								
			</div>
			<div class="col-sm-6">
				<div id="type_weapon" style="display:none">
					<label class="control-label" for="ammo" style="width:150px">Magazingröße</label>
					<?=form_input(array('id' => 'ammo', 'name' =>'ammo', "class" => "input-xlarge"), $item[0]['ammo']);?>
					<br />
					<label class="control-label" for="damage" style="width:150px">Schaden</label>
					<?=form_input(array('id' => 'damage', 'name' =>'damage', "class" => "input-xlarge"), $item[0]['damage']);?>
					<br />						
					<label class="control-label" for="mode" style="width:150px">Feuermodus</label>
					<?php $options = array('HM' => 'HM', 'SM' => 'SM', 'AM' => 'AM'); ?>
					<?=form_multiselect('mode[]', $options, $op_mode);?>
					<br />
					<label class="control-label" for="reduce" style="width:150px">Rückstoßdämpfung</label>
					<?=form_input(array('id' => 'reduce', 'name' =>'reduce', "class" => "input-xlarge"), $item[0]['reduce']);?>
					<br /><br />
				</div>
				<div id="type_armor" style="display:none">				
					<label class="control-label" for="armor" style="width:150px">Rüstungswert</label>
					<?=form_input(array('id' => 'armor', 'name' =>'armor', "class" => "input-xlarge"), $item[0]['armor']);?>																		
				</div>
			</div>			
		<div style="clear:both"></div>
			<div class="col-sm-12">
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'Gegenstand bearbeiten', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<br />
	<a href="/secure/snn/admin/itemsVerwalten"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;
</div>			