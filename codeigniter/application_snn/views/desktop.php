
	<?php foreach($news as $n):?>
		<div>
			<fieldset class="newselement">
			
				<div class="newstitle"><?=strip_tags($n['title']);?></div>
				<br />
				<div class="col-sm-2">
					<img src="/secure/snn/assets/img/news/icons/<?=$n['icon'];?>" alt="<?=$n['cat_name'];?>" title="<?=$n['cat_name'];?>" />
				</div>
				<div class="col-sm-5">
					von: <?=$n['autor'];?> am: <?=date('d.m.Y H:i', $n['date']);?>
				</div>
				<div class="col-sm-12">
					<br />
					<span id="teaser_<?=$n['nid'];?>" style="display:block;border:1px solid grey;padding:5px;">
						<?=$n['teaser'];?>
						<?php if(strlen($n['newstext']) > strlen($n['teaser'])): ?>
							...
						<?php endif;?>
					</span>
					<?php if(strlen($n['newstext']) > strlen($n['teaser'])): ?>
					 <div style="border:1px solid grey;padding:5px;display:none" id="news_<?=$n['nid'];?>">
						<?=$n['newstext'];?>
					 </div>
						<br />
						<a href="javascript:void(0)" onClick="toggleNews('<?=$n['nid'];?>')" id="link_<?=$n['nid'];?>" class="btn-info btn-xs" style="float:right;border:1px solid black;color:black"><span>[ more ]</span></a>	
					 
					<?php endif;?>
				</div>
			
				<br />&nbsp;
			</fieldset>
		</div>
		<br />
		<div style="clear:all"></div>				
	<?php endforeach; ?>
	<div class="newslinks"><?=$pagination;?></div>


