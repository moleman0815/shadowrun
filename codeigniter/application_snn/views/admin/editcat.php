<div class="col-lg-8 adminpanel">
	<br />
		<fieldset>
		<legend>Kategorie bearbeiten</legend>
			<?php if($catError): ?>
				<div class="errormsg"><?=$catError?></div>
				<br />
			<?php endif; ?>
			<?php if($catSuccess): ?>
				<div class="alert alert-success"><?=$catSuccess?></div>
				<br />
			<?php endif; ?>		
			<?=print_r($ads)?>	
			<div id="newstitle_error"></div>
			<?=form_open_multipart('/admin/editCategory');?>
			<?=form_hidden('editcat', true);?>
			<input type="hidden" name="id" value="<?=$ads[0]['id'];?>" />
			<input type="hidden" name="old_icon" value="<?=$ads[0]['icon'];?>" />
			<div class="col-sm-12">
			
				<br />
				<label class="control-label" for="cat_name" style="width:150px">Kategorie Name</label>
				<br />
				<?=form_input(array('id' => 'cat_name', 'name' =>'cat_name', "class" => "input-xlarge", "value" => $ads[0]['cat_name']));?>
				<br />
				<label class="control-label" for="autor" style="width:150px">Kategorie Autor</label>
				<br />
				<?=form_input(array('id' => 'autor', 'name' =>'autor', "class" => "input-xlarge", "value" => $ads[0]['autor']));?>
			<br /><br />
			<label class="control-label" for="adsimage" style="width:150px">Kategorie Icon</label>
			<br />
			<img src="/secure/snn/assets/img/news/icons/<?=$ads[0]['icon']?>" /><br />
			<input type="checkbox" name="delete_oldimage" value="1" /> - altes Icon löschen<br /><br />
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
				<?=form_submit(array('id'=>'submit', 'value' => 'Kategorie bearbeiten', 'name' => 'submit', 'class' => 'btn btn-warning btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<a href="/secure/snn/admin/categoryVerwalten"><- zurück</a>			
</div>