var sr;
if (!sr) {
	sr = {};
}

$.extend (sr,{
	combat: {
		init : function () {
			console.log("combat initiated");
		},
		initiateCombat : function () {
			console.log('startup');
			$.post(
				'/secure/snn/combatzone/initiateCombat',
				function(data) {
					console.log("data "+data);
				}
					
			);
		},
		proceedAction : function () {
    		var action = $('input[id="action"]:checked').val();
    		var round = $('#round').val();
			console.log("action: "+action+" round: "+round);
		},
		testme : function () {
			console.log("in testme");
		}
	}		
});


sr.combat.init();