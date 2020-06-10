<script src="http://tablefilter.free.fr/TableFilter/tablefilter_all_min.js"></script>
<link rel="stylesheet" href="http://tablefilter.free.fr/TableFilter/filtergrid.css">

<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    
	<div class="db-header-extra"><input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_129") ?> <?php echo $group->name ?>" data-toggle="modal" data-target="#memberModal" />
	
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li><a href="<?php echo site_url("admin/user_groups") ?>"><?php echo lang("ctn_15") ?></a></li>
  <li class="active"><?php echo lang("ctn_125") ?></li>
</ol>

<p><?php echo lang("ctn_126") ?> <b><?php echo $group->name ?></b> - <?php echo lang("ctn_127") ?> <b><?php echo number_format($total_members) ?></b></p>

<hr>
<?php 
$form="";
if(!empty($_GET["saved"])){
$form = $_GET["saved"];
if($form!=null || $form != "" && $form==true):
?>
<div class="alert alert-warning" id="cookiesDiv" name="cookiesDiv" onload="checkCookie();"> 
<p><?php echo lang("ctn_575") ?></p>
<strong><a id="cookiesBtn" name="cookiesBtn" href="<?php echo site_url("polls/create?saved=true"); ?>"><?php echo lang("ctn_576") ?></a></strong>
</div>
<?php 
endif; 
}
?>
<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_7") ?></td><td><?php echo lang("ctn_215") ?></td><td><?php echo lang("ctn_179") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($users->result() as $r) : ?>
<tr><td><?php echo $r->first_name." ".$r->last_name ?></td><td><?php echo $r->username ?></td><td><?php echo $r->email ?></td><td><a href="<?php echo site_url("profile/" . $r->username) ?>" class="btn btn-success btn-xs"><?php echo lang("ctn_577") ?></a>  <a href="<?php echo site_url("admin/remove_user_from_group/" . $r->userid . "/" . $r->groupid . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_130") ?></a></td></tr>
<?php endforeach; ?>
</table>

<div class="align-center">
<?php echo $this->pagination->create_links() ?>
</div>

<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_129") ?></h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("admin/add_user_to_group_pro/" . $group->ID), array("class" => "form-horizontal")) ?>
            
				<div class="form-group">
				<label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_132") ?></label>
				<div class="col-md-12">
				<!--<input type="search" name="usernames" list="usernames">-->
				<table id="tusers" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" title="Id">#</th>
						<th scope="col">Usuario</th>
						<th scope="col">Nombre</th>
						<th scope="col">Apellido</th>
						<th scope="col">Sexo</th>
						<th scope="col">Nacionalidad</th>
						<th scope="col"></th>

					</tr>
				</thead>
				<tbody>
				<?php if($userlist->num_rows()!=0): ?>
				<?php $i = 1; ?>
				<?php foreach($userlist->result() as $row){?>
				<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $row->username; ?></td>
				<td><?php echo $row->first_name; ?></td>
				<td><?php echo $row->last_name; ?></td>

				<?php if($row->sex==0 || empty($row->sex)){ ?>
				<td></td>
				<?php }else if($row->sex==1){ ?>
				<td><?php echo lang("ctn_566"); ?></td>
				<?php }else if($row->sex==2){ ?>
				<td><?php echo lang("ctn_567"); ?></td>
				<?php } ?>
				<td><?php echo $row->nac; ?></td>
				<!--<td><a id="myLink" title="Agregar a la lista" onclick="addUser(<?php echo $row->username; ?>);">Agregar</a></tr>
					<td><a href="#" onclick='add("<?php echo $row->username; ?>");' class='btn btn-success btn-xs'>Agregar</a></td>
					<td><a class="btn btn-success" onclick="alert('<?php echo $row->username; ?>')"><?php echo lang("ctn_73") ?></a></td>-->
				<?php echo form_open(site_url("admin/add_users_to_group/" . $group->ID . "/" . $row->username."?saved=".$form), array("class" => "form-horizontal")) ?>
				<td><a href="<?php echo site_url("admin/add_users_to_group/" . $group->ID . "/" . $row->username."?saved=".$form); ?>" class="btn btn-success btn-xs">Agregar</a></td>
				</tr>
				<?php $i++; } ?>
				<?php endif; ?>
				
				</tbody>
			</table>

                        <!-- <input type="text" class="form-control" id="email-in" name="usernames"> -->
                       <!-- <span class="help-text"><?php echo lang("ctn_131") ?></span> -->
                    </div>
					
					
					
            </div>
           
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_73") ?>" />-->
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">   

$(document).ready(function(){

	var table10_Props = {
		highlight_keywords: true,  
        on_keyup: true,  
        on_keyup_delay: 0,  
        single_search_filter: false,  
        paging: true,  
        paging_length: 10,  
		col_0: "none",
        col_4: 'select',
		col_5: 'select',  
		col_6: "none", 
        sort_num_asc: [2],  
        refresh_filters: true, 
		display_all_text: " [ Mostrar Todo ] ",  
		sort_select: true ,
		btn_next_page_text: "Siguiente",
		btn_prev_page_text: "Anterior",
		btn_last_page_text: "Ultimo",
		btn_first_page_text: "Inicio",
		page_text: "Pagina",
		of_text: "de",
		help_instructions: false
	};

	var tf10 = setFilterGrid("tusers", table10_Props);

});
	function add(value){
	var userInput = document.getElementById("usernames");

		if(userInput.value == ""){
alert("activo");
			document.getElementById("usernames").innerHtml += value; 
		}else{
alert("inactivo");

			document.getElementById("usernames").innerHtml += userInput.value + "," + value; 
		}
	}
</script>
