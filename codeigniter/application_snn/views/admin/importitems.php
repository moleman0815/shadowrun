<?php

	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');	
?>
<fieldset class="admininterface">
	<legend class="newstitle">Gegenstand hinzufügen</legend>
	<br />
				<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>	
	<form action="/secure/snn/admin/itemsImport" enctype="multipart/form-data" method="post">
	<input type="hidden" name="sendfile" value="true" />
	<div class="col-lg-6 col-sm-6 col-12">
			<input type="file" id="itemfile" name="itemfile" readonly>
	</div>
		<div class="col-sm-12">
			<br />	
			<input type="submit" value="Gegenst&auml;nde importieren" class="btn btn-primary btn-sm" />
		</div>

	
	</form>
	<br />&nbsp;
</fieldset>


