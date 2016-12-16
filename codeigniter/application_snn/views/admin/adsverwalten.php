 <script type="text/javascript">
	function deleteAds(id) {
		$.post(
			'/secure/snn/admin/deleteAds', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#newserror').html('<div class="alert alert-error">Beim löschen der Werbung ist ein Fehler aufgetreten.</div>');
				}
				

			});	
		//
	}	
</script>
 <div class="col-lg-8 adminpanel">
	<br />
	<div style="display:block" id="newnews" data-type="box">
		<div id="newserror"></div>
		<br />
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Image</th>
					<th>Text</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($ads as $n): ?>
				<tr>
					<td style="width:10%;cursor:pointer"><img src="/secure/snn/assets/img/uploads/<?=$n['image'];?>" style="width:100px;height:100px" /></div></td>
					<td>
					<?php if(!empty($n['title'])): ?>
						<b><?=$n['title'];?></b><br />
					<?php endif; ?>
						<?=$n['text'];?>
					</td>
					<td style="width:10%">
						<span style="margin-left: 10px"><a href="/secure/snn/admin/editAds/<?=$n['id'];?>"><img src="/secure/snn/assets/img/icons/edit.png" border="0" title="Edit" alt="Edit" /></a></span>
						<span style="margin-left: 20px"><a href="javascript:void(0)" onclick="if (confirm('Werbung wirklich löschen?')) { deleteAds('<?=$n['id']?>'); }; return false;"><img src="/secure/snn/assets/img/icons/delete.png" border="0" title="Löschen" alt="Löschen" /></a></span>

					</td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
		<?=$pagination;?>
	</div>
	<br />
	<a href="/secure/snn/admin/overview"><- zurück</a>
</div>		