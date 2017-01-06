<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');
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
</script>
<div class="col-lg-8 admininterface">
		<fieldset>
		<legend class="newstitle">Werbung erstellen</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>		
			<div id="newstitle_error"></div>
			<?=form_open_multipart('/admin/newAds');?>
			<?=form_hidden('sendAds', true);?>
			<div class="col-sm-12">
				<label class="control-label" for="title" style="width:150px">Titelzeile</label><br />
				<?=form_input(array('id' => 'title', 'name' =>'title', "class" => "input-xlarge form-control"), '', 'style="width:600px"');?>
				<br />				
				<label class="control-label" for="adstext" style="width:150px">Werbung Text</label>
				<br />
				<input type="hidden" id="adstext" name="adstext" />
				<trix-editor input="adstext" class="trix-content"></trix-editor>

			<br /><br />
			<label class="control-label" for="adsimage" style="width:150px">Werbung Bild</label>
			<br />
			<div class="input-group" style="width:450px">
	                <span class="input-group-btn">
	                    <span class="btn btn-primary btn-file">
	                        Browse&hellip; <input type="file" name="adsimage" title="adsimage">
	                    </span>
	                </span>
	                <input type="text" class="form-control" readonly>
	            </div>
						</div>
			<div style="clear:both"></div>
			<div class="col-sm-12">
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'Werbung erstellen', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;				
</div>