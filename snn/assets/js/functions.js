	function showEinstellungen(id) {
		$('div').each(function(){
			if($(this).attr('data-type') == 'box') {
				$(this).hide('fast');
			}
		});
    $('#'+id).show('fast');
		
	}

$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
        
    });

});

function toggleNews(id) {
	var aid = $('div[id^="news_"]:visible').prop('id');
	if (aid == 'news_'+id) {
		$('#teaser_'+id).show('fast');
		$('#news_'+id).hide('fast');
		$('#link_'+id).html('[ more ]');
	} else {
		$('div[id^="news_"]').each(function(){
			//var fid = $(this).attr('id');
			//if (id != fid) {
				$(this).hide('fast');
			//}			
		});
		$('span[id^="teaser_"]').each(function(){
			$(this).show('fast');
		});		
		$('a[id^="link_"]').each(function(){
			$(this).html('[ more ]');
		});				
		$('#teaser_'+id).toggle('fast');
		$('#news_'+id).toggle('fast');
		$('#link_'+id).html('[ less ]');
	}
}


	$(function() {
		$("#btnForm").fancybox({
			'onStart': function() { $("#divForm").css("display","block"); },            
			'onClosed': function() { $("#divForm").css("display","none"); },
//			'frameWidth':  300,
//        	'frameHeight':  300,
//        	'autoScale': false,
//        	'autoDimensions': false,
		});
	});
	$(function() {
		$("#replyForm").fancybox({
			'onStart': function() { $("#divReply").css("display","block"); },            
			'onClosed': function() { $("#divReply").css("display","none"); }
		});
	});	



	function deleteAvatar(id) {
		$.post(
			'/secure/snn/desktop/deleteAvatar', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#avatarerror').html('<div class="alert alert-error">Beim Löschen des Avatars ist ein Fehler aufgetreten.</div>');
				}
				console.log(json.status);

			});	
		//
	}	
	function addFriend(id) {
		$.post(
			'/secure/snn/desktop/addFriend', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#friendmsg').html('<div class="alert alert-error">Ein Fehler aufgetreten.</div>');
				}
				console.log(json.status);

			});	
		//
	}		
	function removeFriend(id) {
		$.post(
			'/secure/snn/desktop/removeFriend', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#friendmsg').html('<div class="alert alert-error">Ein Fehler aufgetreten.</div>');
				}
				console.log(json.status);

			});	
		//
	}

	function deleteShoutbox(id) {
		$.post(
			'/secure/snn/desktop/deleteShoutbox', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#sberror').html('<div class="alert alert-error">Beim Löschen der Nachricht ist ein Fehler aufgetreten.</div>');
				}
			});	
		//
	}		

	 function checkGangerName(gangername) {
 	$.ajax({
		type: 'POST',
		url: '/secure/snn/admin/checkGangerName', 
		data: {gangername: gangername}, 
		success: function (data) {
			var json = jQuery.parseJSON(data);				
			if (json.status == "false") {
				var html = 'Der Name '+gangername+' ist bereits vergeben, oder zu kurz.';
				$('#gangername_error').html('<div class="errormsg">'+html+'</div><br />');				
				$('#submit').prop('disabled', true)
			} else {
				$('#gangername_error').html('');
				$('#submit').prop('disabled', false)
			}
	}});				
 }

 	function changeDifficulty(level) {
		if (level) {
		$.ajax({
			type: 'POST',
			url: '/secure/snn/combatzone/fetchMissions', 
			data: {level: level}, 
			success: function (data) {
				var json = jQuery.parseJSON(data);
				var html = '<table class="table table-striped table-hover" style="cursor:pointer"><thead><tr><th>Level</th><th>Titel</th><th>Text</th><th>Typ</th><th>Ganger</th><th>Runner</th><th>Gewinn</th><th>Kosten</th><th>NPCs</th></tr></thead><tbody>';							
				
				if (json.status == 'success') {
					$.each(json['data'], function (i, item) {
						html += '<tr onclick="window.location.href=\'/secure/snn/combatzone/combat_mission/'+item.mid+'\';">';
						html += '<td>'+item.level+'</td>';
						html += '<td>'+item.title+'</td>';
						html += '<td>'+item.text+'</td>';
						html += '<td>'+item.type+'</td>';
						html += '<td>'+item.ganger+'</td>';
						html += '<td>'+item.member+'</td>';
						html += '<td>'+item.cash+' &yen;</td>';
						html += '<td>'+item.expense+'</td>';
						html += '<td>'+item.extras+'</td>';							
						html += '</tr>';
					});
					html += '</tbody></table>';
					
				} else {
					html = "Es wurden keine Missionen gefunden";
				}
				
				
				$('#json_receiver').html(html);				
			}});				
		}	
	}

 function checkGangerName(gangername) {
 	$.ajax({
		type: 'POST',
		url: '/secure/snn/admin/checkMissionTitle', 
		data: {gangername: gangername}, 
		success: function (data) {
			var json = jQuery.parseJSON(data);				
			if (json.status == "false") {
				var html = 'Der Titel '+gangername+' ist bereits vergeben, oder zu kurz.';
				$('#missiontitle_error').html('<div class="errormsg">'+html+'</div><br />');				
				$('#submit').prop('disabled', true)
			} else {
				$('#missiontitle_error').html('');
				$('#submit').prop('disabled', false)
			}
	}});				
 }

 function getMission(mid) {
	$.post(
		'/secure/snn/admin/getMission', 
		{mid: mid}, 
		function(data) {
				var json = jQuery.parseJSON(data);
				console.log(json['data']);
				$.each(json['data'], function (i, item) {										
					$('#mid').val(item.mid);
					$('#missionstitle').val(item.title);
					$('#missionlevel').val(item.level);
					$('#missionstext').val(item.text);
					$('#missionswintext').val(item.text_win);
					$('#missionslosstext').val(item.text_loss);
					$('#missionsstorytext').val(item.text_story);										
					$('#missiontype').val(item.type);
					$('#missioncash').val(item.cash);
					$('#missionexpense').val(item.expense);
					$('#missionextras').val(item.extras);
					$('#missionmember').val(item.member);
					var image = item.image;
					var max = (image.length-4);
					var iid = image.substring(0, max);
					$('input[name^="missionsimage"]').prop('checked', false);
					$('#'+iid).prop('checked', true);
					var image = item.johnson;
					var max = (image.length-4);
					var iid = image.substring(0, max);
					$('input[name^="johnson"]').prop('checked', false);
					$('#'+iid).prop('checked', true);
					var image = item.storyimage;
					var max = (image.length-4);
					var iid = image.substring(0, max);
					$('input[name^="storyimage"]').prop('checked', false);
					$('#'+iid).prop('checked', true);										
					var gids = item.gid;
					var a = gids.split(';');
					$('select#missionganger').val('');
					$('select#missionganger').val(a);

				})
		});			
}	

	function deleteNews(id) {
		$.post(
			'/secure/snn/admin/deleteNews', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#newserror').html('<div class="alert alert-error">Beim löschen der Nachricht ist ein Fehler aufgetreten.</div>');
				}
				console.log(json.status);

			});	
		//
	}

	function showMsgHistory(id) {
		$.post(
			'/secure/snn/desktop/showMsgHistory', 
			{id: id}, 
			function(data) {
				var json = jQuery.parseJSON(data);
				if (json.status == 'success') {
					location.reload();			
				} else {
					$('#avatarerror').html('<div class="alert alert-error">Beim Anzeigen der Nachrichten ist ein Fehler aufgetreten.</div>');
				}
				console.log(json.status);

			});	
		//
	}	