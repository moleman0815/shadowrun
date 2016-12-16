$(document).ready(function () {
        $('#total_cost').val('0');
    });
    $(function () {
		$("#product li").draggable({
			revert:true,
			
			drag:function () {
				$(this).addClass("active");
				$(this).closest("#product").addClass("active");
			},
			stop:function () {
				$(this).removeClass("active").closest("#product").removeClass("active");
			}
		});

		$(".basket").droppable({
			activeClass:"active",
			hoverClass:"hover",
			tolerance:"touch",
			drop:function (event, ui) {
		
				var basket = $(this),
						move = ui.draggable,
						itemId = basket.find("ul li[data-id='" + move.attr("data-id") + "']");

						itemType = move.attr("data-type");

				// To increase the value by +1 if the same item is already in the basket
				if (itemId.html() != null && itemType == 'stuff') {
					$('#' + move.attr("data-id") + '_amount').val(parseInt($('#' + move.attr("data-id") + '_amount').val())+1);
					$('#' + move.attr("data-id") + '_cost').val(parseInt($('#' + move.attr("data-id") + '_cost').val())+parseInt(move.attr("data-cost")));
					calculateCosts(move);					
					//itemId.find("input").val(parseInt(itemId.find("input").val()) + 1);
				} else {
					// Add the dragged item to the basket
					if (move.attr("data-type") == 'weapon') {
						addBasketWeapon(basket, move);
					} else if (move.attr("data-type") == 'stuff') {
						addBasketStuff(basket, move);
					} else {
						addBasketArmor(basket, move);
					}
		
					// Updating the quantity by +1" rather than adding it to the basket
					move.find("input").val(parseInt(move.find("input").val()) + 1);
				}
			}
		});

		$(".basket_sell").droppable({
		
			// The class that will be appended to the to-be-dropped-element (basket)
			activeClass:"active",
		
			// The class that will be appended once we are hovering the to-be-dropped-element (basket)
			hoverClass:"hover",
		
			// The acceptance of the item once it touches the to-be-dropped-element basket
			// For different values http://api.jqueryui.com/droppable/#option-tolerance
			tolerance:"touch",
			drop:function (event, ui) {		
				var basket = $(this),
						move = ui.draggable,
						itemId = basket.find("ul li[data-id='" + move.attr("data-id") + "']");
						itemType = move.attr("data-type");
				addBasket(basket, move);		
			}
		});


		$(".basket_cyberware").droppable({
		
			// The class that will be appended to the to-be-dropped-element (basket)
			activeClass:"active",
		
			// The class that will be appended once we are hovering the to-be-dropped-element (basket)
			hoverClass:"hover",
		
			// The acceptance of the item once it touches the to-be-dropped-element basket
			// For different values http://api.jqueryui.com/droppable/#option-tolerance
			tolerance:"touch",
			drop:function (event, ui) {		
				var basket = $(this),
						move = ui.draggable,
						itemId = basket.find("ul li[data-id='" + move.attr("data-id") + "']");
						itemType = move.attr("data-type");
				addCyberBasket(basket, move);		
			}
		});		

        function addCyberBasket(basket, move) {			
			basket.find("ul[id^='cyberware']").append('<li data-essence="' + move.attr("data-essence") + '" data-id="' + move.attr("data-id") + '" data-type="' + move.attr("data-type") + '" data-cost="' + move.attr("data-cost") + '">'
					+ '<table class="table"><tr>'
					+ '<input type="hidden" name="' + move.attr("data-type") + '[]" value="' + move.attr("data-id") + '" />'
					+ '<td style="width:220px"><span class="name">' + move.find("h3").html() + '</span></td>'					
					+ '<td style="width:70px"><input type="text" id="' + move.attr("data-id") + '_sell" readonly value="' + move.attr("data-cost") + '" style="width:50px" class="count"  /></td>'
					+ '<td style="width:20px"><button class="delete">&#10005;</button></td>'
					+ '</tr></table></li>'
				);					
			calculateCyberwareCosts('add', move.attr("data-cost"), move.attr("data-essence"));
			move.hide();
		}

        function addBasket(basket, move) {			
			basket.find("ul[id^='sell']").append('<li data-id="' + move.attr("data-id") + '" data-type="' + move.attr("data-type") + '" data-cost="' + move.attr("data-cost") + '">'
					+ '<table class="table"><tr>'
					+ '<input type="hidden" name="' + move.attr("data-type") + '[]" value="' + move.attr("data-id") + '" />'
					+ '<td style="width:220px"><span class="name">' + move.find("h3").html() + '</span></td>'					
					+ '<td style="width:70px"><input type="text" id="' + move.attr("data-id") + '_sell" readonly value="' + move.attr("data-cost") + '" style="width:50px" class="count"  /></td>'
					+ '<td style="width:20px"><button class="delete">&#10005;</button></td>'
					+ '</tr></table></li>'
				);					
			calculatePrice('add', move.attr("data-cost"));
			move.hide();
		}

        function addBasketStuff(basket, move) {			
			basket.find("ul[id^='stuff']").append('<li data-id="' + move.attr("data-id") + '" data-type="' + move.attr("data-type") + '" data-cost="' + move.attr("data-cost") + '">'
					+ '<table class="table"><tr>'
					+ '<td style="width:220px"><span class="name">' + move.find("h3").html() + '</span></td>'					
					+ '<td style="width:70px"><input type="text" name="' + move.attr("data-id") + '" id="' + move.attr("data-id") + '_amount" readonly value="1" style="width:30px" class="count" /></td>'					
					+ '<td style="width:70px"><input type="text" id="' + move.attr("data-id") + '_cost" readonly value="' + move.attr("data-cost") + '" style="width:50px" class="count"  /></td>'
					+ '<td style="width:20px"><button class="delete">&#10005;</button></td>'
					+ '<td style="width:20px"><button class="minus">-</button></td>'
					+ '</tr></table></li>'
				);					
			calculateCosts(move);
		}

		function calculatePrice(type, cash) {	
			if (type == 'add') {
				var costs = parseInt($('#total_sell').val())+parseInt(cash);
			} else {
				var costs = parseInt($('#total_sell').val())-parseInt(cash);
			}

			$('#total_sell').val(costs);
			$('#total_sell').css('color', 'black');				
			$('#submit').attr('disabled', false);				
		}		


        // The function that is triggered once delete button is pressed
        $(".basket_sell ul li button.delete").live("click", function () {

			var price = $(this).closest("li").attr("data-cost");
			var id = $(this).closest("li").attr("data-id");
			$('#item_'+id).show();
			console.log(id);
			$(this).closest("li").remove();
			calculatePrice('sub', price);			
		});

        // The function that is triggered once delete button is pressed
        $(".basket_cyberware ul li button.delete").live("click", function () {

			var price = $(this).closest("li").attr("data-cost");
			var id = $(this).closest("li").attr("data-id");
			var essence = $(this).closest("li").attr("data-essence");
			$('#item_'+id).show();		
			$(this).closest("li").remove();
			calculateCyberwareCosts('sub', price, essence);			
		});	

        // The function that is triggered once delete button is pressed
        $(".basket ul li button.delete").live("click", function () {
			$(this).closest("li").remove();
			calculateCosts();			
		});

        // The function that is triggered once delete button is pressed
        $(".basket ul li button.minus").live("click", function () {
			var id = $(this).closest("li").attr('data-id');
			var cost = $(this).closest("li").attr('data-cost');
			if ($('#'+id+'_amount').val() < '2') {
				$(this).closest("li").remove();
			} else {
				$('#'+id+'_amount').val(parseInt($('#'+id+'_amount').val())-1);
				$('#'+id+'_cost').val(parseInt($('#'+id+'_cost').val())-parseInt(cost));
			}
			console.log(cost);
			calculateCosts();			
		});
    });

function toggleMarketBoxes(id) {
	if ($('#'+id+'_box').is(":visible")) {
		$('#'+id+'_box').hide('fast');
		$('#'+id+'_span').html('<img src="/secure/snn/assets/img/icons/add.png" />');
	} else {
		$('#'+id+'_box').show('fast');
		$('#'+id+'_span').html('<img src="/secure/snn/assets/img/icons/minus.png" />');		
	}
}


        // This function runs onc ean item is added to the basket
        function addBasketWeapon(basket, move) {
			$(".basket ul[id^='weapon'] li").remove();
			basket.find("ul[id^='weapon']").append('<li data-id="' + move.attr("data-id") + '" data-type="' + move.attr("data-type") + '">'
					+ '<table class="table"><tr>'
					+ '<input type="hidden" name="weapon_id" value="' + move.attr("data-id") + '" />'
					+ '<td style="width:250px"><span class="name">' + move.find("h3").html() + '</span></td>'					
					+ '<td style="width:70px"><input type="text" readonly value="1" style="width:30px" class="count" /></td>'					
					+ '<td style="width:70px"><input type="text" id="weapon_cost" readonly value="' + move.attr("data-cost") + '" style="width:50px" class="count"  /></td>'
					+ '<td style="width:20px"><button class="delete">&#10005;</button></td>'
					+ '</tr></table></li>'
				);					
			calculateCosts(move);
		}
        function addBasketArmor(basket, move) {
			$(".basket ul[id^='armor'] li").remove();

			basket.find("ul[id^='armor']").append('<li data-id="' + move.attr("data-id") + '" data-type="' + move.attr("data-type") + '">'
					+ '<table class="table"><tr>'
					+ '<input type="hidden" name="armor_id" value="' + move.attr("data-id") + '" />'					
					+ '<td style="width:250px"><span class="name">' + move.find("h3").html() + '</span></td>'					
					+ '<td style="width:70px"><input type="text" readonly value="1" style="width:30px" class="count" /></td>'					
					+ '<td style="width:70px"><input type="text" id="armor_cost" readonly value="' + move.attr("data-cost") + '" style="width:50px" class="count"  /></td>'
					+ '<td style="width:20px"><button class="delete">&#10005;</button></td>'
					+ '</tr></table></li>'
				);

			calculateCosts(move);
		}	