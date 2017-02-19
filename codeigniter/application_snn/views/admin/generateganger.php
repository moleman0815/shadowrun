<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');

$n_options = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'); 
$r_options = array('mensch' => 'Mensch', 'elf' => 'Elf', 'zwerg' => 'Zwerg', 'ork' => 'Ork', 'troll' => 'Troll', 'critter' => 'Critter');
?>
<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?>    	
	});
 </script>
<div class="col-lg-8 admininterface">
	<fieldset>
		<legend class="newstitle">Generate Ganger</legend>
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left:25%;border: 3px solid black" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
			<?php endif; ?>	
			<div id="gangername_error"></div>
			<?=form_open('/admin/generateGanger');?>
			<?=form_hidden('sendGanger', true);?>
			<div class="col-sm-6">
				<label class="control-label" for="gangername" style="width:150px">Ganger Name</label>
				<?php $js = 'onblur="checkGangerName(this.value)"'; ?>
				<?=form_input(array('id' => 'gangername', 'name' =>'gangername', "class" => "input-xlarge"), '', $js);?>
				<br />
				<label class="control-label select_width" for="gangerrace" style="width:150px">Ganger Rasse</label>
				<?=form_dropdown('gangerrace', $r_options);?>
				<br />			
				<label class="control-label select_width" for="gangergender" style="width:150px">Ganger Geschlecht</label>
				<?=form_dropdown('gangergender', array('male' => 'männlich', 'female' => 'weiblich'));?>
				<br />		
				<label class="control-label select_width" for="gangerlevel" style="width:150px">Ganger Level</label>
				<?=form_dropdown('gangerlevel', $n_options);?>
				<br />
				<label class="control-label select_width" for="gangertype" style="width:150px">Ganger Type</label>
				<?=form_dropdown('gangertype', array('combat' => 'Kampf', 'magic' => 'Magie'));?>
				<br />
				<label class="control-label select_width" for="gangerarchtyp" style="width:150px">Ganger Archetyp</label>
				<?=form_dropdown('gangerarchetyp', array('ganger' => 'Ganger', 'lonestar' => 'Lonestar', 'critter' => 'Critter', 'ghost' => 'Geist'));?>
				<br />
				<label class="control-label" for="gangerbio" style="width:150px;" valign="top">Ganger Bio</label>
				<?=form_textarea(array('id' => 'gangerbio', 'name' =>'gangerbio', "class" => "input-xlarge"));?>
				<br />						
			</div>
			<div class="col-sm-6">
				<label class="control-label" for="gangerportrait" style="width:150px">Ganger Portrait</label><br />
				<table><tr>

				<?php for($x=0; $x<count($images);$x++): ?>
					<?php echo ($x%8 == 0) ? '</tr><tr>' : ''; ?>
					<td style="padding-right:5px">
						<img src="/secure/snn/assets/img/combat/ganger/<?=$images[$x];?>" style="height:50px;wight:50px" /><br />
						<?=form_checkbox(array('name' => 'gangerportrait', 'value' => $images[$x]));?>
					</td>

				<?php endfor; ?>
				</tr></table>
			</div>
			<div style="clear:both"></div>
			<div class="col-sm-12">
				<br />	
				<?=form_submit(array('id'=>'submit', 'value' => 'Ganger erstellen', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
			</div>
			<?=form_close();?>			
	</fieldset>
	<br />
	<a href="/secure/snn/admin/overview"><div class="newstitle"><i class="fa fa-arrow-circle-left"></i>&nbsp; zurück</div></a>
	<br />&nbsp;	
</div>