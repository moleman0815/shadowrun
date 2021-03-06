<style>
input, textarea, select {
	color: black;
}

/* The Modal (background) */
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
    width: 650px;
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
	var newMessageBtn;
	var newMessageModal;
	var newMessageClose;
	var replyMessageModal;
	var replyMessageClose;
	
 	$( document ).ready(function() { 			
     	$('#writeMessage').submit(function(event){
     		event.preventDefault();
     		sr.messages.sendNewMessage();     		
     	});
     	$('#replyMessage').submit(function(event){
     		event.preventDefault();
     		sr.messages.sendReplyMessage();     		
     	});

     	newMessageBtn = document.getElementById('newMessageBtn');
     	newMessageModal = document.getElementById('newMessageBox');
     	newMessageClose = document.getElementById('newMessageClose');
     	replyMessageModal = document.getElementById('replyMessageBox');
     	replyMessageClose = document.getElementById('replyMessageClose');
     	//

     	newMessageBtn.onclick = function (){
     		newMessageModal.style.display = "block";
     	}

     	newMessageClose.onclick = function() {
     		newMessageModal.style.display = "none";
     	}
     	replyMessageClose.onclick = function() {
     		replyMessageModal.style.display = "none";
     	}	

     	window.onclick = function(event) {
     	    if (event.target == newMessageModal) {
     	    	newMessageModal.style.display = "none";
     	    	replyMessageModal.style.display = "none";
     	    }
     	}
 	});



</script>

</head>
<body>

	<div style="margin: 0 0 20px 0">
		<button class="btn-info btn-sm" id="newMessageBtn" style="color: #000000">Neue Nachricht versenden</button>
	</div>
		<div class="alert alert-danger" id="sendMsgError" style="display:none"></div>
		<div class="alert alert-success" id="sendMsgSuccess" style="display:none"></div>

	<?php $a=0; foreach($messages['messages'] as $m):?>
	<?php 
		if($settings[0]['show_own_messages'] == 0 && $m['send_from'] == $this->session->userdata('id')) { continue; } 
	?>
	<?php $subclass = ($a%2 == 0) ? 'uneven' : '';?>	
	<?php 
		$avatar = $messages['avatar'][$m['send_from']][0]['avatar'];
		$sendto = ($m['send_to'] == $this->session->userdata('id')) ? "DICH" : $messages['avatar'][$m['send_to']][0]['nickname'];
		$nickname = ($m['send_from'] == $this->session->userdata('id')) ? "DIR" : $messages['avatar'][$m['send_from']][0]['nickname'];
		$ownclass = ($m['send_from'] == $this->session->userdata('id')) ? "green_border" : "";
		#_debug($messages);
	?>				
	<div class="newselement <?=$subclass;?> <?=$ownclass?>" style="padding: 0 5px 0 5px">
		<div>
			<a name="<?=$m['id']?>" style="text-decoration:none"></a>
				<h4>
					<?=$m['title']?>					
						<span id="new_<?=$m['id']?>"><?=($m['gelesen'] == 0 && $m['send_from'] != $this->session->userdata('id')) ? "(neu)" : ""; ?></span>
					<div style="float:right">
						<?php if($m['send_from'] == $this->session->userdata('id')): ?>
							<input type="button" value="[ Lesen ]" onclick="sr.messages.toggleMsgOwn('<?=$m['id']?>')" class=" btn-info btn-sm" style="color: #000000;padding-left:10px" />
						<?php else: ?>
							<input type="button" value="[ Lesen ]" onclick="sr.messages.toggleMsg('<?=$m['id']?>')" class=" btn-info btn-sm" style="color: #000000;padding-left:10px" />
						<?php endif; ?>	
						<?php if ($m['send_from'] != $this->session->userdata('id')): ?>
							<span style="margin-right:10px"><img src="/secure/snn/assets/img/icons/turn_right.png" onclick="sr.messages.replyMessage('<?=$m['id']?>', '<?=$m['send_from']?>')" title="reply" alt="reply" style="cursor:pointer" /></span>
						<?php else: ?>
							<span style="margin-right:20px">&nbsp;</span>
						<?php endif; ?>
						<span><img src="/secure/snn/assets/img/icons/delete.png" title="delete" alt="delete" onclick="sr.messages.deleteMsg('<?=$m['id']?>')" style="cursor:pointer" /></span>
					</div>
				</h4>		
				<span style="font-size: 12px">Nachricht von <strong><?=$nickname?></strong> an <strong><?=$sendto?></strong> am <?= date('d.m.Y - H:i', $m['date'])?> Uhr</span>
				
					<div style="clear:both"></div>
					<?php $msgclass = ($a%2 == 0) ? 'class="evenmsg"' : 'class="unevenmsg"'; ?> 					
					<div style="display:none;border:1px solid grey;padding:5px" <?=$msgclass?> id="msg_<?=$m['id']?>">
						<?php if($avatar): ?>
							<div style="float:left;width:100px;padding:5px">
								<img src="/secure/snn/assets/img/avatar/<?=$avatar;?>" alt="" />
							</div>
						<?php endif; ?>
						<?=nl2br($m['msg_text']); #nl2br?>
					</div>
				<?php if($m['child'] != '0'):?>
				<br />
					<!--<input type="button" value="[ Gesprächsverlauf anzeigen ]" onclick="showMsgHistory('msg_<?=$m['id']?>')" class=" btn-info btn-sm" style="float:left;color: #000000" />-->
				<?php endif; ?>
				<div style="clear:both"></div>
		</div>
	</div>
	<br />
	<?php $a++; ?>

	<?php endforeach; ?>
	<div class="newslinks"><?=$pagination;?></div>
	
		



<div id="replyMessageBox" class="modal">
	<div class="modal-content">
	<button type="button" id="replyMessageClose" title="Close" class="close">X</button>
		<fieldset>
			<legend>Nachricht beantworten</legend>
			<form action="#" id="replyMessage" enctype="text/html" method="post">
			<?=form_hidden('userid', $this->session->userdata('id'))?>	
			<?=form_hidden('reply', '1')?>
			<?=form_hidden('sendmsg', true);?>		
			<input type="hidden" name="senderid" id="senderid" value="" />
			<input type="hidden" name='receiverid' id="receiverid" />
			<label for="replytitle">Titel</label><br />
			<input type="text" name='replytitle' id="replytitle" style="width:95%"/>
			<br />
	
			<label for="msg_title">Empfänger</label><br />
			<?=form_input(array('name' => 'replyreceiver', 'id' => "replyreceiver", 'readonly' => true));?>
	
			<br />
			<label for="msg_text">Nachrichten Text</label><br />
			<?=form_textarea('reply_text','', 'rows="10" cols="90" id="reply_text"');?>
			<br />
			<?=form_submit('senden', 'Absenden',  'class=" btn-info btn-sm"');?>
			<?=form_close()?>
		</fieldset>
	</div>
</div>

<div id="newMessageBox" class="modal">
	<div class="modal-content">
	<button type="button" id="newMessageClose" title="Close" class="close">X</button>
		<fieldset>
		<legend class="newstitle">Nachricht schreiben</legend>
  			<form action="#" id="writeMessage" enctype="text/html" method="post"> 
			
			<input type="hidden" name="sendmsg" id="sendmsg" value="true" />
			<?php if($this->session->userdata('rank') > 1): ?>
				<input type="hidden" name="userid" id="userid" value="<?=$this->session->userdata('id');?>" />
			<?php endif; ?>

	
			<label for="msg_title">Titel</label><br />
			<input type="text" name="title" id="title" style="width: 95%"/>
			<br />
			<?php if($this->session->userdata('rank') <= 1): ?>
				<br />
				<b>Absender</b><br />
				<select name="userid" id="userid">
				<option value="">Absender w&auml;hlen</option>
				<option value=""></option>
				<?php
						foreach ($receiver as $key => $value) {
							#if ($value['id'] == $this->session->userdata('id')) continue;
							echo "<option value='".$value['id']."'>";
    						echo ucfirst($value['nickname']);
    						if ($value['rank'] == '3') {
    							echo " (NSC)";
    						}
    						echo "</option>";
						}	
				?>
				</select>
			<?php endif; ?>
			<br />
			<b>Empfänger</b><br />
			<select name="receiver[]" id="receiver" multiple size="7">
			<option value="">Empfänger w&auml;hlen</option>
			<option value=""></option>
			<?php
					foreach ($receiver as $key => $value) {
						if ($value['id'] == $this->session->userdata('id')) continue;
						echo "<option value='".$value['id']."'>";
						echo ucfirst($value['nickname']);
						if ($value['rank'] == '3' && $this->session->userdata('rank') <= 1) {
							echo " (NSC)";
						}
						echo "</option>";
					}	
			?>
			</select>
			
			<br />
			<label for="msg_text">Nachrichten Text</label><br />
			<textarea name="msg_text" id="msg_text" rows="10" cols="90"></textarea>
			<br />
			<?=form_submit('senden', 'Absenden',  'class=" btn-info btn-sm" style="color:black"');?>
			</form>
		</fieldset>
	</div>
</div>