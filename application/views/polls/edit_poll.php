<script src="http://tablefilter.free.fr/TableFilter/tablefilter_all_min.js"></script>
<link rel="stylesheet" href="http://tablefilter.free.fr/TableFilter/filtergrid.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
<!--<script src="https://rawgit.com/nnnick/Chart.js/f3eb6f4a433b4f34a582842dcf7b42f710861a7d/Chart.js"></script>-->


<div class="white-area-content">

<div id="preview_loading"><span id="loading-text"><?php echo lang("ctn_388") ?></span> <span class="glyphicon glyphicon-refresh" id="loading-spinner"></span></div>

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_351") ?></div>
   <?php if($poll->ex_a == 1 && $poll->ex_r == 1): ?>
	   <div class="db-header-extra"> <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#codeModal"><?php echo lang("ctn_389") ?></button> <a href="<?php echo site_url("polls/view_poll2/" . $poll->ID . "/" . $poll->hash."/1") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_335") ?></a> <a href="<?php echo site_url("analytics/poll/" . $poll->ID) ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_595") ?></a> <?php if($poll->status != 1) : ?><a href="<?php echo site_url("polls/edit_poll_pro/" . $poll->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_379") ?></a> <?php endif; ?>
   <?php else: ?>
	   <div class="db-header-extra"> <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#codeModal"><?php echo lang("ctn_389") ?></button> <a href="<?php echo site_url("polls/view_poll/" . $poll->ID . "/" . $poll->hash) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_335") ?></a> <a href="<?php echo site_url("analytics/poll/" . $poll->ID) ?>" class="btn btn-warning btn-sm"> <?php echo lang("ctn_595") ?></a> <?php if($poll->status != 1) : ?><a href="<?php echo site_url("polls/edit_poll_pro/" . $poll->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_379") ?></a> <?php endif; ?>
	<?php endif; ?>
	
	
	<!--<a onclick="alerta();" class="btn btn-danger btn-sm"><?php echo lang("ctn_387") ?></a>
	-->
	</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("polls") ?>"><?php echo lang("ctn_359") ?></a></li>
  <li class="active"><?php echo lang("ctn_358") ?></li>
</ol>


<hr>

<div class="row">
<div class="col-md-12">

   <?php if($poll->ex_a == 1 && $poll->ex_r == 1): ?>
	<div><?php echo lang("ctn_408") ?>: <a href="<?php echo site_url("polls/view_poll2/".$poll->ID . "/" . $poll->hash. "/1/") ?>"><?php echo site_url("polls/view_poll2/".$poll->ID . "/" . $poll->hash ."/1") ?></a></div>
   <?php else: ?>
	<div><?php echo lang("ctn_408") ?>: <a href="<?php echo site_url("polls/view_poll/".$poll->ID . "/" . $poll->hash) ?>"><?php echo site_url("polls/view_poll/".$poll->ID . "/" . $poll->hash) ?></a></div>
	<?php endif; ?>



<div class="input-group">
  <input type="text" class="form-control" aria-label="..." id="question" value="<?php echo $poll->question ?>">
  <input type="hidden" id="pollid" value="<?php echo $poll->ID ?>">
  <div class="input-group-btn">
  	<?php if($poll->status == 1) : ?>
    <button type="button" class="btn btn-success" id="status_button"><?php echo lang("ctn_332") ?></button>
	<?php elseif($poll->status == 2) : ?>
	<button type="button" class="btn btn-info" id="status_button"><?php echo lang("ctn_333") ?></button>
	<?php else : ?>
	<button type="button" class="btn btn-default" id="status_button"><?php echo lang("ctn_334") ?></button>
	<?php endif; ?>
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    	<span class="caret"></span>
    </button>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a href="javascript:void(0)" onclick="update_status(<?php echo $poll->ID ?>, 0); location.reload();"><?php echo lang("ctn_390") ?></a></li>
          <li role="separator" class="divider"></li>
		  <li><a href="javascript:void(0)" onclick="update_status(<?php echo $poll->ID ?>, 1); location.reload();"><?php echo lang("ctn_391") ?></a></li>
          <li role="separator" class="divider"></li>
		  <li><a href="javascript:void(0)" onclick="update_status(<?php echo $poll->ID ?>, 2); location.reload();"><?php echo lang("ctn_392") ?></a></li>
          
          <!-- <li><a href="#"><?php echo lang("ctn_393") ?></a></li> -->
        </ul>
  </div>
</div>

</div>
</div>

<hr>

<div class="row">
<div class="col-md-6">
 <?php if($poll->status != 1) : ?>
<div class="panel panel-default">
  <div class="panel-heading">
<div class="db-header-borderless clearfix">
    <div class="page-header-title"> <?php echo lang("ctn_394") ?></div>
    <div class="db-header-extra"> <button type="button" id="answer-button" class="btn btn-primary btn-xs" onclick="add_answer(<?php echo $poll->ID ?>)"><span class="glyphicon glyphicon-plus"></span> <?php echo lang("ctn_395") ?></button>
	</div>
</div>
</div>
<div class="panel-body" id="answer-area">

<?php foreach($answers->result() as $r) : ?>
<div class="answer-box group-spacing" id='answer-area-id-<?php echo $r->ID ?>'>

<div class="row <?php if(!$r->image) : ?>no-display<?php endif; ?>" id="picture-area-<?php echo $r->ID ?>">
<div class="col-md-3 align-center">
<p><?php if($r->image) : ?><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" height="70" id="image-id-<?php echo $r->ID ?>"/></p><?php endif; ?>
</div>
<div class="col-md-9">
<?php echo form_open_multipart(site_url("polls/upload_image_answer/" . $poll->ID . "/" . $r->ID)) ?>
<p><input type="file" name="userfile" value="<?php echo lang("ctn_402") ?>"></p><p> <input type="submit" class="btn btn-primary btn-xs" value="<?php echo lang("ctn_396") ?>"/></p>
<?php echo form_close() ?>
<?php if($r->image) : ?>
<p><button type="button" class="btn btn-danger btn-xs" onclick="delete_image(<?php echo $r->ID ?>)"><span class="glyphicon glyphicon-remove"></span> <?php echo lang("ctn_397") ?></button></p>
<?php endif; ?>
</div>
</div>
<div class="input-group">
    <input type="text" class="form-control answer-field" aria-label="..." placeholder="Ingresa tu alternativa." id="answer-id-<?php echo $r->ID ?>" placeholder="Ingresa tu alternativa" value="<?php echo $r->answer ?>">
  <div class="input-group-btn">
  <button type="button" class="btn btn-default" title="<?php echo lang("ctn_398") ?>" onclick="add_picture(<?php echo $r->ID ?>)"><span class="glyphicon glyphicon-picture"></span></button>
  <button type="button" class="btn btn-default" title="<?php echo lang("ctn_399") ?>" onclick="update_answer(<?php echo $r->ID ?>)"><span class="glyphicon glyphicon-ok"></span></button>
  <button type="button" class="btn btn-default" title="<?php echo lang("ctn_400") ?>" onclick="delete_answer(<?php echo $r->ID ?>)"><span class="glyphicon glyphicon-remove"></span></button>
  </div>
</div>
</div>
<?php endforeach; ?>



</div>
</div>
<?php endif; ?>
<?php
// Convert timestamp to days hours mins
  $ff =($poll->start+($poll->timestamp));
  $time = $this->common->convert_time($ff);
  unset($time['secs']);

  if($poll->start != 0 ){
	  $fecha_inicial = date($this->settings->info->date_format, $poll->start);
	  if($poll->timestamp !=0){
		  $fecha_final = date($this->settings->info->date_format, $poll->start+$poll->timestamp);
	  }else{
		  $fecha_final = "-";
	  }
  } else {
	  $fecha_inicial = "-";
	  $fecha_final = "-";
  }
  ?>

<div class="panel panel-default">
  <div class="panel-body">
    <table class="table table-bordered">
    <tr class="table-header"><td colspan="2"><?php echo lang("ctn_401") ?></td></tr>
    <tr><td><?php echo lang("ctn_362") ?>:</td><td><?php echo $poll->name ?></td></tr>
    <tr><td><?php echo lang("ctn_382") ?>:</td><td><?php echo date($this->settings->info->date_format, $poll->created) ?></td></tr>
    <tr><td><?php echo lang("ctn_403") ?>:</td><td><?php echo date($this->settings->info->date_format, $poll->updated) ?></td></tr>
    <tr><td><?php echo lang("ctn_612") ?>:</td><td><?php echo $fecha_inicial ?></td></tr>
    <tr><td><?php echo lang("ctn_613") ?>:</td><td><?php echo $fecha_final ?></td></tr>
   
   <tr><td><?php echo lang("ctn_383") ?>:
   </td><td>
   
   <?php if($poll->timestamp == 0) : ?>
        <?php echo lang("ctn_385") ?>
   <?php elseif($poll->start == 0) : ?>
        <?php echo "-" ?>
    <?php elseif(time() < $ff) : ?>
    <?php echo $this->common->get_time_string($time); ?>
    <?php else : ?>
	<?php if(time() > $ff) : ?>
       <span class="label label-danger"><?php echo lang("ctn_384") ?></span>
      <?php endif; ?>
    <?php endif; ?>
	
	</td></tr>
	<?php if($poll->public && $poll->groupid!=0 && $groups): ?>
    <tr><td><?php echo lang("ctn_161") ?>:</td><td><a href="<?php echo site_url("admin/view_group/" . $groups->ID) ?>"><?php echo $groups->name ?></a></td></tr>
	<?php endif; ?></td></tr>
    </table>
  <?php if($poll->status != 1) : ?>
      <?php if($votes->num_rows() > 0 && $poll->start > 0) : ?>
	
	<span> Este modelo ya tiene votaciones y no puede ser modificado </span>
	  
	  <?php else: ?>
    <a href="<?php echo site_url("polls/edit_poll_pro/" . $poll->ID) ?>" class="btn btn-warning btn-sm form-control"><?php echo lang("ctn_379") ?></a>
  

  <?php endif; ?>
  <?php endif; ?>
  </div>
</div>

</div>
<div class="col-md-6">
<div>
<div class="panel panel-default">
  <div class="panel-heading">
  	<div class="db-header-borderless clearfix">
    <div class="page-header-title"> <?php echo lang("ctn_404") ?></div>
    <div class="db-header-extra"> 
</div>
</div>	
  </div>
<!-- GRAFICO -->
<br />
<div class="col"> <canvas id="pollChart" class="graph-width" height="90%"></canvas> </div>
<br />
 </div>



</div>
<div>

<div class="panel panel-default">
	<div class="panel-heading">
		<?php echo lang("ctn_414") ?>
	</div>
	<div class="panel-body">
		<table class="table table-bordered">
			<tr class="table-header small-text"><td>Usuario</td><td><?php echo lang("ctn_415") ?></td><td><?php echo lang("ctn_417") ?></td></tr>
			<?php foreach($votes->result() as $r) : ?>
			<tr class="small-text"><td><?php echo $r->FirstName ." ". $r->LastName ?></td><td><?php echo $r->answer ?></td><td><?php echo date($this->settings->info->date_format, $r->timestamp) ?></td></tr>
			<?php endforeach; ?>
		</table>
	</div>
	</div>
		<p><a href="<?php echo site_url("polls/results/" . $poll->ID) ?>" class="btn btn-primary btn-sm form-control"><?php echo lang("ctn_419") ?></a></p>

	</div>
	</div>
</div>

<div class="modal fade" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_407") ?></h4>
      </div>
      <div class="modal-body">
        <p><b><?php echo lang("ctn_408") ?></b></p>
        <p><?php echo lang("ctn_409") ?></p>

        <p><?php echo site_url("polls/view_poll/".$poll->ID . "/" . $poll->hash) ?></p>

        <p><b><?php echo lang("ctn_410") ?></b></p>
        <p><?php echo lang("ctn_411") ?></p>

        <p><textarea class="form-control" rows="2"><iframe src="<?php echo site_url("polls/view_poll/".$poll->ID . "/" . $poll->hash) ?>" width="400" height="600" frameborder="0" ></iframe></textarea></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
      </div>
    </div>
  </div>
</div>


<script>
  toggle_realtime();
  var elem = document.getElementById("answer-button");
  var pos = 0;
  var id = setInterval(frame, 100);
  function frame(){
	  if(pos<60){
	  pos++;
	  var colors = ["red","white"];
	  var rnd = Math.floor(Math.random() * 2)
      elem.style.borderColor = colors[rnd]; 
      elem.style.borderStyle = "solid"; 
	  
	  }else{
      elem.style.borderColor = colors[1]; 
	  }
  }
  function alerta()
    {
    var mensaje;
    var opcion = confirm("Seguro que desea eliminar este modelo?");
    if (opcion == true) {
        window.location = "<?php echo site_url("polls/delete_poll/" . $poll->ID . "/" . $this->security->get_csrf_hash()) ?>";
	} else {
	   
	   
	}
}
</script>


<?php if($poll->ex_a==1 && $poll->ex_r==1): ?>
 <script>	
var data = <?php echo json_encode($answers->result()); ?>;
var ctx = document.getElementById("pollChart").getContext("2d");

  var ex_a = [];
  var ex_r = [];
  var labels = [];

  data.forEach(function(answer) {
    ex_a.push(answer.ex_a);
    ex_r.push(answer.ex_r);
    labels.push(answer.answer);
  });
    console.log(data);

  console.log(labels);
  console.log(ex_a);
  console.log(ex_r);
  
  var chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
	labels: labels,
     // datasets: [dataset],
	  datasets:[
	  {label: "Aprobaciones",
		backgroundColor: 'rgba(99,255,155, 0.2)',
        borderColor: 'rgba(38,233,80,1)',
        borderWidth: 1,
		data:ex_a},
		
		{label: "Rechazos",
		backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255,99,132,1)',
        borderWidth: 1,
		data: ex_r}
		]

    },

    options: {  
		scales: {
        yAxes: [{
            display: true,
            ticks: {
                suggestedMin: 0,    // minimum will be 0, unless there is a lower value.
                // OR //
                beginAtZero: true   // minimum value will be 0.
            }
        }]
    }
	}
  });
  
</script>
<?php else: ?>

<script>
var data = <?php echo json_encode($answers->result()); ?>;
var ctx = document.getElementById("pollChart").getContext("2d");

	 var votes = [];
	 var labels = [];
		 
  data.forEach(function(answer) {
    votes.push(answer.votes);
    labels.push(answer.answer+":"+answer.votes);
  });
console.log(data);
  
  
  
  var chartInstance = new Chart(ctx, {
    type: 'pie',
    data: {
	labels: labels,
     // datasets: [dataset],
	  datasets:[
	  {label: "Votos",
		backgroundColor: ["#F44336","#009688","#FFC107","#4CAF50","#3F51B5","#FF5722","#607D8B"],
		borderColor: 'rgba(200, 200, 200, 0.75)',
		//hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
		hoverBorderColor: 'rgba(200, 200, 200, 1)',        
		borderWidth: 1,
		data:votes},
		]

    },

    options: {
        animation: {
            duration: 0
        },
	legend:{
    "display": true
   },
	}
	
  });
  </script>
<?php endif; ?>


