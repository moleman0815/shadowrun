<style>

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
	var newCommentModal;
	var newCommentClose;
	
 	$( document ).ready(function() { 			
     	$('#writeComment').submit(function(event){
     		event.preventDefault();
     		sr.messages.sendNewComment();     		
     	});

     	newCommentModal = document.getElementById('newCommentModal');
     	newCommentClose = document.getElementById('newCommentClose');

     	newCommentClose.onclick = function() {
     		newCommentModal.style.display = "none";
     	}
     	window.onclick = function(event) {
     	    if (event.target == newCommentModal) {
     	    	newCommentModal.style.display = "none";
     	    }
     	}
 	});



</script>
	<div class="alert alert-danger" id="sendMsgError" style="display:none"></div>
	<div class="alert alert-success" id="sendMsgSuccess" style="display:none"></div>
	<?php foreach($news as $n):?>
		<div>
			<fieldset class="newselement">
			
				<div class="newstitle"><?=strip_tags($n['title']);?></div>
				<br />
				<div class="col-sm-2">
					<img src="/secure/snn/assets/img/news/icons/<?=$n['icon'];?>" alt="<?=$n['cat_name'];?>" title="<?=$n['cat_name'];?>" />
				</div>
				<div class="col-sm-10">
					von: <?=$n['autor'];?> am: <?=date('d.m.Y H:i', $n['date']);?>
				
					<br />
					<span id="teaser_<?=$n['nid'];?>" style="display:block;border:1px solid grey;padding:5px;">
						<?=$n['teaser'];?>
						<?php if(strlen($n['newstext']) > strlen($n['teaser'])): ?>
							...
						<?php endif;?>
					</span>
											<br />
					<?php if(strlen($n['newstext']) > strlen($n['teaser'])): ?>
					 	<div style="border:1px solid grey;padding:5px;display:none" id="news_<?=$n['nid'];?>">
							<?=$n['newstext'];?>
					 	</div>					 						
						<a href="javascript:void(0)" onClick="toggleNews('<?=$n['nid'];?>')" id="link_<?=$n['nid'];?>" class="btn-info btn-xs" style="float:right;border:1px solid black;color:black"><span>[ more ]</span></a>						 	
					<?php endif;?>
					<a href="javascript:void(0)" onClick="sr.messages.toggleComment('<?=$n['nid'];?>')" id="comment_<?=$n['nid'];?>"  class="btn-info btn-xs" style="float:right;border:1px solid black;color:black"><span>Kommentar schreiben</span></a>
				</div>
				<?php if(count($n['comments']) > 0): ?>
					<a href="javascript:void(0)" onclick="sr.messages.toggleNewsComment('<?=$n['nid'];?>')">Kommentare (<?=count($n['comments']);?>)</a>
					<div style="display:none" id="commentbox_<?=$n['nid'];?>">
						<?php $iter=0;foreach($n['comments'] as $c): ?>
							<?php $subclass = ($iter%2 == 0) ? 'uneven' : '';?>
							<fieldset class="newselement <?=$subclass?>">
								<span class="small">Kommentar von <?=ucfirst($c['nickname'])?> am <?=date('d.m.Y H:i', $c['date'])?></span><br />
								<?php if ($c['uid'] == $this->session->userdata('id')): ?>
									<span style="float:right"><img src="/secure/snn/assets/img/icons/delete.png" title="delete" alt="delete" onclick="sr.messages.deleteComment('<?=$c['cid']?>')" style="cursor:pointer" /></span>
								<?php endif; ?>
								<?=$c['comment']?>
								
							</fieldset>
							<?php $iter++; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<br />&nbsp;
			</fieldset>
		</div>
		<br />
		<div style="clear:all"></div>				
	<?php endforeach; ?>
	<div class="newslinks"><?=$pagination;?></div>


<div id="newCommentModal" class="modal">
	<div class="modal-content">
	<button type="button" id="newCommentClose" title="Close" class="close">X</button>
		<fieldset>
		<legend class="newstitle">Kommentar schreiben</legend>
  			<form action="#" id="writeComment" enctype="text/html" method="post"> 
			<input type="hidden" name="newsid" id="newsid" value="" />
			<input type="hidden" name="sendcomment" id="sendcomment" value="true" />
			<input type="hidden" name="userid" id="userid" value="<?=$this->session->userdata('id');?>" />

			<br />
			<label for="msg_text">Kommentar</label><br />
			<textarea name="comment" id="comment" rows="10" cols="90"></textarea>
			<br />
			<?=form_submit('senden', 'Absenden',  'class=" btn-info btn-sm" style="color:black"');?>
			</form>
		</fieldset>
	</div>
</div>