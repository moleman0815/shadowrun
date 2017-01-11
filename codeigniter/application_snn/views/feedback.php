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

<style>
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 450px;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
	
	</style>


<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?>    	

    	newMessageModal = document.getElementById('feedbackBox');
    	newMessageClose = document.getElementById('feedbackClose');

    	newMessageClose.onclick = function() {
     		newMessageModal.style.display = "none";
     	}

     	window.onclick = function(event) {
     	    if (event.target == newMessageModal) {
     	    	newMessageModal.style.display = "none";
     	    }
     	}
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

	function openFeedbackModal(fid) {
		$('#feedbackParentid').val(fid);
		newMessageModal.style.display = "block";
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
			<?php 
				$children = explode(";", $f['child']);
			?>
			<div class="col-lg-12" style="border: 1px solid white;padding:5px">
				<div><b><?=$f['title']?>: <span style="color: red"><?=strtoupper($f['type'])?></span></b><br />
					<span style="float:right;margin-right:15px;">				
					<?php if($this->session->userdata('rank') == '1'): ?>
						Status: <?php echo ($f['status'] == '0') ? '<span style="color:red;cursor:pointer" onclick="changeFeedbackStatus(\''.$f['fid'].'\')")><b>offen</b></span>' : '<span style="color:green">fixed</span>';  ?>
					<?php else: ?>
						Status: <?php echo ($f['status'] == '0') ? '<span style="color:red"><b>offen</b></span>' : '<span style="color:green">fixed</span>';  ?>						
					<?php endif; ?>
					<?php if($this->session->userdata('rank') == '1' || $this->session->userdata('id') == $f['uid']): ?>
						<br />
						<span><img src="/secure/snn/assets/img/icons/delete.png" title="delete" alt="delete" onclick="if(confirm('Feedback wirklich loeschen?')) { sr.messages.deleteFeedback('<?=$f['fid']?>'); return true; } else { return false; }" style="cursor:pointer" /></span>
						&nbsp;
						<span><img src="/secure/snn/assets/img/icons/edit.png" title="edit" alt="edit" onclick="sr.messages.editFeedback('<?=$f['fid']?>')" style="cursor:pointer" /></span>
					<?php endif; ?>
					</span>
				</div>
				<?=$f['autor']?> schrieb am <?=date('d.m.Y H:i', $f['time'])?><br /><br />
				<?=nl2br($f['feedback']);?>

				<?php if($this->session->userdata('rank') == '1'): ?>
				<br /><br />
					<span style="float:right;margin-right:10px;cursor:pointer" onclick="openFeedbackModal('<?=$f['fid']?>')">[Antwort]</span>
					<br />
				<?php endif; ?>		
				<br /><br />
				<?php if (!empty($children)):?>
					<?php foreach($children as $c): ?>
					<?php if($c == 0) { continue; }?>
						<span style="color:green">
						<b><?=$answer[$c]['autor'];?></b> (<?=date('d.m.Y - H:i', $answer[$c]['time'])?> uhr)<br />
						<b><?=$answer[$c]['title'];?></b><br />
						<?=$answer[$c]['feedback'];?></span>
						<hr />
					<?php endforeach; ?>
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
		<input type="hidden" name="uid" id="uid" value="<?=$this->session->userdata('id')?>" />
		<input type="hidden" name="mode" id="mode" value="" />
		<input type="hidden" name="fid" id="fid" value="" />
		<label class="control-label" for="title" style="width:150px">Titel</label>
		<?=form_input(array('id' => 'title', 'name' =>'title', "class" => "input-xlarge"));?>
		<br />
		<label class="control-label" for="autor" style="width:150px">Name</label>
		<input type="text" name='autor' id="autor" class="input-xlarge" value="<?=ucfirst($this->session->userdata('name'))?>" readonly="readonly" />
		<br />		
		<label class="control-label" for="type" style="width:150px">Typ</label>
		<select name="type" id="type">
			<option value="bug">Bug</option>
			<option value="feature">Feature</option>
			<option value="layout">Layout</option>
			<option value="wunsch">Wunsch</option>
		</select>
		<br />					
		 <label class="control-label" for="bereich" style="width:150px">Betroffener Bereich</label>
		<?=form_input(array('id' => 'bereich', 'name' =>'bereich', "class" => "input-xlarge"));?>
		<br />		
		<div id="editStatusBox" style="display:none">
		<label class="control-label" for="editStatus" style="width:150px">Status</label>
		<select name="status" id="status">
			<option value="0">Offen</option>
			<option value="1">Geschlossen</option>
		</select>	
		</div>
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

<div id="feedbackBox" class="modal">
	<div class="modal-content">
	<button type="button" id="feedbackClose" title="Close" class="close">X</button>
		<fieldset>
		<legend class="newstitle">Feedback schreiben</legend>
			<?=form_open_multipart('/desktop/feedback');?>
			<input type="hidden" name="feedbackParentid" id="feedbackParentid" value="" />
			<input type="hidden" name="sendfeedbackanswer" id="sendfeedback" value="true" />
	
			<label for="msg_title">Titel</label><br />
			<input type="text" name="feedbacktitle" id="feedbacktitle" style="width: 90%"/>
			<br />
			<label for="msg_text">Nachrichten Text</label><br />
			<textarea name="feedbacktext" id="feedbacktext" rows="10" cols="60"></textarea>
			<br />
			<?=form_submit('senden', 'Absenden',  'class=" btn-info btn-sm" style="color:black"');?>
			</form>
		</fieldset>
	</div>
</div>