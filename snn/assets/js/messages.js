var sr;
if (!sr) {
	sr = {};
}

var newMessageBtn;
var newMessageModal;
var newMessageClose;
var replyMessageModal;
var replyMessageClose;
var newCommentModal;
var newCommentClose;

$( document ).ready(function() { 	
	$('#sendMsgError').hide();
	$('#sendMsgSuccess').hide();
	sr.messages.getNewMsgHeader();
});

$.extend (sr,{
	messages: {
		init : function () {
			console.log("messages initiated");
		},
		toggleComment : function (nid) {
			$('#newsid').val(nid);
			$('#comment').val('');
			newCommentModal.style.display = "block";
		},
		toggleNewsComment : function (nid) {
			var status = $('#commentbox_'+nid).css('display');
			if (status == 'none') {
				$('#commentbox_'+nid).show();
			} else {
				$('#commentbox_'+nid).hide();
			}
		},
		sendNewComment : function () {
			newCommentModal.style.display = "none";
			$.ajax({
				url: '/secure/snn/desktop/sendNewComment',
				type: 'POST',
				data: $('#writeComment').serialize(),
				success : function (data) {
					var json = jQuery.parseJSON(data);
					if (json['status'] == 'error') {
						$('#sendMsgError').show("fast").html(json['msg']);
					} else {
						$('#sendMsgSuccess').show("fast").html(json['msg']);
					}
					location.reload().delay(1500);
				}
			});
		},
		deleteComment : function (cid) {
			$.ajax({
				url: '/secure/snn/desktop/deleteComment',
				type: 'POST',
				data: {cid: cid},
				success : function (data) {
					location.reload();
				}
			});
		},
		toggleSystemNews : function (id) {
			$.ajax({
				url: '/secure/snn/admin/toggleSystemNews',
				type: 'POST',
				data: {id: id},
				success : function (data) {
					location.reload();
				}
			});
		},
		sendNewMessage : function () {
			newMessageModal.style.display = "none";
			replyMessageModal.style.display = "none";			
			//console.log($('#writeMessage').serialize());
			$.ajax({
				url: '/secure/snn/desktop/sendMessage',
				type: 'POST',
				data: $('#writeMessage').serialize(),
				success: function(data) {
					var json = jQuery.parseJSON(data);
					if (json['status'] == 'error') {
						$('#sendMsgError').show("fast").html(json['msg']);
					} else {
						$('#sendMsgSuccess').show("fast").html(json['msg']);
					}
				}
         	});
		},
		sendReplyMessage : function () {
			newMessageModal.style.display = "none";
			replyMessageModal.style.display = "none";
			$.ajax({
				url: '/secure/snn/desktop/sendMessage',
				type: 'POST',
				data: $('#replyMessage').serialize(),
				success: function(data) {
					var json = jQuery.parseJSON(data);
					if (json['status'] == 'error') {
						$('#sendMsgError').show("fast").html(json['msg']);
					} else {
						$('#sendMsgSuccess').show("fast").html(json['msg']);
					}
				}
         	});
		},
		replyMessage : function (id, senderid) {
			replyMessageModal.style.display = "block";
			$.ajax({
				url: '/secure/snn/desktop/replyMsg',
				type: 'POST',
				data: {id: id},
				success: function(data) {
					var json = jQuery.parseJSON(data);
					console.log("", json);
					$('#replytitle').val('Re: '+json.title);
					$('#receiverid').val(json.receiver_id);
					$('#replyreceiver').val(json.receiver);
					$('#reply_text').val(json.msg);
					$('#senderid').val(json.sender_id);
					$('#replyForm').click();
				}
			});
		},
		deleteMsg : function (id) {
			$.post('/secure/snn/desktop/deleteMsg', {id: id}, function(data)
					{
						location.reload();
				});
		},	
		toggleMsg : function (id) {
			$('div[id^="msg"]').each(function(){
				var fid = $(this).attr('id');
				if (id != fid) {
					$(this).hide('fast');
				}			
			});
			$('#msg_'+id).toggle('fast');
			$('#new_'+id).html("");
			this.updateNewMsg(id);
		},
		toggleMsgOwn : function (id) {
			$('div[id^="msg"]').each(function(){
				var fid = $(this).attr('id');
				if (id != fid) {
					$(this).hide('fast');
				}			
			});
			$('#msg_'+id).toggle('fast');
			$('#new_'+id).html("");
		},
		updateNewMsg : function (id) {
			var self = this;
			$.ajax({
				url: '/secure/snn/desktop/updateNewMessage',
				type: "POST",
				data: {'id': id},
				success: function (data) {
					self.getNewMsgHeader();
				}
			});
			
		},
		getNewMsgHeader : function () {
			$.ajax({
				url: '/secure/snn/desktop/getNewMsgHeader',
				success: function (data) {
					var json = jQuery.parseJSON(data);
					if (json['msgNo'] > 0) {
						$('#newMessagesHeader').html(" ("+json['msgNo']+")");
					} else {
						$('#newMessagesHeader').html("");
					}
				}
			});
		},
		deleteFeedback : function (id) {
			$.post('/secure/snn/desktop/deleteFeedback', {fid: id}, function(data)
					{
						location.reload();
				});
		},	
		editFeedback : function (id) {
			$.ajax({
				url: '/secure/snn/desktop/receiveFeedback',
				type: 'POST',
				data: {'fid': id},
				success: function (data) {
					var json = jQuery.parseJSON(data);
					console.log("",json);
					if (json['status'] == 'success') {
						$('#mode').val('edit');
						$('#autor').val(json['data'][0]['autor']);
						$('#title').val(json['data'][0]['title']);
						$('#type').val(json['data'][0]['type']);
						$('#bereich').val(json['data'][0]['bereich']);
						$('#feedback').val(json['data'][0]['feedback']);
						$('#fid').val(json['data'][0]['fid']);
						$('#status').val(json['data'][0]['status']);
						$('#uid').val(json['data'][0]['uid']);
						$('#editStatusBox').show("fast");
						var element = document.getElementById("title");
						element.scrollIntoView();
					}
				}
			});

		}
	},

});

sr.messages.init();


