<script type="text/javascript">
	$('.hastip').tooltipsy();
	function deleteGanger(id) {
		$.post(
			'/secure/snn/admin/deleteGanger', 
			{gid: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#newserror').html('<div class="alert alert-error">Beim löschen des Ganger ist ein Fehler aufgetreten.</div>');
				}
				console.log(json.status);

			});	
		//
	}	
</script>
 <div class="col-lg-8 admininterface">
	<div style="display:block" id="newnews" data-type="box">
		<div id="newserror"></div>
		<div class="newstitle">Ganger verwalten</div>
		<br /><br />

		<table class="table table-striped table-bordered ">
			<thead>
				<tr>
					<th>Portrait</th>
					<th>Name</th>
					<th>Rasse</th>
					<th>Sex</th>
					<th>Level</th>
					<th>Archetyp</th>
					<th>Optionen</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($allganger as $n): ?>
				<tr>
					<td><img src="/secure/snn/assets/img/combat/ganger/<?=$n['profile'];?>" alt="<?=$n['profile'];?>" title="<?=$n['profile'];?>" style="height:50px;width:50px" /></td>
					<td><?=ucfirst($n['ganger_name']);?></td>
					<td><?=ucfirst($n['race']);?></td>
					<td><?=ucfirst($n['gender']);?></td>
					<td><?=$n['level'];?></td>
					<td><?=ucfirst($n['archetyp']);?></td>
					<td>
						<span style="margin-left: 10px"><a href="/secure/snn/admin/editGanger/<?=$n['gid'];?>"><img src="/secure/snn/assets/img/icons/edit.png" border="0" title="Edit" alt="Edit" /></a></span>
						<span style="margin-left: 20px"><a href="javascript:void(0)" onclick="if (confirm('<?=ucfirst($n['ganger_name']);?> wirklich löschen?')) { deleteGanger('<?=$n['gid']?>'); }; return false;"><img src="/secure/snn/assets/img/icons/delete.png" border="0" title="Löschen" alt="Löschen" /></a></span>

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