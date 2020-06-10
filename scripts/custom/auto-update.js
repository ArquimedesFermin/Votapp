$(document).ready(function() {
	refreshPollCI = setInterval(refreshPoll, 60000);
});

var refreshPollCI;
var count = 0;
var redirect = 1;

function refreshPoll() 
{
	count++;
	if(count > 60) {
		clearInterval(refreshPollCI);
	}
	var pollid = $('#pollid').val();
	var hash = $('#pollhash').val();
	if(pollid == undefined) return;
	if(hash == undefined) return; 
	$.ajax({
		url: global_base_url + 'polls/ajax_poll_refresh/' + pollid + '/' + hash,
		type: 'GET',
		beforeSend: function () { 
		$('#loading-text').html('Cargando ...');
		$('#preview_loading').fadeIn(10);
		$("#loading-spinner").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading-text').html('Listo');
		$('#preview_loading').fadeOut(10);
		$("#loading-spinner").removeClass("spin");
	 	},
		data: {
			redirect : redirect
		},
		success: function(msg) {
			// Completed
			$('#poll-area').html(msg);
		}
	})
}