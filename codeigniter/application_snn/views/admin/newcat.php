<div class="col-lg-8 adminpanel">
		<fieldset>
		<legend>Kategorie erstellen</legend>
			<?php if($catError): ?>
				<div class="errormsg"><?=$catError?></div>
				<br />
			<?php endif; ?>
			<?php if($catSuccess): ?>
				<div class="alert alert-success"><?=$catSuccess?></div>
				<br />
			<?php endif; ?>			
			<div id="newstitle_error"></div>
			<?=form_open_multipart('/admin/insertCategory');?>
			<?=form_hidden('sendcat', true);?>
			<div class="col-sm-12">
			
				<br />
				<label class="control-label" for="cat_name" style="width:150px">Kategorie Name</label>
				<br />
				<?=form_input(array('id' => 'cat_name', 'name' =>'cat_name', "class" => "input-xlarge"));?>
				<br />
				<label class="control-label" for="autor" style="width:150px">Kategorie Autor</label>
				<br />
				<?=form_input(array('id' => 'autor', 'name' =>'autor', "class" => "input-xlarge"));?>
			<br /><br />
			<label class="control-label" for="adsimage" style="width:150px">Kategorie Icon</label>
			<br />
			<div class="input-group" style="width:450px">
	                <span class="input-group-btn">
	                    <span class="btn btn-primary btn-file">
	                        Browse&hellip; <input type="file" name="catimage" title="catimage">
	                    </span>
	                </span>
	                <input type="text" class="form-control" readonly>
	            </div>
						</div>
			<div style="clear:both"></div>
			<div class="col-sm-12">
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'Kategorie erstellen', 'name' => 'submit', 'class' => 'btn btn-warning btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<a href="/secure/snn/admin/overview"><- zurück</a>			
</div>