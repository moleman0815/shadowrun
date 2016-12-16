<style>
input, textarea, select {
	color: black;
}
</style>
<?php # _debugDie($messages); ?>

	<div style="margin: 0 0 20px 0">
		<a class="fancybox" href="#divReply" id="replyForm" style="display:none"></a>
		<a class="fancybox" href="#divHistory" id="HistoryForm" style="display:none"></a>
		<a class="fancybox btn-info btn-sm" href="#divForm" id="btnForm" style="color: #000000">Neue Nachricht versenden</a>
	</div>
		<?php if($msg['error']): ?>
			<div class="alert alert-success"><?=$msg['error']?></div>
			<br />
		<?php endif; ?>
		<?php if($msg['success']): ?>
			<div class="alert alert-success"><?=$msg['success']?></div>
			<br />
		<?php endif; ?>	
	<?php $a=0; foreach($messages['messages'] as $m):?>
	<?php $subclass = ($a%2 == 0) ? 'uneven' : '';?>	
	<?php 
		$avatar = $messages['avatar'][$m['send_to']][0]['avatar'];
		$nickname = ($m['send_to'] == $this->session->userdata('id')) ? "DIR" : $messages['avatar'][$m['send_to']][0]['nickname'];
	?>				
	<div class="newselement <?=$subclass;?>">
		<?php if($avatar): ?>
			<div style="float:left;width:100px;padding:10px">
				<img src="/secure/snn/assets/img/avatar/<?=$avatar;?>" alt="" />
			</div>
		<?php endif; ?>
		<div>
			<a name="<?=$m['id']?>" style="text-decoration:none"></a>
				<h4>
					<?=$m['title']?>
					<div style="float:right">
						<span style="margin-right:10px"><img src="/secure/snn/assets/img/icons/turn_right.png" onclick="replyMsg('<?=$m['id']?>', '<?=$m['id']?>')" title="reply" alt="reply" style="cursor:pointer" /></span>
						<span><img src="/secure/snn/assets/img/icons/delete.png" title="delete" alt="delete" onclick="deleteMsg('<?=$m['id']?>')" style="cursor:pointer" /></span>
					</div>
				</h4>		
				<p>Nachricht von <strong><?=$nickname?></strong> am <?= date('d.m.Y H:i', $m['date'])?></p>
				<span id="teaser_<?=$m['id']?>"><?=substr($m['msg_text'], 0, 100);?> ...</span><br />
				<?php if (strlen($m['msg_text']) > 100): ?>
					<input type="button" value="[ Full Message ]" onclick="toggleMsg('msg_<?=$m['id']?>')" class=" btn-info btn-sm" style="float:right;color: #000000" />
									<br />
					<div style="clear:both"></div>
					<?php $msgclass = ($a%2 == 0) ? 'class="evenmsg"' : 'class="unevenmsg"'; ?> 
					<div style="display:none;border:1px solid grey;padding:10px" <?=$msgclass?> id="msg_<?=$m['id']?>">
						<?=$m['msg_text']?>
					</div>
				<?php endif; ?>
				<?php if($m['child'] != '0'):?>
				<br />
					<!--<input type="button" value="[ Gesprächsverlauf anzeigen ]" onclick="showMsgHistory('msg_<?=$m['id']?>')" class=" btn-info btn-sm" style="float:left;color: #000000" />-->
				<?php endif; ?>
				<div style="clear:both"></div>
		</div>
	</div>
	<br />
	<?php $a++; ?>
	<br />
	<?php endforeach; ?>
	<div><?=$pagination?></div>


<div id="divForm" style="width:450px;height:350px;display:none" class="fancybox-hidden newselement">
	<fieldset>
		<legend class="newstitle">Nachricht schreiben</legend>
		<?=form_open('desktop/messages')?>
		<?=form_hidden('userid', $this->session->userdata('id'))?>
		<?=form_hidden('sendmsg', true);?>
		<label for="msg_title">Titel</label><br />
		<?=form_input('title','');?>
		<br /><br />
		<select name="receiver">
		<option value="">Empfänger</option>
		<?php
				foreach ($receiver as $key => $value) {
					if ($value['id'] == $this->session->userdata('id')) continue;
					echo "<option value='".$value['id']."'>".ucfirst($value['nickname'])."</option>";
				}	
		?>
		</select>
		<br />
		<label for="msg_text">Nachrichten Text</label><br />
		<?=form_textarea('msg_text','', 'rows="5" cols="40"');?>
		<br />
		<?=form_submit('senden', 'Absenden',  'class=" btn-info btn-sm" style="color:black"');?>
		<?=form_close()?>
	</fieldset>
</div>


<div id="divReply" style="width:450px;height:350px;display:none" class="fancybox-hidden newselement">
	<fieldset>
		<legend>Nachricht beantworten</legend>
		<?=form_open('desktop/messages')?>
		<?=form_hidden('userid', $this->session->userdata('id'))?>	
		<?=form_hidden('reply', '1')?>
		<?=form_hidden('sendmsg', true);?>		
		<input type="hidden" name="senderid" id="senderid" value="" />
		<input type="hidden" name='receiverid' id="receiverid" />
		<label for="replytitle">Titel</label><br />
		<input type="text" name='replytitle' id="replytitle" />
		<br />

		<label for="msg_title">Empfänger</label><br />
		<?=form_input(array('name' => 'replyreceiver', 'id' => "replyreceiver", 'readonly' => true));?>

		<br />
		<label for="msg_text">Nachrichten Text</label><br />
		<?=form_textarea('reply_text','', 'rows="5" cols="40" id="reply_text"');?>
		<br />
		<?=form_submit('senden', 'Absenden',  'class=" btn-info btn-sm"');?>
		<?=form_close()?>
	</fieldset>
</div>

