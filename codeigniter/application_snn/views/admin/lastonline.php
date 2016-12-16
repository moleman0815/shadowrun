 <div class="col-lg-8 adminpanel">
 	<div class="newstitle">News verwalten</div>
	<br />
	<div style="display:block" id="newnews" data-type="box">
		<div id="newserror"></div>
		<br />
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>User</th>
					<th>Page</th>
					<th>lastonline</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($online as $o): ?>
			<tr>
				<td><?=$o['name'];?></td>
				<td><?=$o['page'];?></td>
				<td><?=date('d.m.Y H:i', $o['lastactive']);?></td>
			</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;	
</div>		