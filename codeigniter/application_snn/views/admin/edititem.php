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
	$('#type_cyberware').hide('fast');
	$('#type_melee').hide('fast');
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
				<?=form_dropdown('type', array('' => 'Typ wählen', 'weapon' => 'Waffe', 'armor' => 'Rüstung', 'cyberware' => 'Cyberware', 'melee' => 'Nahkampf'), $item[0]['type'], 'id="type" onchange="changeItemType(this.value)"');?>
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
				<div id="type_melee" style="display:none;color: white">
					<label class="control-label" for="ammo" style="width:150px">Schaden (zB 0L, 3M, ..)</label>
					&nbsp;&nbsp;&nbsp;<?=form_input(array('id' => 'melee_damage', 'name' =>'melee_damage', "class" => "input-xlarge"), $item[0]['damage']);?><br />
					<label class="control-label" for="ammo" style="width:150px">Reichweite</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'reach', 'name' =>'reach', "class" => "input-xlarge"), $item[0]['reach']);?><br />
				</div>
			</div>
			<div class="col-sm-6">
				<div id="type_cyberware" style="display:none;color: white">
					<label class="control-label" for="ammo" style="width:150px">Type</label>
					&nbsp;&nbsp;&nbsp;<?=form_dropdown('cyberware_type', array('bodyware' => 'Bodyware', 'headware' => 'Headware'), $item[0]['cyberware_type']);?><br />
					<label class="control-label" for="ammo" style="width:150px">Essenzkosten</label>
					<b>&minus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_essence', 'name' =>'cyberware_essence', "class" => "input-xlarge"), $item[0]['cyberware_essence']);?><br />					
					<label class="control-label" for="ammo" style="width:150px">Iniw&uuml;rfel</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_ini', 'name' =>'cyberware_ini', "class" => "input-xlarge"), $item[0]['cyberware_ini']);?><br />
					<label class="control-label" for="ammo" style="width:150px">Reaktion</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_reaction', 'cyberware_reaction' =>'ammo', "class" => "input-xlarge"), $item[0]['cyberware_reaction']);?><br />
					<label class="control-label" for="ammo" style="width:150px">R&uuml;stung</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_armor', 'name' =>'cyberware_armor', "class" => "input-xlarge"), $item[0]['cyberware_armor']);?><br />
					<label class="control-label" for="ammo" style="width:150px">Mindestwurf</label>
					<b>&minus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_mw', 'name' =>'cyberware_mw', "class" => "input-xlarge"), $item[0]['cyberware_mw']);?><br />
					<label class="control-label" for="ammo" style="width:150px">Konstitution</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_body', 'name' =>'cyberware_body', "class" => "input-xlarge"), $item[0]['cyberware_body']);?><br />
					<label class="control-label" for="ammo" style="width:150px">Schnelligkeit</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_quickness', 'name' =>'cyberware_quickness', "class" => "input-xlarge"), $item[0]['cyberware_quickness']);?><br />
					<label class="control-label" for="ammo" style="width:150px">St&auml;rke</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_strength', 'name' =>'cyberware_strength', "class" => "input-xlarge"), $item[0]['cyberware_strength']);?><br />										
					<label class="control-label" for="ammo" style="width:150px">Intelligenz</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_intelligence', 'name' =>'cyberware_intelligence', "class" => "input-xlarge"), $item[0]['cyberware_intelligence']);?><br />
				</div>
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