<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
<!--<script src="https://rawgit.com/nnnick/Chart.js/f3eb6f4a433b4f34a582842dcf7b42f710861a7d/Chart.js"></script>-->

<div class="white-area-content">

<div id="preview_loading"><span id="loading-text"><?php echo lang("ctn_388") ?></span> <span class="glyphicon glyphicon-refresh" id="loading-spinner"></span></div>
<?php 

$msg = $this->session->flashdata("warning");

if(!empty($msg)): ?>
<div class="alert alert-warning" role="alert"><div class="row vertical-align" style="display: flex; align-items: center"><div class="col-md-1 text-right"><span class="glyphicon glyphicon-alert"></span></div><div class="col-xs-11"><b><h5><?php echo $msg ?></h5></b></div></div></div>
<?php endif; ?>

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_351") ?></div>
    <div class="db-header-extra"> 

    <?php if($this->user->loggedin && $this->user->info->ID == $poll->userid) : ?>
      <a href="<?php echo site_url("polls/edit_poll/" . $poll->ID) ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_358") ?></a> <a href="<?php echo site_url("polls/edit_poll_pro/" . $poll->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_379") ?></a> <!--<a href="<?php echo site_url("polls/delete_poll/" . $poll->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_387") ?></a>-->
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
<hr>

<?php
// Convert timestamp to days hours mins
  $ff =($poll->start+($poll->timestamp));
  $time = $this->common->convert_time($ff);
  unset($time['secs']);

  $fecha_final = date($this->settings->info->date_format, $poll->start+$poll->timestamp);

  ?>

<input type="hidden" id="pollid" value="<?php echo $poll->ID ?>">
<input type="hidden" id="pollhash" value="<?php echo $poll->hash ?>">

 <?php if($poll->ex_a == 0):?>
<div class="alert alert-info" role="alert"><?php echo lang("ctn_570"); ?></div>
<?php elseif($poll->ex_a == 1):?>
<div class="alert alert-info" role="alert"><?php echo lang("ctn_571"); ?></div>
<?php endif; ?>


<?php 
$status = "";
if($poll->timestamp == 0):
	$status = "";
elseif(time() > $ff):
	$status = '<left><span class="label label-danger">'. lang("ctn_384") .'</span></left>';	
endif;
?>


<div class="row">

<div class="col-md-4" style="margin-right: 15px">
<div class="user-poll clearfix" id="poll-area">

<div> <b><center> <?php echo $poll->name ."  ".$status?> </center></b> </div>
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

<?php echo form_open(site_url("polls/vote_poll/" . $poll->ID . "/" . $poll->hash . "/1")); ?>
<?php foreach($answers->result() as $r) : ?>

<div class="answer-poll" id="poll-answer-<?php echo $r->ID ?>">
<label class="answer-label" for="answer-input-<?php echo $r->ID ?>">
<?php if($r->image) : ?>
<div class="answer-image">
<a href="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" target="_blank"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" height="40" width="40"></a>
</div>
<?php endif; ?>
<?php echo $r->answer ?> 
<?php if( ($user_vote_num > 0 && $poll->show_results) || ($poll->show_results && $ff > time() && $poll->timestamp != 0)) : ?>
<span class="small-text" style="font-weight: normal;">(<?php echo $r->votes ?> <?php if($r->votes > 1 || $r->votes == 0) : ?><?php echo lang("ctn_426") ?><?php else : ?><?php echo lang("ctn_427") ?><?php endif; ?>)</span>
<?php endif; ?>
<?php if( (!$poll->ip_restricted || $user_vote_num ==0) && ($poll->timestamp == 0 || $ff > time())) : ?> 
<?php if($poll->vote_type == 0) :?>
<input type="radio" name="poll_answer" class="poll-voting-box" id="answer-input-<?php echo $r->ID ?>" value="<?php echo $r->ID ?>">
<?php else : ?>
<input type="checkbox" name="poll_answer_<?php echo $r->ID ?>" class="poll-voting-box" id="answer-input-<?php echo $r->ID ?>" value="<?php echo $r->ID ?>">
<?php endif; ?>
<?php endif; ?>
</label>
<?php if( ($user_vote_num > 0 && $poll->show_results) || ($poll->show_results && $ff > time() && $poll->timestamp != 0)) : ?>
	<?php $vote_percent = @intval(($r->votes/$poll->votes) * 100); ?>
<div class="progress">
	<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $vote_percent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $vote_percent ?>%; min-width: 15px;">
		<span class="" title="(<?php echo $vote_percent ?>%)"><?php echo $vote_percent ?> %</span>
	</div>
</div>
<?php endif; ?>
</div>
<?php endforeach; ?>

<?php if(time() < $ff && $poll->status==1) : ?>
<div>
<?php echo lang("ctn_313") ?>: <?php echo $this->common->get_time_string($time); ?><br />
<br />
</div>
<?php endif; ?>


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
<?php endif; ?>
<?php echo form_close() ?>
</div></div>
<!--   ************   -->


<div class="col-md-6">
<div style="border: solid 1px">
<center> <H3> <?php echo $poll->question ?> </H3> </center>
<canvas id="pollChart" class="graph-width"></canvas>
<br>
</div>
<hr />
<div class="panel panel-default">
	
	<div class="panel-heading">
		<?php echo lang("ctn_414") ?>
	</div>
	<div class="panel-body">
		  <div class="table-resposive">
          <h2>Listado de Votos</h2>
            <table id="tusers" cellpadding="0" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Usuario</th>
				  <th>Nombre completo</th>
                  <th>Respuesta</th>
                  <th>Fecha</th> 
				  <th>Hora</th>
                </tr>
              </thead>
              <tbody>
			  <?php $i = 0; ?>
				<?php foreach($votes->result() as $r) : ?>
				<?php $i++; ?>
					<tr><td><?php echo $i; ?></td><td><?php echo $r->UserName; ?></td><td><?php echo $r->FirstName ." ". $r->LastName ?></td><td><?php echo $r->answer ?></td>   
					<td><?php echo date($this->settings->info->date_format, $r->timestamp) ?></td>
					<td><?php echo date("h:i:s", $r->timestamp) ?></td>					
					</tr>
				<?php endforeach; ?>
              </tbody>
            </table>
          </div>   

		
		
		
		
		<div class="align-center">
			<?php echo $this->pagination->create_links(); ?>
		</div>

		<!--<p><a href="<?php echo site_url("polls/results_votes/" . $poll->ID) ?>" class="btn btn-primary btn-sm form-control"><?php echo lang("ctn_419") ?></a></p>
	-->
	</div>
</div>
</div>


</div>

</div>

<?php if($this->settings->info->enable_ads) : ?>
    <hr>
<?php include("adsense.php"); ?>
<?php endif; ?>

<script type="text/javascript">   

	$(document).ready(function() {

		var table10_Props = {
			highlight_keywords: true,  
			on_keyup: true,  
			on_keyup_delay: 0,  
			single_search_filter: false,  
			paging: true,  
			paging_length: 10,  
			col_0: "none",
			col_3: "select",
			col_4: "none",
			col_5: "none",
			
			col_width: ["50px","150px","300px","300px","150px"],
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