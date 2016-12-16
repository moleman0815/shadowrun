<?php

#_debug($shoutbox);

?>

		<div class="advertising">
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
		</div>
