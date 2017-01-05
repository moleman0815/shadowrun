</div> <!-- End div from maincontent -->
<div class="col-md-2">
	<?php if($settings[0]['show_ads'] != 1 ):?>
		<?php if($show_ads): ?>
			<div class="advertising">
				<span class="advbroad">Advertising</span>
				<br />
				<img src="/secure/snn/assets/img/uploads/<?=$ads[0]['image'];?>" alt="<?=$ads[0]['image'];?>" title="<?=$ads[0]['image'];?>"  style="width:100%" />
				<br /><br />
			<?php if(!empty($ads[0]['title'])):?>
				<b><?=$ads[0]['title'];?></b>
				<br /><br />
			<?php endif; ?>
				<?=$ads[0]['text'];?>
			</div>
			<br />
		<?php endif; ?>
	<?php endif; ?>
</div>
