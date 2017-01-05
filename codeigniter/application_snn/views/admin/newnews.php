<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');

	$c_opt = '<option value="">Kategorie auswählen</option>';
	foreach($categories as $c) {
		$c_opt .= '<option value="'.$c['id'].'">'.$c['cat_name'].'</option>';
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
	});
	$(function() {
        $('.wymeditor').wymeditor();
    })
</script>
<div class="col-lg-8">
		<fieldset style="background-color: white">
		<legend class="newstitle">News schreiben</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>			
			<div id="newstitle_error"></div>
			<?=form_open('/admin/newNews');?>
			<?=form_hidden('sendNews', true);?>
			<div class="col-sm-6">
				<label class="control-label" for="title" style="width:150px">News Titel</label>				
				<?=form_input(array('id' => 'title', 'name' =>'title', "class" => "input-xlarge"));?>
				<br />
				<label class="control-label" for="category" style="width:150px">News Kategorie</label>	
				<select name="category" id="category">
					<?=$c_opt;?>
				</select>
				<br />							
			</div>
			<div class="col-sm-12">
			
				<br />
				<label class="control-label" for="newstext" style="width:150px">News Text</label>
				<br />
				<input type="hidden" id="newstext" name="newstext" />
				<trix-editor input="newstext" class="trix-content"></trix-editor>
				
			</div>
			<div style="clear:both"></div>
			<div class="col-sm-12">
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'News erstellen', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;
</div>