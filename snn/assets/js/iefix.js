////////////////////////////////////////////////////////////////////////////////
// START: IE8-, IE9-Placeholder-Support
////////////////////////////////////////////////////////////////////////////////
$(document).ready(function() {
	// scan for every element containing a placeholder attribute
	$('[placeholder]').each(function(index) {
		// equip every object's value width placeholder text
		if ($(this).val() == '') $(this).val( $(this).attr('placeholder'));
      
		// on focusing the element
		$(this).focus(function() {
			if ( $(this).val() == $(this).attr('placeholder') ) $(this).val('');
		});
      
		// on bluring the element
		$(this).blur(function() {
			if ( $(this).val() == '' ) $(this).val( $(this).attr('placeholder'));
		});
 
		// don't submit/send placeholder text
		$(this).parents("form").submit(function () {   
			$(this).find('[placeholder]').each(function() {
				if ( $(this).val() == $(this).attr('placeholder') ) $(this).val('');
			});      
		});
   });
});
