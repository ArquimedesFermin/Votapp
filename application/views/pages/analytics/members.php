<script src="http://tablefilter.free.fr/TableFilter/tablefilter_all_min.js"></script>
<link rel="stylesheet" href="http://tablefilter.free.fr/TableFilter/filtergrid.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>

<div class="white-area-content">
<div class="db-header clearfix">
<div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> Análisis de Miembros</div>
</div>

<div class="row">

<!--        ******         -->
<div class="col-md-4">
  <div class="panel-body">
    <table class="table table-bordered">
    <tr class="table-header"><td colspan="2"><?php echo lang("ctn_604") ?></td></tr>
    <tr><td><?php echo lang("ctn_605") ?>:</td><td><?php echo $total_members ?></td></tr>
    <tr><td><?php echo lang("ctn_606") ?>:</td><td><?php echo $total_members_active->num_rows() ?></td></tr>
    <tr><td><?php echo lang("ctn_607") ?>:</td><td><?php echo $total_members_banned->num_rows() ?></td></tr>	
    </table>
  </div>
</div>
<!--        ******         -->

<!--        ******         -->
<div class="col-md-4">
  <div class="panel-body">
    <table class="table table-bordered">
    <tr class="table-header"><td colspan="2"><?php echo lang("ctn_608") ?></td></tr>
    <tr><td><?php echo lang("ctn_609") ?>:</td><td><?php echo $total_members_admin->num_rows() ?></td></tr>
    <tr><td><?php echo lang("ctn_610") ?>:</td><td><?php echo $total_members_voter->num_rows() ?></td></tr>
    <tr><td><?php echo lang("ctn_611") ?>:</td><td><?php echo $total_members_external->num_rows() ?></td></tr>	
    </table>
  </div>
</div>
<!--        ******         -->







<!--        ******         -->
<div class="col-md-10">
  <div class="table-resposive">
  <h2>Listado de Usuarios</h2>
	<table id="tusers" cellpadding="0" cellspacing="0">
	  <thead>
		<tr>
		  <th>#</th>
		  <th>Usuario</th>
		  <th>Nombre completo</th>
		  <th>Fecha de registro</th> 
		  <th>Hora</th>
		</tr>
	  </thead>
	  <tbody>
	  <?php $i = 0; ?>
		<?php foreach($members->result() as $r) : ?>
		<?php $i++; ?>
			<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $r->username; ?></td>
			<td><?php echo $r->first_name ." ". $r->last_name; ?></td>   
			<td><?php echo date($this->settings->info->date_format, $r->joined); ?></td>
			<td><?php echo date("h:i:s", $r->joined); ?></td>					
			</tr>
		<?php endforeach; ?>
	  </tbody>
	</table>
  </div> 
  </div> 

<!--        ******         -->
  
  </div>   



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


</div>
</div>

