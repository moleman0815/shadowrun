<?php
	$success = $this->session->userdata('success');
	$this->session->unset_userdata('success');
	$error = $this->session->userdata('error');
	$this->session->unset_userdata('error');	
if(!empty($char)) {
	$inv_weapons = explode(';', $inv[0]['wid']);
	$inv_armor = explode(';', $inv[0]['aid']);
}
foreach ($wpnoptions as $w) {
	$wpnType .= '<option value="'.$w['subtype'].'">'.strtoupper($w['subtype']).'</option>'; 
	
}
?>

<script>
	$( document ).ready(function() {
    	<?php if($error): ?>	
    		$("#error").fadeOut(7000);    	
    	<?php endif; ?>
    	<?php if($success): ?>
    		$("#success").fadeOut(7000);    	
    	<?php endif; ?> 
    	$('#total_cost').val(0);
    	$('#total_sell').val(0);    	
		$( "#tabs" ).tabs();
		var last_index = $.cookie("lasttab");
	    $("#tabs").tabs( "option", "active", last_index );

	    var top = $('#buyBasket').offset().top;
	    var navbar = $('.navbar').offset().top;
	    $(window).scroll(function(){
	    	var winscroll = ($(window).scrollTop()+navbar);
	    	 
	    console.log('nav: '+navbar+' -> window: '+winscroll+" -> top: "+top);
	      if(winscroll > top) {
	        $('#buyBasket').addClass('fixedBuy');
	      } else {
	        $('#buyBasket').removeClass('fixedBuy');
	      }
	    });
	});

	

	$(function() {
		$("li").click(function() {
		    var current_index = $("#tabs").tabs("option","active");
		    $.cookie("lasttab", current_index);
		});
	});	

		function calculateCosts(event = "") {
			if (event && typeof event != "undefined") {
				event.preventDefault();
			}
			console.log("in calculate");
			var weapon = $('#weapon_cost').val();
			var armor = $('#armor_cost').val();
			var ammo = $('#ammo_cost').val();
			var med =  $('#medipack_cost').val();

			if (typeof armor != 'undefined') { a = parseInt(armor);} else { a = 0;}
			if (typeof weapon != 'undefined') { w = parseInt(weapon);} else { w = 0;}
			if (typeof ammo != 'undefined') { am = parseInt(ammo);} else { am = 0;}
			if (typeof med != 'undefined') { m = parseInt(med);} else { m = 0;}
			var costs = a+w+am+m;
			$('#total_cost').val(costs);
			if (costs > '<?=$inv[0]['money']?>') {
				$('#total_cost').css('color', 'red');
				$('#cost-warning').html('<span style="color:red;background-color: white">Die Kosten überschreiten dein aktuelles Guthaben.</span>');
				$('#submit').attr('disabled', true);
			} else {
				$('#total_cost').css('color', 'black');				
				$('#cost-warning').html('');				
				$('#submit').attr('disabled', false);				
			}
		}

		function changeWeaponFilter () {
			var filter = $('#wpn_filter :selected').val();
			if (filter != "") {
		 		$('#wpn_list').children().each(function(){
		 			if ($(this).attr("data-subtype") != filter) {
						$(this).hide("fast");
		 			} else {
		 				$(this).show("fast");
		 			}		 			
		 		});	
			} else {
		 		$('#wpn_list').children().each(function(){
			 		$(this).show("fast");		 			
		 		});
			}
		}
		
</script>
<style>

.fixedBuy {
	position: fixed; 
	top:55px;
	right:20px;}

.ui-widget-content {
	background-image: none;
	background-color: #000000;
	
	color: #FFFFFF;
}
.ui-widget-header {
	background-color: black;
	background-color: #000000;
	color: #FFFFFF;	
}

input {
	background-color: #FFFFFF;
}
fieldset {
	border: 1px solid #ffffff;
}
legend {
	color: #FFFFFF;
}
.basket table {
	font-size: 11px;
}

.basket ul {
	font-size:11px;
	color: white;
}

.basket_sell td {
	color: white;
}

.basket_sell table {
	font-size:12px;
	color: white;
}
#product {
	font-size: 12px;
}
</style>

	<fieldset>
		<legend class="newstitle">Marktplatz</legend>	
		<br />
			<?php if($error): ?>
				<div class="alert alert-danger" style="z-index: 100;position: absolute; width:50%;left: 25%;" id="error"><b><i class="fa fa-exclamation-circle"></i>&nbsp;<?=$error?></b></div>
				<br />
			<?php endif; ?>
			<?php if($success): ?>
				<div class="alert alert-success" style="z-index: 100;position: absolute; width:50%;left: 25%;" id="success"><b><i class="fa fa-thumbs-up"></i>&nbsp;<?=$success?></b></div>
				<br />
			<?php endif; ?>			
		<div class="col-sm-12">
	<?php if(!empty($char)): ?>
			<div class="curator">Verfügbares Geld: <?=$inv[0]['money']?> &yen;</div>
			<div class="curator">Medipacks: <?=$inv[0]['medipacks']?></div>
			<div class="curator">Munition: <?=$inv[0]['maxammo']?></div><br />
			<?php if (!empty($inv[0]['weapon'])): ?>
			<div class="curator">Waffen:<br />
				<ul>
				<?php foreach($inv[0]['weapon'] as $w): ?>
					<li><?=$w['name']?></li>
				<?php endforeach; ?>
				</ul>
			 </div>
			<?php endif; ?>
			<?php if (!empty($inv[0]['armor'])): ?>
			<div class="curator">Rüstung: <br />
				<ul>
				<?php foreach($inv[0]['armor'] as $w): ?>
					<li><?=$w['name']?></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
			
		</div>
		<div style="clear:both"></div>
		<br />
		<div id="tabs">
		<ul>
			<li><a href="#buy">Ware kaufen</a></li>
		  	<li><a href="#sell">Ware verkaufen</a></li>
	
		</ul>	
		<div id="buy">		
			<div class="col-md-12" style="color: white;" id="buyBasket">
			<?=form_open_multipart('/combatzone/marketplace');?>
			<?=form_hidden('buyItems', true);?>		
				<div class="basket" id="weapon" style="color: white;float:right">
	            	<div class="basket_list" style="color: white">
						<div class="head" style="color: white">
							<table style="color: white">
								<tr>
		                    		<td style="width:250px"><span>Produkt</span></td>
		                    		<td style="width:70px"><span>Menge</span></td>
		                    		<td style="width:70px"><span>Kosten</span></td>
		                    		<td style="width:20px"></td>
		                    	</tr>
		                    </table>
		                </div>	
	            		<ul id="weapon">
	            		</ul>
	            		<ul id="armor">
	            		</ul>   
	            		<ul id="stuff">
	            		</ul>                		         
	            		<hr />		
	            	<span style="float:right">Total: <input type="text" readonly name="total_cost" id="total_cost" style="width:70px" value="0" /> &yen;   </span>
	            	<br />
	            	<div id="cost-warning"></div>
	            	<br />&nbsp;         		
	            	</div>
	            	<?=form_submit(array('id'=>'submit', 'value' => 'Gegenstände erwerben', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
	            </div>

				<?=form_close();?>	
			</div>
			<br />&nbsp;
			
				<div class="newstitle">Schusswaffen -> Filter: 
					<select name="wpn_filter" id="wpn_filter" onchange="changeWeaponFilter()">
						<option value="">All</option>
						<?=$wpnType?>
					</select>
					<span style="float:right;margin-left:20px;cursor:pointer" id="weapon_span" onclick="toggleMarketBoxes('weapon')"><img src="/secure/snn/assets/img/icons/add.png" />
				</div>
				<br />
				<div style="display: none" id="weapon_box">
		    		<section id="product">
			        	<ul class="clear" id="wpn_list">
			        	<?php if(!empty($weapons[0])): ?>
			        		<?php $i=1; foreach($weapons as $w): ?>
			        			<?php if(in_array($w['wid'], $inv_weapons)) {continue;} ?>
			        			<?php 
			        				$tooltip = "";
			        				$tooltip = "<div>";
			        				$tooltip .= $w['name']."<br />";
			        				$tooltip .= "Ammo: ".$w['ammo']."<br />";
			            			$tooltip .= "Damage: ".$w['damage']."<br />";
			            			$tooltip .= "Modus: ".$w['mode']."<br />";
			            			$tooltip .= "Typ: ".strtoupper($w['subtype'])."<br />";
			            			$tooltip .= "Rückstoß: -".$w['reduce']."<br /><br />";
			        				$tooltip .= "<div>";
			        			?>
			            		<li data-id="<?=$w['wid']?>" data-type="weapon" data-subtype="<?=$w['subtype']?>" data-cost="<?=$w['cost'];?>" onmouseover="Tip('<?=$tooltip?>')" onmouseout="UnTip()" style="z-index:1000">
			            			<h3 style="font-weight:bold;font-size:12px"><?=$w['name'];?></h3>
			      					Kosten: <b><?=$w['cost'];?> &yen;</b><br />
			      					Typ: <b><?=$w['subtype']?></b>
			            		</li>
			            		<?php if ($i%5 == 0): ?>
			            			</ul><ul class="clear">
			            		<?php endif; ?>
			            		<?php $i++; ?>
			        		<?php endforeach; ?>
			        	<?php endif; ?>
			        	</ul>
		        	</section>
		        	<br />
		        </div>
		        <div class="col-md-12">
				<div class="newstitle">Nahkampfwaffen
					<span style="float:right;margin-left:20px;cursor:pointer" id="melee_span" onclick="toggleMarketBoxes('melee')"><img src="/secure/snn/assets/img/icons/add.png" /></span>
				</div>
				<br />
				<div style="display: none" id="melee_box">
		    		<section id="product">
			        	<ul class="clear" id="wpn_list">
			        	<?php if(!empty($melee[0])): ?>
			        		<?php $i=1; foreach($melee as $w): ?>
			        			<?php if(in_array($w['wid'], $inv_weapons)) {continue;} ?>
			        			<?php 
			        				$tooltip = "";
			        				$tooltip = "<div>";
			        				$tooltip .= $w['name']."<br />";
			            			$tooltip .= "Damage: Str+".$w['damage']."<br />";
			            			$tooltip .= "Reichweite: ".$w['reach']."<br />";
			        				$tooltip .= "<div>";
			        			?>
			            		<li data-id="<?=$w['wid']?>" data-type="weapon" data-subtype="<?=$w['subtype']?>" data-cost="<?=$w['cost'];?>" onmouseover="Tip('<?=$tooltip?>')" onmouseout="UnTip()" style="z-index:1000">
			            			<h3 style="font-weight:bold;font-size:14px"><?=$w['name'];?></h3>
			      					Kosten: <b><?=$w['cost'];?> &yen;</b>
			      					
			            		</li>
			            		<?php if ($i%5 == 0): ?>
			            			</ul><ul class="clear">
			            		<?php endif; ?>
			            		<?php $i++; ?>
			        		<?php endforeach; ?>
			        	<?php endif; ?>
			        	</ul>
		        	</section>
		        	<br />
		        </div>
				<div class="newstitle">Rüstung<span style="float:right;margin-left:20px;cursor:pointer" id="armor_span" onclick="toggleMarketBoxes('armor')"><img src="/secure/snn/assets/img/icons/add.png" /></div>
				<br />
				<div style="display: none" id="armor_box">			
		    		<section id="product">
		        	<ul class="clear">
		        		<?php if(!empty($armor[0])): ?>
			        		<?php foreach($armor as $w): ?>
			        			<?php if(in_array($w['wid'], $inv_armor)) {continue;} ?>
			            		<li data-id="<?=$w['wid']?>" data-type="armor" data-cost="<?=$w['cost'];?>">
			            			<h3 style="font-weight:bold;font-size:14px"><?=$w['name'];?></h3>
			            			Schutz: <?=$w['armor'];?><br />
									Kosten: <b><?=$w['cost'];?> &yen;</b>
			            		</li>
			        		<?php endforeach; ?>
			        	<?php endif; ?>
		        	</ul>
		        	</section>
		        </div>
				<div class="newstitle">Stuff<span style="float:right;margin-left:20px;cursor:pointer" id="stuff_span" onclick="toggleMarketBoxes('stuff')"><img src="/secure/snn/assets/img/icons/add.png" /></div>
				<br />
				<div style="display: none" id="stuff_box">			
		    		<section id="product">
		        	<ul class="clear">
						<li data-id="medipack" data-type="stuff" data-cost="200">
	            			<h3 style="font-weight:bold;font-size:14px">Medikit</h3>
	            			Heilt: +3 HP<br />
							Kosten: <b>200 &yen;</b>
						</li>
						<li data-id="ammo" data-type="stuff" data-cost="20">
	            			<h3 style="font-weight:bold;font-size:14px">Munition</h3>
	            			Menge: 10 Schuß<br />
							Kosten: <b>20 &yen;</b>
						</li>					
		        	</ul>
		        	</section>
				</div>	        
			</div>
			
			<div style="clear:both"></div>
		</div>
		<div id="sell">
		<div class="col-sm-6">			
			<br />
			<div style="display: block" id="weapon_sell_box">
	    		<section id="product">
	        		<ul class="clear">
	        		<?php if(!empty($inv[0]['weapon'])): ?>
		        		<?php foreach($inv[0]['weapon'] as $w): ?>
		            		<li data-id="<?=$w['wid']?>" data-type="weapon" data-cost="<?=($w['cost']/2);?>"  id="item_<?=$w['wid']?>">
		            			<h3 style="font-weight:bold;font-size:14px"><?=$w['name'];?></h3><br />
		            			Ammo: <?=$w['ammo'];?><br />
		            			Damage: <?=$w['damage'];?><br />
		            			Modus: <?=$w['mode'];?><br />
		            			Rückstoß: -<?=$w['reduce'];?><br /><br />
								Kosten: <b><?=($w['cost']/2);?> &yen;</b><br />&nbsp;
		            		</li>
		        		<?php endforeach; ?>
		        	<?php endif; ?>
	        		</ul>
	        	</section>
	        	<br />
	        </div>
			<div style="display: block" id="armor_sell_box">			
	    		<section id="product">
	        	<ul class="clear">
	        	<?php if(!empty($inv[0]['armor'])): ?>
	        		<?php foreach($inv[0]['armor'] as $w): ?>
	            		<li data-id="<?=$w['wid']?>" data-type="armor" data-cost="<?=($w['cost']/2);?>"  id="item_<?=$w['wid']?>">
	            			<h3 style="font-weight:bold;font-size:14px"><?=$w['name'];?></h3><br />
	            			Schutz: <?=$w['armor'];?><br /><br />
							Kosten: <b><?=($w['cost']/2);?> &yen;</b><br />&nbsp;
	            		</li>
	        		<?php endforeach; ?>
	        	<?php endif; ?>
	        	</ul>
	        	</section>
	        </div>	        
		</div>
			<?=form_open_multipart('/combatzone/marketplace');?>
			<?=form_hidden('sellItems', true);?>		
			<div class="col-sm-6" style="color: white">
				<div class="newstitle">Verkaufen</div>	
				<br />
				<div class="basket_sell" id="weapon" style="color: white">
	            	<div class="basket_list" style="color: white">
						<div class="head" style="color: white">
							<table style="color: white">
								<tr>
		                    		<td style="width:250px"><span>Produkt</span></td>
		                    		<td style="width:70px"><span>Menge</span></td>
		                    		<td style="width:70px"><span>Kosten</span></td>
		                    		<td style="width:20px"></td>
		                    	</tr>
		                    </table>
		                </div>	
	            		<ul id="sell"></ul>

	            		<hr />		
	            	<span style="float:right">Total: <input type="text" readonly name="total_sell" id="total_sell" style="width:70px" value="0" /> &yen;   </span>
	            	<br />
	            	<div id="cost-warning"></div>
	            	<br />&nbsp;         		
	            	</div>
	            	<br />
	            	<?=form_submit(array('id'=>'submit', 'value' => 'Gegenstände verkaufen', 'name' => 'submit', 'class' => 'btn btn-primary btn-sm'));?>
	            </div>

				<?=form_close();?>	
			</div>
			<div style="clear:both"></div>		
		</div>
	<?php else: ?>
	<br />
		<div class="errormsg">
			Um Einkaufen zu können, musst du erst deinen Charakter hinterlegen.<br />
			<a href="/secure/snn/desktop/einstellungen/">HIER</a> gehts lang ....
		</div>
		<br />
	<?php endif; ?>			
	</div>		
	</fieldset>
	<div style="clear:both"></div>
	<br />
