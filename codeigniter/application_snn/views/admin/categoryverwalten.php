 <script type="text/javascript">
	function deleteCategory(id) {
		$.post(
			'/secure/snn/admin/deleteCategory', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#newserror').html('<div class="alert alert-error">Beim Löschen der Kategorie ist ein Fehler aufgetreten.</div>');
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
					<th>Name</th>
					<th>Autor</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($ads as $n): ?>
				<tr>
					<td style="width:10%;cursor:pointer"><img src="/secure/snn/assets/img/news/icons/<?=$n['icon'];?>" style="width:100px;height:100px" /></div></td>
					<td><?=$n['cat_name'];?></td>
					<td><?=$n['autor'];?></td>
					<td style="width:10%">
						<span style="margin-left: 10px"><a href="/secure/snn/admin/editCategory/<?=$n['id'];?>"><img src="/secure/snn/assets/img/icons/edit.png" border="0" title="Edit" alt="Edit" /></a></span>
						<span style="margin-left: 20px"><a href="javascript:void(0)" onclick="if (confirm('Kategorie <?=$n['cat_name'];?> wirklich löschen?')) { deleteCategory('<?=$n['id']?>'); }; return false;"><img src="/secure/snn/assets/img/icons/delete.png" border="0" title="Löschen" alt="Löschen" /></a></span>

					</td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<br />
	<a href="/secure/snn/admin/overview"><- zurück</a>
</div>		