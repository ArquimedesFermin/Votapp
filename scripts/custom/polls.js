$(document).ready(function() {
	get_poll_results();
	$('#question').change(function() {
		var question = $("#question").val();
		var pollid = $('#pollid').val();
		$.ajax({
			url: global_base_url + 'polls/update_question/' + pollid + '/' + global_hash,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () { 
			$('#loading-text').html('Cargando ...');
			$('#preview_loading').fadeIn(10);
			$("#loading-spinner").addClass("spin");
		 	},
		 	complete: function () { 
			$('#loading-text').html('Guardado ');
			$('#preview_loading').fadeOut(1000);
			$("#loading-spinner").removeClass("spin");
		 	},
			data: {
				question : question
			},
			success: function(msg) {
				if(msg.error) {
					alert("Error: " + msg.error_msg);
				} else {
					if(msg.success) {
						
					}
				}
			}
		})
	});

	$('#answer-area').change('answer-field', function(event) {
		var pollid = $('#pollid').val();
		var answerid = $(event.target).attr('id');
		var answer = $(event.target).val();
		answerid = answerid.split('-');
		answerid = parseInt(answerid[2]);
		if(answer == '') return;
		$.ajax({
			url: global_base_url + 'polls/update_answer/' + pollid + '/' + answerid + '/' + global_hash,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () { 
			$('#loading-text').html('Cargando ...');
			$('#preview_loading').fadeIn(10);
			$("#loading-spinner").addClass("spin");
		 	},
		 	complete: function () { 
			$('#loading-text').html('Guardado ');
			$('#preview_loading').fadeOut(1000);
			$("#loading-spinner").removeClass("spin");
		 	},
			data: {
				answer : answer
			},
			success: function(msg) {
				if(msg.error) {
					alert("Error: " + msg.error_msg);
				} else {
					if(msg.success) {
						// Completed
					}
				}
			}
		})

	});
	
});

function update_status(pollid, status) 
{
	$.ajax({
		url: global_base_url + 'polls/update_status/' + pollid + '/' + global_hash,
		type: 'GET',
		dataType: 'json',
		beforeSend: function () { 
		$('#loading-text').html('Cargando ...');
		$('#preview_loading').fadeIn(10);
		$("#loading-spinner").addClass("spin");
	 	},
	 	complete: function () {
		$('#loading-text').html('Guardado ');
		$('#preview_loading').fadeOut(1000);
		$("#loading-spinner").removeClass("spin");
	 	},
		data: {
			status : status
		},
		success: function(msg) {
			if(msg.error) {
				alert("Error: " + msg.error_msg);
			} else {
				if(msg.success) {
					if(status == 0) {
						$('#status_button').html("Inactive").removeClass().addClass("btn btn-default");
					} else if(status == 1) {
						$('#status_button').html("Active").removeClass().addClass("btn btn-success");
					} else if(status == 2) {
						$('#status_button').html("Archived").removeClass().addClass("btn btn-info");
					}
				}
			}
		}
	})
}

function add_picture(answerid) 
{
	$('#picture-area-'+answerid).toggle();
}

function delete_image(answerid) 
{
	var pollid = $('#pollid').val();
	$.ajax({
			url: global_base_url + 'polls/delete_image/' + pollid + '/' + answerid + '/' + global_hash,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () { 
			$('#loading-text').html('Cargando ...');
			$('#preview_loading').fadeIn(10);
			$("#loading-spinner").addClass("spin");
		 	},
		 	complete: function () { 
			$('#loading-text').html('Guardado ');
			$('#preview_loading').fadeOut(1000);
			$("#loading-spinner").removeClass("spin");
		 	},
			data: {
			},
			success: function(msg) {
				if(msg.error) {
					alert("Error: " + msg.error_msg);
				} else {
					if(msg.success) {
						// Completed
						$('#image-id-'+answerid).fadeOut(10);
						$('#picture-area-'+answerid).toggle();
					}
				}
			}
		})
}

var realtime_pings = 0;
var myTime;
function toggle_realtime() 
{
	if(myTime) {
		$('#realtime-icon').removeClass();
		$('#realtime-icon').addClass('glyphicon glyphicon-ban-circle red-color');
		clearInterval(myTime);
		realtime_pings = 0;
		myTime=null;
	} else {
		$('#realtime-icon').removeClass();
		$('#realtime-icon').addClass('glyphicon glyphicon-ok green-color');
		myTime = setInterval(function(){ get_poll_results() }, 5000);
	}
}

function get_poll_results() 
{
	realtime_pings++;
	if(myTime) {
		if(realtime_pings > 60) {
			$('#realtime-icon').removeClass();
		    $('#realtime-icon').addClass('glyphicon glyphicon-ban-circle red-color');
			clearInterval(myTime);
			realtime_pings = 0;
			myTime=null;
		}
	}
	var pollid = $('#pollid').val();
	$.ajax({
			url: global_base_url + 'polls/get_poll_results/' + pollid,
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
			},
			success: function(msg) {
				
	 
				// Completed
				$('#poll_results').html(msg);
			}
		})
}

function delete_answer(answerid) 
{
	var pollid = $('#pollid').val();
	$.ajax({
			url: global_base_url + 'polls/delete_answer/' + pollid + '/' + answerid + '/' + global_hash,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () { 
			$('#loading-text').html('Cargando ...');
			$('#preview_loading').fadeIn(10);
			$("#loading-spinner").addClass("spin");
		 	},
		 	complete: function () { 
			$('#loading-text').html('Eliminado');
			$('#preview_loading').fadeOut(1000);
			$("#loading-spinner").removeClass("spin");
		 	},
			data: {
			},
			success: function(msg) {
				if(msg.error) {
					alert("Error: " + msg.error_msg);
				} else {
					if(msg.success) {
						// Completed
						$('#answer-area-id-'+answerid).fadeOut(400);
					}
				}
			}
		})
}

function update_answer(answerid) 
{
	var pollid = $('#pollid').val();
	var answer = $('#answer-id-' + answerid).val();
		if(answer == '') return;
		$.ajax({
			url: global_base_url + 'polls/update_answer/' + pollid + '/' + answerid + '/' + global_hash,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () { 
			$('#loading-text').html('Cargando ...');
			$('#preview_loading').fadeIn(10);
			$("#loading-spinner").addClass("spin");
		 	},
		 	complete: function () { 
			$('#loading-text').html('Guardado ');
			$('#preview_loading').fadeOut(1000);
			$("#loading-spinner").removeClass("spin");
		 	},
			data: {
				answer : answer
			},
			success: function(msg) {
				if(msg.error) {
					alert("Error: " + msg.error_msg);
				} else {
					if(msg.success) {
						// Completed
					}
				}
			}
		})
}


function add_answer(pollid) 
{
	$.ajax({
		url: global_base_url + 'polls/add_answer/' + pollid + '/' + global_hash,
		type: 'GET',
		dataType: 'json',
		beforeSend: function () { 
		$('#loading-text').html('Cargando ...');
		$('#preview_loading').fadeIn(10);
		$("#loading-spinner").addClass("spin");
	 	},
	 	complete: function () {
		$('#preview_loading').fadeOut(500);
		$("#loading-spinner").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			if(msg.error) {
				alert("Error: " + msg.error_msg);
			} else {
				if(msg.success) {
					create_answer_area(msg.answerid);
				}
			}
		}
	})
}

function create_answer_area(answerid) 
{
	var pollid = $('#pollid').val();
	var html = '<div class="answer-box group-spacing" id="answer-area-id-'+answerid+'">'
			  +'<div class="row no-display" id="picture-area-'+answerid+'">'
			  +'<div class="col-md-3 align-center">'
			  +'<p></p>'
			  +'</div>'
              +'<div class="col-md-9">'
			  +'<form accept-charset="utf-8" method="post" enctype="multipart/form-data" action="'+global_base_url+'polls/upload_image_answer/'+pollid+'/'+answerid+'">'
			  +'<input type="hidden" style="display:none;" value="'+global_hash+'" name="csrf_test_name">'
              +'<p><input type="file" name="userfile" value="Seleccionar imagen"></p><p><input type="submit" class="btn btn-primary btn-xs" value="Subir"/></p>'
              +'</form>'
              +'<p><button type="button" class="btn btn-danger btn-xs" onclick="delete_image('+answerid+')"><span class="glyphicon glyphicon-remove"></span> Eliminar imagen</button></p>'
              +'</div>'
              +'</div>'
				+'<div class="input-group">'
	  			+'<input type="text" class="form-control answer-field" id="answer-id-'+answerid+'" aria-label="..." placeholder="Ingresa tu alternativa.">'
  				+'<div class="input-group-btn">'
  				+'<button type="button" class="btn btn-default" title="Agregar imagen" onclick="add_picture('+answerid+')"><span class="glyphicon glyphicon-picture"></span></button>'
  				+'<button type="button" class="btn btn-default" title="Actualizar" onclick="update_answer('+answerid+')"><span class="glyphicon glyphicon-ok"></span></button>'
  				+'<button type="button" class="btn btn-default" title="Eliminar" onclick="delete_answer('+answerid+')"><span class="glyphicon glyphicon-remove"></span></button>'
  				+'</div>'
				+'</div>'
				+'</div>';
	$('#answer-area').append(html);
}