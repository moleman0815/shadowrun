var sr;
if (!sr) {
	sr = {};
}

$.extend (sr,{
	combat: {
		init : function () {
			console.log("combat initiated");
		},
		testme : function () {
			console.log("in testme");
		}
	}		
});


sr.combat.init();