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
<style>
	input, textarea, select {
		color: black;
	}
</style>
<div class="col-lg-2"></div>
<div class="col-lg-8 newselement">
	<div class="newstitle">Features</div>
	<br />
	<?php if($error): ?>
		<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
	<?php endif; ?>
	<?php if($success): ?>
		<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
	<?php endif; ?>
	<div class="col-lg-12" style="border: 1px solid white;padding:5px">		
	<?php if(!empty($features)): ?>
		<ul>
		<?php foreach($features as $f): ?>	
			<li><b><?=date('d.m.Y H:i', $f['time'])?></b><br />
				<?=nl2br($f['feedback']);?>
			</li>
			<br />
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
<div style="clear:both"></div>	
	<br /><hr />
	<?php if($this->session->userdata('rank') == '1'): ?>
		<div style="margin: 0 auto;padding:15px;">
		<fieldset style="border:wpx solid white">
			<legend style="color:white">Feature schreiben</legend>
		<?=form_open_multipart('/desktop/features');?>
		<?=form_hidden('sendFeature', true);?>
			<label class="control-label" for="feature" style="width:150px">Feature</label>
			<br />
			<input type="hidden" id="feature" name="feature" />
			<trix-editor input="feature" class="trix-content" style="color:black"></trix-editor>
			
			<br />	<br />	
			<?=form_submit(array('id'=>'submit', 'value' => 'Feature absenden', 'name' => 'submit', 'class' => 'btn btn-warning btn-sm'));?>		
		<?=form_close();?>
		</fieldset>
		</div>
	<?php endif; ?>
</div>
<div class="col-lg-2"></div>
<div style="clear:both"></div>
<br />&nbsp;
