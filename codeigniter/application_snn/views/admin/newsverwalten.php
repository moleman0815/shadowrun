<script type="text/javascript">
	$('.hastip').tooltipsy();
	
</script>
 <div class="col-lg-8 adminpanel">
 	<div class="newstitle">News verwalten</div>
	<br />
	<div style="display:block" id="newnews" data-type="box">
		<div id="newserror"></div>
		<br />
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Titel</th>
					<th>Autor</th>
					<th>Optionen</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($news as $n): ?>
				<tr>
					<td style="width:60%;cursor:pointer"><?=$n['title'];?></td>
					<td><?=$n['autor'];?></td>
					<td>
						<span style="margin-left: 10px"><a href="/secure/snn/admin/editNews/<?=$n['nid'];?>"><img src="/secure/snn/assets/img/icons/edit.png" border="0" title="Edit" alt="Edit" /></a></span>
						<span style="margin-left: 20px"><a href="javascript:void(0)" onclick="if (confirm('News wirklich löschen?')) { deleteNews('<?=$n['nid']?>'); }; return false;"><img src="/secure/snn/assets/img/icons/delete.png" border="0" title="Löschen" alt="Löschen" /></a></span>

					</td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
		<?=$pagination;?>
	</div>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;	
</div>