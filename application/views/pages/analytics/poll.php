<script src="http://tablefilter.free.fr/TableFilter/tablefilter_all_min.js"></script>
<link rel="stylesheet" href="http://tablefilter.free.fr/TableFilter/filtergrid.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
<!--<script src="https://rawgit.com/nnnick/Chart.js/f3eb6f4a433b4f34a582842dcf7b42f710861a7d/Chart.js"></script>-->

<div class="white-area-content">
<div class="db-header clearfix">
<div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> Análisis de Modelo</div>
<div class="db-header-extra form-inline">

<div class="form-group has-feedback no-margin">
<div class="input-group">

			</div>
		</div>
	</div>
</div>
<!--
<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_298") ?></li>
</ol>
<p><?php echo lang("ctn_337") ?></p>
<hr>
-->

<div class="row text-center placeholders" style="margin-right:100px; margin-bottom:1px">
<!-- TABLA -->
    <?php 
		$type="";
		if($poll->public == 0): $type="<strong>Público</strong>";
		elseif($poll->public == 1): $type="<strong>Privado</strong>";
		 endif; 
	 ?>
	 
<div class="col-sm-12 placeholder">
  <div class="panel-body">
    <table class="table table-bordered">
    <tr class="table-header"><td colspan="2"><?php echo lang("ctn_401") ?></td></tr>
    <tr><td><?php echo lang("ctn_362") ?>:</td><td><?php echo $poll->name ?></td></tr>
    <tr><td><?php echo lang("ctn_382") ?>:</td><td><?php echo date($this->settings->info->date_format, $poll->created) ?></td></tr>
    <tr><td><?php echo lang("ctn_403") ?>:</td><td><?php echo date($this->settings->info->date_format, $poll->updated) ?></td></tr>	
	<tr><td><?php echo lang("ctn_591") ?>:</td><td><?php echo $type ?></td></tr>
	<tr><td><?php echo lang("ctn_383") ?>:</td><td>
    <?php if($poll->timestamp > time() ) : ?>
      <?php echo $this->common->get_time_string($poll->timestamp); ?>
    <?php else : ?>
      <?php if($poll->timestamp > 0) : ?>
       <span class="label label-danger"><?php echo lang("ctn_384") ?></span>
      <?php else : ?>
        <?php echo lang("ctn_385") ?>
      <?php endif; ?>
    <?php endif; ?></td></tr>
	<?php if($poll->public && $poll->groupid!=0 && $group): ?>
    <tr><td><?php echo lang("ctn_161") ?>:</td><td><a href="<?php echo site_url("admin/view_group/" . $group->ID) ?>"><?php echo $group->name ?></a></td></tr>
	<?php endif; ?></td></tr>
    </table>
  </div>
</div>

<!-- RESULTADOS -->
<div class="col-md-4 col-sm-4 placeholder">
<?php if($poll->ex_a==1 && $poll->ex_r==1): ?>

<!-- SI ES APROBACION COMPLETA -->

	<div class="panel-body">
		<table class="table table-bordered">
			<tr class="table-header"><td colspan="2"><?php echo lang("ctn_592") ?></td></tr>
			<tr><td><?php echo lang("ctn_596") ?>:</td><td><?php echo $answers_votes2; ?></td></tr>
			
			<?php if($poll->public==1): ?>
			<tr><td><?php echo lang("ctn_597") ?>:</td><td><?php echo $total_members ?></td></tr>
			<tr><td><?php echo lang("ctn_598") ?>:</td><td><?php echo round(($answers_votes2 / $total_members)*100)."%" ?></td></tr>
			<?php endif; ?>
			<?php
			$ta = 0;
			$tr = 0;
			foreach($votes->result() as $r){
				$ta = $ta + $r->A;
				$tr = $tr + $r->R;
			}
			?>
			<tr><td><?php echo lang("ctn_538") ?>:</td><td><?php echo $ta ?></td></tr>
			<tr><td><?php echo lang("ctn_539") ?>:</td><td><?php echo $tr ?></td></tr>
			
		</table>
	</div>

<?php else: ?>

<!-- SI ES NORMAL -->

	<div class="panel-body">
		<table class="table table-bordered">
			<tr class="table-header"><td colspan="2"><?php echo lang("ctn_592") ?></td></tr>
			<tr><td><?php echo lang("ctn_592") ?>:</td><td><?php echo $top_answer->answer ?></td></tr>
			<tr><td><?php echo lang("ctn_594") ?>:</td><td><?php echo $top_answer->votes ?></td></tr>
			<tr><td><?php echo lang("ctn_596") ?>:</td><td><?php echo $votes->num_rows() ?></td></tr>
			<tr><td><?php echo lang("ctn_598") ?>:</td><td><?php 
			if($top_answer->votes!=0 && $votes->num_rows() !=0){
			$percentaje = round(($top_answer->votes / $votes->num_rows())*100);
			if(!$percentaje == 0){
			echo $percentaje."%";
			}else{
			echo "0%";
			}
			}else{
			echo "0%";
			}
			?></td></tr>
		</table>
	</div>
	
<?php endif; ?>
</div>	

<!-- GRAFICO -->

<div class="col-md-8 col-sm-5 placeholder">
  

<div class="panel-body">
    <table class="table table-bordered">
    <tr class="table-header"><td colspan="2"><?php echo $poll->question ?></td></tr>
<tr><td><canvas id="pollChart" class="graph-width"></canvas></td></tr>

</table>
 </div>
 </div>
<div class="col-md-12 col-sm-12 placeholder">

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
<script type="text/javascript">   

	$(document).ready(function() {

		var table10_Props = {
			highlight_keywords: true,  
			on_keyup: true,  
			on_keyup_delay: 0,  
			single_search_filter: false,  
			paging: false,  
			paging_length: 10,  
			<?php if($poll->ex_a==1 && $poll->ex_r==1): ?>
			col_0: "none",
			col_3: "select",
			col_4: "none",
			col_5: "none",
			col_6: "checklist",
			col_7: "none",
			col_width: ["50px","150px","300px","300px","200px","200px","200px"],
			<?php else: ?>
			col_0: "none",
			col_3: "select",
			col_4: "checklist",
			col_5: "none",
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


