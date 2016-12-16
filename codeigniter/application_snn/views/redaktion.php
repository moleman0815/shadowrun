<style>
.tooltipsy
{
    padding: 10px;
    max-width: 200px;
    color: #303030;
    background-color: #f5f5b5;
    border: 1px solid #deca7e;
}
</style>
<script type="text/javascript">
$('.hastip').tooltipsy();
</script>
 
	<br />
 	<ul class="nav nav-pills">
	  	<li role="presentation" class="active"><a href="#" onclick="showEinstellungen('newnews')">News verwalten</a></li>
	  	<li role="presentation"><a href="#" onclick="showEinstellungen('newad')">Werbung verwalten</a></li>
	</ul>
	<br />
	<div style="display:block" id="newnews" data-type="box">
		<table class="table table-striped table-bordered table-hover">
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
					<td style="width:60%;cursor:pointer"><div class="hastip" title="<?=$n['teaser'];?> ..."><?=$n['title'];?></div></td>
					<td><?=$n['autor'];?></td>
					<td>
						<span style="margin-left: 10px"><img src="/secure/snn/assets/img/icons/edit.png" border="0" title="Edit" alt="Edit" /></span>
						<span style="margin-left: 20px"><img src="/secure/snn/assets/img/icons/delete.png" border="0" title="Löschen" alt="Löschen" /></span>

					</td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
		<?=print_r($news);?>
	</div>
