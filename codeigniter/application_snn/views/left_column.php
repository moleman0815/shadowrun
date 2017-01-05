<?php
	$sb_success = $this->session->userdata('sb_success');
	$this->session->unset_userdata('sb_success');
	$sb_error = $this->session->userdata('sb_error');
	$this->session->unset_userdata('sb_error');	

?>
<script type="text/javascript">
$(document).ready(function(){
   <?php if($sb_error): ?>
   		$("#sb_error").fadeOut(7000);    
   <?php endif; ?>
   <?php if($sb_success): ?>
    	$("#sb_success").fadeOut(7000);    
   <?php endif; ?>   
});
</script>

<div class="col-md-2">
	<?php if($sb_error): ?>
		<div class="alert alert-danger" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="sb_error">
			<b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$sb_error?></b>
		</div>		
	<?php endif; ?>
	<?php if($sb_success): ?>
		<div class="alert alert-success" style="z-index: 100;position: absolute; width:90%;border: 3px solid black" id="sb_success">
			<b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$sb_success?></b>
		</div>
		<br />
	<?php endif; ?>		
	<?php if($settings[0]['show_shoutbox'] != 1 ):?>	
		<?php if($show_shoutbox): ?>
			<div class="advertising">
				<div id="sberror"></div>
				<span class="advbroad">Shoutbox</span>
				<br />
				<?php if (!empty($shoutbox)): ?>
					<?php $x=0; foreach ($shoutbox as $s): ?>
						<?php $subclass = ($x%2 == 0) ? 'uneven' : '';?>
						<div class="sb_textblock <?=$subclass?>">
							<span><?=date('d.m.Y H:i', $s['sb_time']);?>
							<?php if($s['login_id'] == $this->session->userdata('id') || $this->session->userdata('rank') == '1'):?>
								<div style="float:right;margin-right:5px"><img onclick="deleteShoutbox('<?=$s['sb_id']?>')" src="/secure/snn/assets/img/icons/delete.png" title="delete" alt="delete" style="cursor:pointer" /></div>
							<?php endif;?>
							</span>
							<?=$s['nickname']?><br /><br />
							<?=$s['sb_text']?><br />
						</div>
						<br />
					<?php $x++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<span><a href="/secure/snn/desktop/shoutbox">>> Shoutbox Archiv</a></span>
				<div style="margin:5px">
					<?=form_open_multipart(base_url() . 'desktop/newShoutbox')?>
					<?=form_hidden('userid', $this->session->userdata('id'))?>
					<?=form_hidden('from', '/'.$this->uri->segment(1).'/'.$this->uri->segment(2))?>
					<?=form_textarea(array('name' => 'sb_text', 'class' => 'sb_text', 'rows' => '3'));?>
					
					<?=form_submit(array('name' => 'sb_senden', "value" => "Absenden", 'class' => 'sb_button'));?>
					<?=form_close()?>
				
				</div>
			</div>
			<br />
		<?php endif; ?>
	<?php endif; ?>

	<!-- // FRIENDS // -->
	<?php if($settings[0]['show_friends'] != 1 ):?>
		<?php if($show_friends): ?>
			<div class="advertising">
				<span class="advbroad">Freunde:</span>
				<br />	
					<?php if (!empty($friends)): ?>
						<div class="left_news">
						<?php $i=0; foreach($friends as $m): ?>	
							<?php $subclass = ($i%2 == 0) ? 'uneven' : '';?>					
							<div class="<?=$subclass?>">
								<?=ucfirst($m['nickname']);?>&nbsp;
								<?php if(($m['lastactive']+1200) > time()):?>
									<span style="float:right"><img src="/secure/snn/assets/img/icons/power_on.png" alt="online" title="online" /></span>
								<?php else: ?>
									<span style="float:right"><img src="/secure/snn/assets/img/icons/power_off.png" alt="offline" title="offline" /></span>
								<?php endif; ?>
							</div>
							<?php $i++; ?> 
						<?php endforeach; ?>
						</div>
					<?php endif; ?>
			</div>
			<br />
			<div style="clear:both"></div>
		<?php endif; ?>
	<?php endif; ?>			

	<?php if($settings[0]['show_msgbox'] != 1 ):?>
		<?php if($show_messages): ?>
			<div class="advertising">
				<span class="advbroad">Neueste Nachrichten:</span>
				<br />	
					<?php if (!empty($column_messages)): ?>				
						<?php $i=0; foreach($column_messages as $m): ?>	
							<?php $subclass = ($i%2 == 0) ? 'uneven' : '';?>					
							<div class="left_news <?=$subclass?>">
								<a href="/secure/snn/desktop/messages/#<?=$m['id'];?>">
										Von: <?=$m['nickname']?><br />
										Am: <?=date('d.m.Y h:m', $m['date']);?><br /><br />
										<?=substr($m['msg_text'], 0, 50);?> ...
								</a>
							</div>
							<br />	
							<?php $i++; ?> 
						<?php endforeach; ?>
					<?php endif; ?>
				<button onclick="location.href='/secure/snn/desktop/messages'" class="sb_button">Nachrichtencenter öffnen</button>
			</div>
			<br />
		<?php endif; ?>
	<?php endif; ?>
</div>