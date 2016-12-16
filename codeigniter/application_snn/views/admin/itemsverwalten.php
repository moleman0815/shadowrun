<script type="text/javascript">
	$('.hastip').tooltipsy();
	function deleteItem(id) {
		$.post(
			'/secure/snn/admin/deleteItem', 
			{wid: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#newserror').html('<div class="alert alert-error">Beim Löschen des Gegenstandes ist ein Fehler aufgetreten.</div>');
				}
				console.log(json.status);

			});	
		//
	}	
</script>
 <div class="col-lg-8 adminpanel">
 	<div class="newstitle">Gegenstände verwalten</div>
	<br />
	<div style="display:block" id="newnews" data-type="box">
		<div id="newserror"></div>
		<br />
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Typ</th>
					<th>Munition</th>
					<th>Schaden</th>
					<th>Modus</th>
					<th>Kosten</th>
					<th>Rückstoß</th>
					<th>Beschreibung</th>					
					<th>Optionen</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($items as $n): ?>
				<tr>
					<td><?=ucfirst($n['name']);?></td>
					<td><?=ucfirst($n['type']);?></td>
					<td><?=$n['ammo'];?></td>
					<td><?=$n['damage'];?></td>
					<td><?=$n['mode'];?></td>
					<td><?=$n['cost'];?></td>
					<td><?=$n['reduce'];?></td>					
					<td><?=$n['description'];?></td>					
					<td>
						<span style="margin-left: 10px"><a href="/secure/snn/admin/editItem/<?=$n['wid'];?>"><img src="/secure/snn/assets/img/icons/edit.png" border="0" title="Edit" alt="Edit" /></a></span>
						<span style="margin-left: 20px"><a href="javascript:void(0)" onclick="if (confirm('<?=ucfirst($n['name']);?> wirklich löschen?')) { deleteItem('<?=$n['wid']?>'); }; return false;"><img src="/secure/snn/assets/img/icons/delete.png" border="0" title="Löschen" alt="Löschen" /></a></span>

					</td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;
</div>