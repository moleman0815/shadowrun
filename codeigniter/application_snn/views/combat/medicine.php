<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');	

	$inv_spells = explode(';', $inv[0]['zid']);	
	
#	_debug($inv[0]['spells']);
?>
<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?>    	
    	$('#total_spell_cost').val(0); 

    	var top = $('#spells').offset().top;
	    var navbar = $('.navbar').offset().top;
	    $(window).scroll(function(){
	    	var winscroll = ($(window).scrollTop()+navbar);
	    	 
	    console.log('nav: '+navbar+' -> window: '+winscroll+" -> top: "+top);
	      if(winscroll > top) {
	        $('#spells').addClass('fixedBuy');
	      } else {
	        $('#spells').removeClass('fixedBuy');
	      }
	    });
	});


		function calculateSpellCosts(type, cash) {
			if (type == 'add') {
				costs = parseInt($('#total_spell_cost').val())+parseInt(cash);
			} else {
				costs = parseInt($('#total_spell_cost').val())-parseInt(cash);
			}

			$('#total_spell_cost').val(costs);
		
			if (costs > '<?=$inv[0]['money']?>') {
				$('#total_spell_cost').css('color', 'red');
				$('#cost-spell-warning').html('<span style="color:red;background-color: white">Die Kosten überschreiten dein aktuelles Guthaben.</span>');
				$('#submit').attr('disabled', true);
			} else {
				$('#total_spell_cost').css('color', 'black');				
				$('#cost-spell-warning').html('');				
				$('#submit').attr('disabled', false);				
			}
		}	
</script>
<style>

.fixedBuy {
	position: fixed; 
	top:75px;
	right:0px;}


ul {
	font-size:12px;
}

</style>
	<br />
	<fieldset>
		<legend class="newstitle"><i class="fa fa-hospital-o fa-2x" style="margin-right:20px"></i>&nbsp;Medizinh&uuml;tte</legend>	
		<br />
	<?php if(!empty($char)): ?>		
	<?php if($char[0]['magic'] > 0): ?>
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left: 25%;" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
				<br />
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left: 25%;" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
				<br />
			<?php endif; ?>			
		<div class="col-sm-12">

			<div class="col-sm-4">
				<img src="/secure/snn/assets/img/layout/magic.jpg" style="width:250px;heigth:265px" />
			</div>
			<div class="col-sm-3" style="margin-left:20px">
				<div class="curator">Verfügbares Geld: <?=$inv[0]['money']?> &yen;</div><br /><br />
				<div class="curator">Essenz: <?=$char[0]['essence']?></div><br />
			</div>
			<div class="col-sm-3" style="margin-left:20px">
				<div class="curator">Zauber:<br />
					<?php if(!empty($inv[0]['spells'])): ?>					
					<ul>
					<?php foreach($inv[0]['spells'] as $w): ?>
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
			<div class="newstitle">Zauber</div>
			<br />
			<div class="col-sm-6">
				<br />
	    		<section id="product">
	        	<ul class="clear">
	        		<?php $x=0; foreach($cyberware as $w): ?>
	        		<?php #_debug($w); ?>
	        			<?php if(in_array($w['zid'], $inv_spells)) {continue;} ?>
	        			<?php if($x%3 == 0) { echo "</ul><ul>"; }?>
	        			<?php 
	        				$subtype = ($w['subtype'] == 'm') ? 'mental' : 'physisch';
	        				$tooltip = "";
	        				$tooltip = "<div>";
	        				$tooltip .= "Subtype: ".$subtype;
	        				$tooltip .= "<br />";
        					$tooltip .= "Entzug: "._generateReadableEntzug($w['entzug'])."<br />";
        					$tooltip .= "Wirkung: "._generateReadableSchaden($w['wirkung'], $w['typ'])."<br />";
	        				$tooltip .= "<div>";
	        			?>
	            		<li data-id="<?=$w['zid']?>" data-type="spells" data-cost="<?=$w['cost'];?>"  id="item_<?=$w['zid']?>" onmouseover="Tip('<?=$tooltip?>')" onmouseout="UnTip()">
	            			<h3 style="font-weight:bold;font-size:14px"><?=$w['name'];?></h3>
	            			Kosten: <b><?=$w['cost'];?> &yen;</b><br />
	            			Typ:  <?=ucfirst($w['typ']);?>																			
	            		</li>
	            		<?php $x++;?>
	        		<?php endforeach; ?>
	        	</ul>
	        	</section>
	        	<br />
		    </div>	
			<?=form_open_multipart('/combatzone/medicine');?>
			<?=form_hidden('buySpells', true);?>		
			<div class="col-sm-6" style="color: white">
				<div class="basket_spells" id="spells" style="color: white">
	            	<div class="basket_list" style="color: white">
						<div class="head" style="color: white">
							<table style="color: white">
								<tr>
		                    		<td style="width:250px"><span>Zauber</span></td>
		                    		<td style="width:70px"><span>Kosten</span></td>
		                    		<td style="width:20px"></td>
		                    	</tr>
		                    </table>
		                </div>	
	            		<ul id="spells"></ul>             		         
	            		<hr />		

	            	<span style="float:right">Total: <input type="text" readonly name="total_spell_cost" id="total_spell_cost" style="width:70px" value="0" /> &yen;   </span>
	            	<div style="clear:both"></div>	            	
	            	<br />
	            	<div id="cost-spell-warning"></div>
	            	<br />&nbsp;         		
	            	</div>
	            	<br />
	            	<?=form_submit(array('id'=>'submit', 'value' => 'Zauber erwerben', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
	            </div>

				<?=form_close();?>	
			</div>		    		
		</div>	
		</div>
	<?php else: ?>
	<br />
		<div class="errormsg">
			Der Schamane wei&szlig;t dich ab, da dein Charakter nicht magisch begabt zu sein scheint.
		</div>
	<?php endif; ?>	
	<?php else: ?>
	<br />
		<div class="errormsg">
			Um Zauber kaufen zu können, musst du erst deinen Charakter hinterlegen.<br />
			<a href="/secure/snn/desktop/einstellungen/">HIER</a> gehts lang ....
		</div>
		<br />
	<?php endif; ?>		
	</fieldset>	
	<div style="clear:both"></div>
	