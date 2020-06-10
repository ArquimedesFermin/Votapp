<div class="white-area-content">

<div id="preview_loading"><span id="loading-text"><?php echo lang("ctn_388") ?></span> <span class="glyphicon glyphicon-refresh" id="loading-spinner"></span></div>

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_351") ?></div>
    <?php if($poll->ex_a == 1 && $poll->ex_r==1): ?> 
	<div class="db-header-extra"> <a href="<?php echo site_url("polls/view_poll2/" . $poll->ID . "/" . $poll->hash. "/1/") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_335") ?></a> <a href="<?php echo site_url("polls/edit_poll_pro/" . $poll->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_379") ?></a> <a href="<?php echo site_url("polls/edit_poll/" . $poll->ID) ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_358") ?></a> <!--<a href="<?php echo site_url("polls/delete_poll/" . $poll->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_387") ?></a>-->
<?php else: ?>
	<div class="db-header-extra"> <a href="<?php echo site_url("polls/view_poll/" . $poll->ID . "/" . $poll->hash) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_335") ?></a> <a href="<?php echo site_url("polls/edit_poll_pro/" . $poll->ID) ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_379") ?></a> <a href="<?php echo site_url("polls/edit_poll/" . $poll->ID) ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_358") ?></a> <!--<a href="<?php echo site_url("polls/delete_poll/" . $poll->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_387") ?></a>-->
	<?php endif ?>
	</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("polls") ?>"><?php echo lang("ctn_359") ?></a></li>
  <li><a href="<?php echo site_url("polls/edit_poll/" . $poll->ID) ?>"><?php echo lang("ctn_358") ?></a></li>
  <li class="active"><?php echo lang("ctn_412") ?></li>
</ol>


<hr>

<div class="row">
<div class="col-md-12 col-sm-12 placeholder">

		  <div class="table-resposive">
          <h2>Listado de Votos</h2>
            <table id="tvotes" cellpadding="0" cellspacing="0">
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

		var tf10 = setFilterGrid("tvotes", table10_Props);

	});

</script>
