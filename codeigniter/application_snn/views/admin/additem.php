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
	$('#type_cyberware').hide('fast');
	$('#type_melee').hide('fast');
	$('#type_spell').hide('fast');
	$('#type_'+id).show('fast');
}
</script>

<div class="col-lg-8 admininterface">
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>		<fieldset>
		<legend class="newstitle">Gegenstand hinzufügen</legend>
				<br />
		
			<div id="gangername_error"></div>
			<?=form_open('/admin/insertItem');?>
			<?=form_hidden('sendItem', true);?>
			<div class="col-sm-6">
				<label class="control-label" for="itemname" style="width:150px">Item Name</label>
				<?=form_input(array('id' => 'itemname', 'name' =>'itemname', "class" => "input-xlarge"), '');?>
				<br />
				<label class="control-label select_width" for="type" style="width:150px">Gegenstandstyp</label>
				<?=form_dropdown('type', array('' => 'Typ wählen', 'weapon' => 'Waffe', 'armor' => 'Rüstung', 'cyberware' => 'Cyberware', 'melee' => 'Nahkampf', 'spell' => 'Zauber'), '', 'id="type" onchange="changeItemType(this.value)"');?>
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
				<div id="type_spell" style="display:none;color: white">
					<label class="control-label" for="ammo" style="width:150px">Typ</label>
					<?=form_dropdown('typ', array('kampf' => 'Kampf', 'heilung' => 'Heilung'));?><br />
					<label class="control-label" for="ammo" style="width:150px">Subtyp</label>
					<?=form_dropdown('subtype', array('p' => 'Physisch', 'm' => 'Mental'));?><br />
					<label class="control-label" for="ammo" style="width:150px">Mindestwurf</label>
					<?=form_input(array('id' => 'mw', 'name' =>'mw', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">Entzug</label>
					<?=form_input(array('id' => 'entzug', 'name' =>'entzug', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">Wirkung</label>
					<?=form_input(array('id' => 'wirkung', 'name' =>'wirkung', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">Ziel</label>
					<?=form_dropdown('target', array('enemy' => 'Gegner', 'multi' => 'mehrere Gegner', 'self' => 'Spieler'));?><br />
				</div>
			</div>
			<div class="col-sm-6">
				<div id="type_melee" style="display:none;color: white">
					<label class="control-label" for="ammo" style="width:150px">Schaden (zB 0L, 3M, ..)</label>
					&nbsp;&nbsp;&nbsp;<?=form_input(array('id' => 'melee_damage', 'name' =>'melee_damage', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">Reichweite</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'reach', 'name' =>'reach', "class" => "input-xlarge"), '');?><br />
				</div>
			</div>
			<div class="col-sm-6">
				<div id="type_cyberware" style="display:none;color: white">
					<label class="control-label" for="ammo" style="width:150px">Type</label>
					&nbsp;&nbsp;&nbsp;<?=form_dropdown('cyberware_type', array('bodyware' => 'Bodyware', 'headware' => 'Headware'));?><br />
					<label class="control-label" for="ammo" style="width:150px">Essenzkosten</label>
					<b>&minus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_essence', 'name' =>'cyberware_essence', "class" => "input-xlarge"), '');?><br />					
					<label class="control-label" for="ammo" style="width:150px">Iniw&uuml;rfel</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_ini', 'name' =>'cyberware_ini', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">Reaktion</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_reaction', 'cyberware_reaction' =>'ammo', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">R&uuml;stung</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_armor', 'name' =>'cyberware_armor', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">Mindestwurf</label>
					<b>&minus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_mw', 'name' =>'cyberware_mw', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">Konstitution</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_body', 'name' =>'cyberware_body', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">Schnelligkeit</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_quickness', 'name' =>'cyberware_quickness', "class" => "input-xlarge"), '');?><br />
					<label class="control-label" for="ammo" style="width:150px">St&auml;rke</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_strength', 'name' =>'cyberware_strength', "class" => "input-xlarge"), '');?><br />										
					<label class="control-label" for="ammo" style="width:150px">Intelligenz</label>
					<b>&plus;</b>&nbsp;<?=form_input(array('id' => 'cyberware_intelligence', 'name' =>'cyberware_intelligence', "class" => "input-xlarge"), '');?><br />
				</div>
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