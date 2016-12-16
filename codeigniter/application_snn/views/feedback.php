<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');

	foreach ($feedback as $f) {
		if ($f['type'] == 'answer') {
			$answer[$f['fid']] = $f;
		}
	}

?>

<!--

<?=_debug($answer);?>
<?=_debug($feedback);?>

-->

<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?>    	
	});
<?php if($this->session->userdata('rank') == '1'): ?>	
	function changeFeedbackStatus(id) {
		$.post(
			'/secure/snn/desktop/changeFeedbackStatus', 
			{fid: id}, 
			function(data) {
					location.reload();			
			});	
		//
	}
<?php endif; ?>	
</script>
<style>
	input, textarea, select {
		color: black;
	}
</style>
<div class="col-lg-2"></div>
<div class="col-lg-8 newselement">
	<div class="newstitle">Feedback</div>
	<br />
	<?php if($error): ?>
		<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
	<?php endif; ?>
	<?php if($success): ?>
		<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
	<?php endif; ?>		
	<?php if(!empty($feedback)): ?>
		<?php foreach($feedback as $f): ?>
		<?php if($f['type'] == 'answer') { continue; } ?>
			<div class="col-lg-8" style="border: 1px solid white;padding:5px">
				<div><b><?=$f['title']?>: <?=ucfirst($f['type'])?></b><br />
				<?php if($this->session->userdata('rank') == '1'): ?>
					<span style="float:right;margin-right:15px;">Status: <?php echo ($f['status'] == '0') ? '<span style="color:red;cursor:pointer" onclick="changeFeedbackStatus(\''.$f['fid'].'\')")><b>offen</b></span>' : '<span style="color:green">fixed</span>';  ?></span></div>
				<?php else: ?>
					<span style="float:right;margin-right:15px">Status: <?php echo ($f['status'] == '0') ? '<span style="color:red"><b>offen</b></span>' : '<span style="color:green">fixed</span>';  ?></span></div>
				<?php endif; ?>
				<?=$f['autor']?> schrieb am <?=date('d.m.Y H:i', $f['time'])?><br /><br />
				<?=nl2br($f['feedback']);?>

				<?php if($this->session->userdata('rank') == '1'): ?>
				<br /><br />
					<span style="float:right;margin-right:10px">[Antwort]</span>
					<br />
				<?php endif; ?>				
				<?php if(!empty($answer[$f['child']])): ?>
					<br />
					<span style="color:green">
					<b><?=$answer[$f['child']]['autor'];?></b><br />
					<?=$answer[$f['child']]['feedback'];?></span>
				<?php endif; ?>
			</div>
			<div style="clear:both"></div>
			<br />
		<?php endforeach; ?>

		<br />
	<?php endif; ?>
<div style="clear:both"></div>	
	<br /><hr />
	<div style="margin: 0 auto;padding:15px;">
	<fieldset style="border:wpx solid white">
		<legend style="color:white">Feedback schreiben</legend>
	<?=form_open_multipart('/desktop/feedback');?>
	<?=form_hidden('sendFeedback', true);?>
		<label class="control-label" for="title" style="width:150px">Titel</label>
		<?=form_input(array('id' => 'title', 'name' =>'title', "class" => "input-xlarge"));?>
		<br />
		<label class="control-label" for="autor" style="width:150px">Name</label>
		<input type="text" name='autor' class="input-xlarge" value="<?=ucfirst($this->session->userdata('name'))?>" readonly="readonly" />
		<br />		
		<label class="control-label" for="type" style="width:150px">Typ</label>
		<select name="type">
			<option value="bug">Bug</option>
			<option value="feature">Feature</option>
			<option value="layout">Layout</option>
		</select>
		<br />					
		<label class="control-label" for="bereich" style="width:150px">Betroffener Bereich</label>
		<?=form_input(array('id' => 'bereich', 'name' =>'bereich', "class" => "input-xlarge"));?>
		<br />			
		<label class="control-label" for="feedback" style="width:150px">Feedback</label>
		<br />
		<?=form_textarea(array('id' => 'feedback', 'name' =>'feedback', "class" => "input-xlarge", "style" => "width:600px;height:150px"));?>
		<br />	<br />	
		<?=form_submit(array('id'=>'submit', 'value' => 'Feedback absenden', 'name' => 'submit', 'class' => 'btn btn-warning btn-sm'));?>		
	<?=form_close();?>
	</fieldset>
	</div>
</div>
<div class="col-lg-2"></div>
<div style="clear:both"></div>
<br />&nbsp;