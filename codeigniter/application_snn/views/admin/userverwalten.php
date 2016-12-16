<?php
	$rank = array('1' => 'Superadmin', '2' => 'Admin', '3' => 'NPC', '5' => 'User');
?>
<script type="text/javascript">
	$('.hastip').tooltipsy();
	function deleteUser(id) {
		$.post(
			'/secure/snn/admin/deleteUser', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#newserror').html('<div class="alert alert-error">Beim löschen des Users ist ein Fehler aufgetreten.</div>');
				}
				console.log(json.status);

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
					<th>Login</th>
					<th>Nickname</th>
					<th>Rang</th>
					<th>Optionen</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($user as $n): ?>
			<?php /*if ($n['rank'] == '1'): continue; endif;*/ ?>
				<tr>
					<td><?=$n['name'];?></td>
					<td><?=$n['nickname'];?></td>
					<td><?=$rank[$n['rank']];?></td>

					<td>
						<span style="margin-left: 10px"><a href="/secure/snn/admin/editUser/<?=$n['id'];?>"><img src="/secure/snn/assets/img/icons/edit.png" border="0" title="Edit" alt="Edit" /></a></span>
						<span style="margin-left: 20px"><a href="javascript:void(0)" onclick="if (confirm('User <?=$n['name'];?> wirklich löschen?')) { deleteUser('<?=$n['id']?>'); }; return false;"><img src="/secure/snn/assets/img/icons/delete.png" border="0" title="Löschen" alt="Löschen" /></a></span>

					</td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<br />
	<a href="/secure/snn/admin/overview" class="newstitle" style="color:black"><- zurück</a>
	<br />&nbsp;
</div>