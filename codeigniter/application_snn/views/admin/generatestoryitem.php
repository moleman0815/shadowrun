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

<div class="col-lg-8 admininterface">
	<fieldset>
		<legend class="newstitle">Story Gegenstand hinzufügen</legend>
				<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>			
			<div id="gangername_error"></div>
			<?=form_open_multipart('/admin/generateStoryitem');?>
			<?=form_hidden('sendStoryitem', true);?>
			<div class="col-sm-6">
				<label class="control-label" for="itemname" style="width:150px">Item Name</label>
				<?=form_input(array('id' => 'itemname', 'name' =>'itemname', "class" => "input-xlarge", "style" => "width: 400px"), '');?>
				<br />	
				<label class="control-label" for="description" style="width:150px;" valign="top">Beschreibung</label>
				<?=form_textarea(array('id' => 'itemtext', 'name' =>'itemtext', "class" => "input-xlarge", "style" => "width:400px;height:200px"));?>
				<br />								
			</div>
			<div class="col-sm-6">
				<label class="control-label" for="gangerportrait" style="width:250px">Bild Upload</label><br />
	            <div class="input-group">
	                <span class="input-group-btn">
	                    <span class="btn btn-primary btn-file">
	                        Browse&hellip; <input type="file" name="image" title="image">
	                    </span>
	                </span>
	                <input type="text" class="form-control" readonly>
	            </div>				

			</div>
			<div style="clear:both"></div>
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