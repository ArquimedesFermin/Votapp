<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>

<style>
.range {
    display: table;
    position: relative;
    height: 25px;
    margin-top: 20px;
    background-color: rgb(245, 245, 245);
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    cursor: pointer;
}

.range input[type="range"] {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    -o-appearance: none !important;
    appearance: none !important;

    display: table-cell;
    width: 100%;
    background-color: transparent;
    height: 25px;
    cursor: pointer;
}
.range input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    -o-appearance: none !important;
    appearance: none !important;

    width: 11px;
    height: 25px;
    color: rgb(255, 255, 255);
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0px;
    background-color: rgb(153, 153, 153);
}

.range input[type="range"]::-moz-slider-thumb {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    -o-appearance: none !important;
    appearance: none !important;
    
    width: 11px;
    height: 25px;
    color: rgb(255, 255, 255);
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0px;
    background-color: rgb(153, 153, 153);
}

.range output {
    display: table-cell;
    padding: 3px 5px 2px;
    min-width: 40px;
    color: rgb(255, 255, 255);
    background-color: rgb(153, 153, 153);
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
    width: 1%;
    white-space: nowrap;
    vertical-align: middle;

    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    -ms-transition: all 0.5s ease;
    transition: all 0.5s ease;

    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: -moz-none;
    -o-user-select: none;
    user-select: none;
}
.range input[type="range"] {
    outline: none;
}

.range.range-primary input[type="range"]::-webkit-slider-thumb {
    background-color: rgb(66, 139, 202);
}
.range.range-primary input[type="range"]::-moz-slider-thumb {
    background-color: rgb(66, 139, 202);
}
.range.range-primary output {
    background-color: rgb(66, 139, 202);
}
.range.range-primary input[type="range"] {
    outline-color: rgb(66, 139, 202);
}

.range.range-success input[type="range"]::-webkit-slider-thumb {
    background-color: rgb(92, 184, 92);
}
.range.range-success input[type="range"]::-moz-slider-thumb {
    background-color: rgb(92, 184, 92);
}
.range.range-success output {
    background-color: rgb(92, 184, 92);
}
.range.range-success input[type="range"] {
    outline-color: rgb(92, 184, 92);
}

.range.range-info input[type="range"]::-webkit-slider-thumb {
    background-color: rgb(91, 192, 222);
}
.range.range-info input[type="range"]::-moz-slider-thumb {
    background-color: rgb(91, 192, 222);
}
.range.range-info output {
    background-color: rgb(91, 192, 222);
}
.range.range-info input[type="range"] {
    outline-color: rgb(91, 192, 222);
}

.range.range-warning input[type="range"]::-webkit-slider-thumb {
    background-color: rgb(240, 173, 78);
}
.range.range-warning input[type="range"]::-moz-slider-thumb {
    background-color: rgb(240, 173, 78);
}
.range.range-warning output {
    background-color: rgb(240, 173, 78);
}
.range.range-warning input[type="range"] {
    outline-color: rgb(240, 173, 78);
}

.range.range-danger input[type="range"]::-webkit-slider-thumb {
    background-color: rgb(217, 83, 79);
}
.range.range-danger input[type="range"]::-moz-slider-thumb {
    background-color: rgb(217, 83, 79);
}
.range.range-danger output {
    background-color: rgb(217, 83, 79);
}
.range.range-danger input[type="range"] {
    outline-color: rgb(217, 83, 79);
}
</style>

<div class="white-area-content">

<div id="preview_loading"><span id="loading-text"><?php echo lang("ctn_388") ?></span> <span class="glyphicon glyphicon-refresh" id="loading-spinner"></span></div>

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_351") ?></div>
    <div class="db-header-extra"> 

    <?php if($this->user->loggedin && $this->user->info->ID == $poll->userid) : ?>
      <a href="<?php echo site_url("polls/edit_poll/" . $poll->ID) ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_358") ?></a> <!--<a href="<?php echo site_url("polls/edit_poll_pro/" . $poll->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_379") ?></a> <a href="<?php echo site_url("polls/delete_poll/" . $poll->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_387") ?></a>-->
    <?php endif; ?>
</div>
</div>
<?php if($this->user->info->poll_creator) : ?>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li><a href="<?php echo site_url("admin/manage_polls") ?>"><?php echo lang("ctn_298") ?></a></li>

  <?php if($this->user->loggedin && $this->user->info->ID == $poll->userid) : ?>
    <li><a href="<?php echo site_url("polls/edit_poll/" . $poll->ID) ?>"><?php echo lang("ctn_358") ?></a></li>
  <?php endif; ?>
  <li class="active"><?php echo lang("ctn_335") ?></li>
</ol>
<?php endif; ?>
 <?php if($poll->ex_a == 0):?>
<div class="alert alert-info" role="alert"> <?php echo lang("ctn_570"); ?> </div>
<?php elseif($poll->ex_a == 1):?> 
<div class="alert alert-info" role="alert"><?php echo lang("ctn_571"); ?></div>
<?php endif; ?>

<hr>

<div class="row" >
<div class="col-md-4" >

<?php
// Convert timestamp to days hours mins
  $ff =($poll->start+($poll->timestamp));
  $time = $this->common->convert_time($ff);
  unset($time['secs']);

  $fecha_final = date($this->settings->info->date_format, $poll->start+$poll->timestamp);
	$i = 0;
  ?>
  
<input type="hidden" id="pollid" value="<?php echo $poll->ID ?>">
<input type="hidden" id="pollhash" value="<?php echo $poll->hash ?>">

<div class="user-poll clearfix" id="poll-area">

<?php 
$TotalAprobacion = 0;
$TotalRechazo = 0;
$u = array();
$u = $user_vote->result();
$status = "";
if($poll->timestamp == 0):
	$status = "";
elseif(time() > $ff):
	$status = '<left><span class="label label-danger">'. lang("ctn_384") .'</span></left>';	
endif;
?>

<div> <b><center> <?php echo $poll->name ."  ".$status?> </center></b></div> 
<?php 
echo '<div class="small-text" style="margin:5px"><center>';
echo '(<strong>'. $total_voters .'</strong>) Votantes sufragantes';
echo ' de ';
echo '(<strong>'. $total_users .'</strong>) Votantes hábiles </div>';
?>

<div class="small-text" style="margin:5px; background-color: #FFAC7D; border: 1px solid; border-radius: 3px; text-align: center; color: #803A0A">Modificaciones disponibles en este modelo de elección:(<b><?php echo ($poll->votes_limit-$mods) ?></b>)</div>

<hr />
<?php echo $poll->question ?>
<hr>

<?php echo form_open(site_url("polls/vote_poll2/" . $poll->ID . "/" . $poll->hash . "/1")); ?>
<?php foreach($answers->result() as $r) : ?>

<div class="answer-poll" id="poll-answer-<?php echo $r->ID ?>">
<label class="answer-label" for="answer-input-<?php echo $r->ID ?>">
<?php if($r->image) : ?>
<div class="answer-image">
<a href="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" target="_blank"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" height="40" width="40"></a>
</div>
<?php endif; ?>
<?php echo $r->answer ?> 
<!--  INICIO -->

        <div class="center">
            <div class="row">
			<div class="col-xs-6">
			<h6>Aprobación</h6>
			<div class="range range-success">
			<input type="range" class="slider" name="answer-a-<?php echo $r->ID ?>" min="0" max="<?php echo $balls_user; ?>" value=<?php if(isset($u) && !empty($u)): echo $u[$i]->ex_a; else: echo 0; endif; ?>>
			<output class="output" id="a"><?php if(isset($u) && !empty($u)): echo $u[$i]->ex_a; else: echo 0; endif; ?></output>
			</div>
			</div>
  
			<div class="col-xs-6">
			<h6>Rechazo</h6>
			<div class="range range-danger">
			<input type="range" class="slider" name="answer-r-<?php echo $r->ID ?>" min="0" max="<?php echo $balls_user; ?>" value=<?php if(isset($u) && !empty($u)): echo $u[$i]->ex_r; else: echo 0; endif; ?>>
			<output class="output" id="r"><?php if(isset($u) && !empty($u)): echo $u[$i]->ex_r; else: echo 0; endif; ?></output>
			</div>
			</div>
			</div>    
		</div>
		
<!--  FIN -->
</label>
<?php if($ff < time() || $poll->timestamp != 0) : ?>

<?php	
$M  = (2/3)*$total_balls;
$U  = (1/2)*$total_balls;
$TotalAprobacion = $r->ex_a;
$TotalRechazo = $r->ex_r;

echo '
<div class="row" style="text-align:center; display: inline-block; padding:1px">
<div class="col-xs-6" style=" padding-left:1px; border-radius: 5px; border: 1px solid green; border-left: 6px solid green; background-color: lightred;">
<h6><strong>Aprobación</strong></h6>
<p id="a">'.$r->ex_a.'</p>
</div>

<div class="col-xs-6" style=" padding-right:1px; border-radius: 5px; border: 1px solid red; border-left: 6px solid red; background-color: lightred;">
<h6><strong>Rechazo</strong></h6>
<p id="r" >'.$r->ex_r.'</p>
</div>';


if(time() > $ff || $poll->timestamp == 0){
//Calculo de estado de aceptabilidad
echo  '<div class="col-xs-12" style="padding-left:30px"><center>';
if ($TotalRechazo >= $M)
echo '<span>Estado de aceptabilidad: </span> <span class="label label-danger" style="padding: 1px; margin: 1px;"> Insatisfactorio </span></div></center>';
else if($TotalRechazo >= $U)
echo '<span>Estado de aceptabilidad: </span> <span class="label label-warning" style="padding: 1px; margin: 1px;">  Dudoso  </span></div></center>';
else{
if($TotalAprobacion >= $M)
echo '<span>Estado de aceptabilidad: </span> <span class="label label-info" style="padding: 1px; margin: 1px;">  Satisfactorio  </span></div></center>';
else if($TotalAprobacion>=$U)
echo '<span>Estado de aceptabilidad: </span> <span class="label label-success" style="padding: 1px; margin: 1px;">  Aceptable  </span></div></center>';
else echo '<span>Estado de aceptabilidad </span> <span class="label label-default" style="padding: 1px; margin: 1px;">  Indeterminado  </span></div></center>';
}	
echo '</center></div>';
}else{
echo  '<div class="col-xs-12" style="padding-left:30px"><center>';
echo '<span>_______________________ </span> <span class="label label-default" style="padding: 1px; margin: 1px;"></span></div></center>';
echo '</center></div>';

}
?>
<hr />
<?php endif; ?>
</div>
<?php $i++; ?>
<?php endforeach; ?>

<?php
echo '<table>';
echo '<tbody>
<tr><td><b>Marca de aprobación: </b></td><td> &nbsp; '.$M.'</td></tr><tr>
<td><b>Marca de rechazo: </b></td><td> &nbsp; '.$M.'</td></tr>
<tr><td><b>Umbral de aprobación: </b></td><td> &nbsp; '.$U.'</td></tr>
<tr><td><b>Umbral de rechazo: </b></td><td> &nbsp; '.$U.'</td>
</tr>
</tbody>';
echo "</table>";
?>

<hr>
<?php if(time() < $ff || $poll->timestamp == 0): ?>
<?php if(!$this->user->info->admin && !$this->user->info->poll_creator) : ?> 
<?php if($poll->userid !== $this->user->info->ID): ?>

	<div>
	<center>
	<?php 
	$txt = "";
	if($user_vote_num ==0){
		$txt = lang("ctn_426");
	}else{
		$txt = "Modificar Voto";
	}
	?>
	
	<?php if($poll->status == 1): ?>
		<input type="submit" class="btn btn-primary btn-sm pull-center vote-button" value="<?php echo $txt; ?>" />
	<?php endif; ?>
	</center>
	</div>
	<hr>

<?php else: ?>
<br><div><center><strong><span><?php echo lang("ctn_557") ?></span></strong></center></div>
<?php endif; ?>
<?php else: ?>
<br><div><center><strong><span><?php echo lang("ctn_558") ?></span></strong></center></div>
<?php endif; ?>
<?php else: ?>
<br><div><center><strong><span><?php echo lang("ctn_537") ?></span></strong></center></div>
<?php echo form_close() ?>
</div></div></div>


<div class="row" >
<div class="col-md-12">
<center> <H3> <?php echo $poll->question ?> </H3> </center>

<canvas id="pollChart" style="position: relative; height:60vh; width:100vw"></canvas>
		  <div class="table-resposive">
          <h2>Listado de Votos</h2>
            <table id="tusers" cellpadding="0" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Usuario</th>
				  <th>Nombre completo</th>
                  <th>Respuesta</th>
				  <?php if($poll->ex_a==1 && $poll->ex_r==1): ?>
                  <th>Aprobaciones</th>
				  <th>Rechazos</th>				  
				  <?php endif; ?>
                  <th>Fecha</th>
				  <th>Hora</th>
                </tr>
              </thead>
              <tbody>
			  <?php $i = 0; ?>
				<?php foreach($votes->result() as $r) : ?>
				<?php $i++; ?>
					<tr><td><?php echo $i; ?></td><td><?php echo $r->UserName; ?></td><td><?php echo $r->FirstName ." ". $r->LastName ?></td><td><?php echo $r->answer ?></td>   
					<?php if($poll->ex_a==1 && $poll->ex_r==1): ?>
					<td><?php echo $r->A ?></td>
					<td><?php echo $r->R ?></td>
					<?php endif ?>
					<td><?php echo date($this->settings->info->date_format, $r->timestamp) ?></td>
					<td><?php echo date("h:i:s", $r->timestamp) ?></td>					
					</tr>
				<?php endforeach; ?>
              </tbody>
            </table>
          </div>   
</div>
</div>
<?php endif; ?>

<script>
var balls_user = <?php echo $balls_user; ?>;
$(function(){	
	$('.slider').on('input change', function(){
			  $(this).next($('.output')).html(this.value+"-"+balls_user);
			});
			
	$('.output').each(function(){
	  var value = $(this).prev().attr('value');
	  $(this).html(value+"-"+balls_user);
	});  
});
</script>

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
		resposive:true,
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
		
<script type="text/javascript">   

	$(document).ready(function() {

		var table10_Props = {
			highlight_keywords: true,  
			on_keyup: true,  
			on_keyup_delay: 0,  
			single_search_filter: false,  
			paging: true,  
			paging_length: 10,  
			<?php if($poll->ex_a==1 && $poll->ex_r==1): ?>
			col_0: "none",
			col_3: "select",
			col_4: "none",
			col_5: "none",
			col_6: "none",
			col_7: "none",
			
			col_width: ["50px","150px","300px","300px","200px","200px","200px"],
			<?php else: ?>
			col_0: "none",
			col_3: "select",
			col_4: "checklist",			
			col_width: ["50px","150px","300px","300px","150px"],
			<?php endif; ?>
			sort_num_asc: [0],  
			refresh_filters: true, 
			display_all_text: " [ Mostrar Todo ] ",  
			sort_select: true ,
			btn_next_page_text: "Siguiente",
			btn_prev_page_text: "Anterior",
			btn_last_page_text: "Ultimo",
			btn_first_page_text: "Inicio",
			page_text: "Pagina",
			of_text: "de",
			help_instructions: false,
			status_bar: true,
			grid_enable_cols_resizer: true
		};

		var tf10 = setFilterGrid("tusers", table10_Props);

	});

</script>
		
