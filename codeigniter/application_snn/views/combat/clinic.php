<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');	

	$inv_cyberware = explode(';', $inv[0]['cyberid']);	
?>
<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?>    	
    	$('#total_cyber_cost').val(0);      
    	$('#essenz').val('<?=$char[0]['essence']?>');
	});


		function calculateCyberwareCosts(type, cash, essence) {
			console.log(essence);
			essence = essence.replace(/,/g, ".");
			if (type == 'add') {
				costs = parseInt($('#total_cyber_cost').val())+parseInt(cash);
				essences = parseFloat($('#essenz').val())-parseFloat(essence);
			} else {
				costs = parseInt($('#total_cyber_cost').val())-parseInt(cash);
				essences = parseFloat($('#essenz').val())+parseFloat(essence);
			}

			$('#total_cyber_cost').val(costs);
			$('#essenz').val(essences);			
			if (costs > '<?=$inv[0]['money']?>') {
				$('#total_cyber_cost').css('color', 'red');
				$('#cost-cyber-warning').html('<span style="color:red;background-color: white">Die Kosten überschreiten dein aktuelles Guthaben.</span>');
				$('#submit').attr('disabled', true);
			} else {
				$('#total_cyber_cost').css('color', 'black');				
				$('#cost-cyber-warning').html('');				
				$('#submit').attr('disabled', false);				
			}
			if (essences <= 0) {
				$('#essenz').css('color', 'red');
				$('#essence-cyber-warning').html('<span style="color:red;background-color: white">Deine Essenz darf nicht unter Null liegen.</span>');
				$('#submit').attr('disabled', true);
			} else {
				$('#essenz').css('color', 'black');				
				$('#essence-cyber-warning').html('');				
				$('#submit').attr('disabled', false);								
			}
		}	
</script>
<style>

ul {
	font-size:12px;
}

</style>
	<br />
	<fieldset>
		<legend class="newstitle"><i class="fa fa-hospital-o fa-2x" style="margin-right:20px"></i>&nbsp;Schattenklinik</legend>	
		<br />
	<?php if(!empty($char)): ?>		
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left: 25%;" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
				<br />
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left: 25%;" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
				<br />
			<?php endif; ?>			
		<div class="col-sm-12">

			<div class="col-sm-3">
				<img src="/secure/snn/assets/img/layout/mensch.png" />
			</div>
			<div class="col-sm-3" style="margin-left:20px">
				<div class="curator">Verfügbares Geld: <?=$inv[0]['money']?> &yen;</div><br /><br />
				<div class="curator">Essenz: <?=$char[0]['essence']?></div><br />
			</div>
			<div class="col-sm-3" style="margin-left:20px">
				<div class="curator">Cyberware:<br />
					<?php if(!empty($inv[0]['cyberware'])): ?>					
					<ul>
					<?php foreach($inv[0]['cyberware'] as $w): ?>
						<li><?=$w['name']?></li>
					<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				</div>				
			</div>
		</div>
		<div style="clear:both"></div>
		<br />
		<div class="col-sm-12" style="background-color:black; padding: 10px">
		<div class="col-sm-12">
			<div class="newstitle">Cyberware</div>
			<br />
			<div class="col-sm-6">
				<br />
	    		<section id="product">
	        	<ul class="clear">
	        		<?php foreach($cyberware as $w): ?>
	        			<?php if(in_array($w['wid'], $inv_cyberware)) {continue;} ?>
	            		<li data-id="<?=$w['wid']?>" data-type="cyberware" data-cost="<?=$w['cost'];?>"  id="item_<?=$w['wid']?>" data-essence="<?=$w['cyberware_essence'];?>" >
	            			<h3 style="font-weight:bold;font-size:14px"><?=$w['name'];?></h3>
	            			Type: <?=ucfirst($w['cyberware_type']);?><br />
	            			Ini: + <?=$w['cyberware_ini'];?>D6<br />
	            			Reaktion: + <?=$w['cyberware_reaction'];?><br />
	            			Rüstung: + <?=$w['cyberware_armor'];?><br />
							Mindeswurf: + <?=$w['cyberware_mw'];?><br />
							Essenz: - <?=$w['cyberware_essence'];?><br />
							Kosten: <b><?=$w['cost'];?> &yen;</b>
							<br />&nbsp;
	            		</li>
	        		<?php endforeach; ?>
	        	</ul>
	        	</section>
	        	<br />
		    </div>	
			<?=form_open_multipart('/combatzone/clinic');?>
			<?=form_hidden('buyCyberware', true);?>		
			<div class="col-sm-6" style="color: white">
				<div class="newstitle">Warenkorb</div>	
				<br />
				<div class="basket_cyberware" id="cyberware" style="color: white">
	            	<div class="basket_list" style="color: white">
						<div class="head" style="color: white">
							<table style="color: white">
								<tr>
		                    		<td style="width:250px"><span>Produkt</span></td>
		                    		<td style="width:70px"><span>Kosten</span></td>
		                    		<td style="width:20px"></td>
		                    	</tr>
		                    </table>
		                </div>	
	            		<ul id="cyberware"></ul>             		         
	            		<hr />		

	            	<span style="float:right">Total: <input type="text" readonly name="total_cyber_cost" id="total_cyber_cost" style="width:70px" value="0" /> &yen;   </span>
	            	<div style="clear:both"></div>
	            	<br />
	            	<span style="float:right">Essenz: <input type="text" readonly name="essenz" id="essenz" style="width:70px" value="0" /> &nbsp;  </span>
	            	<br />
	            	<div id="cost-cyber-warning"></div>
	            	<div id="essence-cyber-warning"></div>
	            	<br />&nbsp;         		
	            	</div>
	            	<br />
	            	<?=form_submit(array('id'=>'submit', 'value' => 'Cyberware erwerben', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
	            </div>

				<?=form_close();?>	
			</div>		    		
		</div>	
		</div>	
	<?php else: ?>
	<br />
		<div class="errormsg">
			Um Cyberware kaufen zu können, musst du erst deinen Charakter hinterlegen.<br />
			<a href="/secure/snn/desktop/einstellungen/">HIER</a> gehts lang ....
		</div>
		<br />
	<?php endif; ?>		
	</fieldset>	
	<div style="clear:both"></div>
	