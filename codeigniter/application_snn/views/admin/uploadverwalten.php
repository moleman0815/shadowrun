<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');
?>
<script type="text/javascript">
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?>    	
	});
	function deleteUpload(id) {
		$.post(
			'/secure/snn/admin/deleteUpload', 
			{source: id, type: '<?=$type?>', deleteItem: true}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				location.reload();
			});	
		//
	}	
</script>
 <div class="col-lg-8 admininterface">
	<div style="display:block" id="newnews" data-type="box">
		<div id="newserror"></div>
		<div class="newstitle"><?=ucfirst($type);?> verwalten</div>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>	
		<br />

		<table class="table table-striped table-bordered ">
			<thead>
				<tr>
					<th>Bild</th>
					<th>Optionen</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($images as $n): ?>
		<?php if(preg_match("/missions/", $n)) { $width = '200';} else { $width = '50';} ?>
				<tr>
					<td><img src="/secure/snn/assets/img/combat/<?=$type?>/<?=$n;?>" alt="<?=$n;?>" title="<?=$n;?>" style="height:50px;width:<?=$width?>px" /></td>
					<td><span style="margin-left: 20px"><a href="javascript:void(0)" onclick="if (confirm('<?=$n;?> wirklich löschen?')) { deleteUpload('<?=$n;?>'); }; return false;"><img src="/secure/snn/assets/img/icons/delete.png" border="0" title="Löschen" alt="Löschen" /></a></span></td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;	
</div>