<?php 
	$options = array('' => 'wähle', '1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','6' => '6','7' => '7','8' => '8','9' => '9','10' => '10');
	$js = 'id="missionlevel" onChange="changeDifficulty(this.value)"';

?>
<script>
	$( document ).ready(function() {
		var level = $('#missionlevel').val();
		changeDifficulty(level);

		$( "#tabs" ).tabs();
		var last_index = $.cookie("lasttab");
	    $("#tabs").tabs( "option", "active", last_index );
	});

	$(function() {
		$("li").click(function() {
		    var current_index = $("#tabs").tabs("option","active");
		    $.cookie("lasttab", current_index);
		});
	});	

	
	
</script>
<style>
	select {
		color: #000000;
	}
.table-striped>tbody>tr:nth-child(odd)>td, 
.table-striped>tbody>tr:nth-child(odd)>th {
   background-color: #123456;
   color: white;
 }
 .table-striped>tbody>tr:nth-child(even)>td, 
.table-striped>tbody>tr:nth-child(even)>th {
   background-color: white;
   color: black;
 }
 .ui-widget-content {
	background-image: none;
	background-color: black;
	
	color: #FFFFFF;
}
.ui-widget-header {
	background-color: black;
	background-image: none;
	color: #FFFFFF;	
}

</style>

	<span class="btn btn-lg btn-success">
		<span class="fa fa-wheelchair fa-2x pull-left">&nbsp;Willkommen in der COMBATZONE</span>
	</span>
	<br />
	<?php if(!empty($char)): ?>
		<br />
	<div id="tabs">
		<ul>
			<li><a href="#mission">Missionen</a></li>
		  	<li><a href="#statistik">Statistik</a></li>
		  	<?php if($this->session->userdata('rank') == '1'): ?>
			<li><a href="#chars">Chars</a></li>
		  	<?php endif; ?>
	
		</ul>	
	<?php if($this->session->userdata('rank') == '1'): ?>
		<div id="chars">

		</div>
	<?php endif; ?>
	
	<div id="mission">
		Du hast folgenden Charakter im System hinterlegt:
		<br /><br />
		<table class="table table-condensed" style="font-size: 12px">
			<thead>
				<tr>
					<?php if(!empty($avatar[0]['avatar'])): ?>
						<th>Portrait</th>
					<?php else: ?>
						<th></th>
					<?php endif; ?>
					<th>Charname</th>
					<th>Rasse</th>
					<th>Kon</th>
					<th>Sch</th>
					<th>Str</th>
					<th>Cha</th>
					<th>Int</th>
					<th>Will</th>
					<th>Essenz</th>
					<th>Magie</th>
					<th>Fern</th>
					<th>Nah</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				<?php if(!empty($avatar[0]['avatar'])): ?>				
					<td><img src="/secure/snn/assets/img/avatar/<?=$avatar[0]['avatar'];?>" alt="" /></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>
					<td><button class="btn btn-default"><i class="info fa fa-heartbeat"></i>&nbsp;<?=ucfirst($char[0]['charname']);?></button></td>
					<td><button class="btn btn-default"><?=ucfirst($char[0]['race']);?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['body'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['quickness'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['strength'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['charisma'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['intelligence'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['willpower'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['essence'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['magic'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['armed_longrange'];?></button></td>
					<td><button class="btn btn-default"><?=$char[0]['armed_combat'];?></button></td>
				</tr>
			</tbody>
		</table>
		<br />
		Wähle deinen Schwierigkeitsgrad aus:
		
		<?=form_dropdown('missionlevel', $options, '', $js);?>
		<br />
		
			<br />

			

		<br />
		<div id="json_receiver"></div>
	</div>
	<div id="statistik">		
		
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>Charname</th>
					<th>Titel</th>
					<th>Level</th>
					<th>Ergebnis</th>
					<th>Kills</th>
					<th>Gewinn</th>
					<th>Verlust</th>
					<!--<th>Verlauf</th>-->
				</tr>
			</thead>
			<tbody>
			<?php foreach($stats as $s): ?>
				<tr>
					<td><?=ucfirst($s['runner']);?></td>
					<td><?=$s['title'];?></td>
					<td><?=$s['level'];?></td>
					<td><?=$s['result'];?></td>
					<td><?=$s['kills'];?></td>
					<td><?=$s['cash'];?> &yen;</td>
					<td><?=$s['loss'];?> &yen;</td>
					<!--<td><div></div></td>-->
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

	</div>

	<?php else: ?>
	<br />
		<div class="errormsg">
			Um an Missionen teilnehmen zu können, musst du erst deinen Charakter hinterlegen.<br />
			<a href="/secure/snn/desktop/einstellungen/">HIER</a> gehts lang ....
		</div>
		<br />
	<?php endif; ?>
		<br />&nbsp;
	</div>

