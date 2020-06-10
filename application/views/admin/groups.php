<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra">
		<input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_14") ?>" data-toggle="modal" data-target="#memberModal" />
	</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_15") ?></li>
</ol>

<p><?php echo lang("ctn_51") ?></p>

<hr>

<?php 
$form = "";
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
<tr class="table-header"><td><?php echo lang("ctn_18") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($groups->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("admin/edit_group/" . $r->ID."/?saved=".$form) ?>" class="btn btn-warning btn-xs"><?php echo lang("ctn_55") ?></a> <a href="<?php echo site_url("admin/delete_group/" . $r->ID . "/" . $this->security->get_csrf_hash()."?saved=".$form) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_56") ?>')"><?php echo lang("ctn_57") ?></a> <a href="<?php echo site_url("admin/view_group/" . $r->ID."?saved=".$form) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_58") ?></a></td></tr>
<?php endforeach; ?>
</table>

<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_14") ?></h4>
      </div>
      <div class="modal-body">
	  
      <?php echo form_open(site_url("admin/add_group_pro/?saved=".$form), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_18") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="name">
                    </div>
            </div>

			<!--<div class="form-group">

                        <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_19") ?></label>
                        <div class="col-md-9">
                            <input type="checkbox" name="default_group" value="1">
                            <span class="help-block"><?php echo lang("ctn_59") ?></span>
                        </div>
            </div> -->
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input onclick="window.location.href = '../poll/create?saved=true';" type="submit" class="btn btn-primary" value="<?php echo lang("ctn_590") ?>" />
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
</div>
	

<script type="text/javascript">   

$(document).ready(function() {

	var table10_Props = {
		paging: true,
		paging_length: 3,
		col_2: 'select',
		sort_num_asc: [2],
		refresh_filters: true
	};
	var tf10 = setFilterGrid("tusers", table10_Props);
	

   var st = $('#search_type').val();
    var table = $('#member-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          [5, "asc" ]
        ],
		   language: {
	"decimal":        "",
    "emptyTable":     "No hay datos",
    "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
    "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
    "infoFiltered":   "(Filtro de _MAX_ total registros)",
    "infoPostFix":    "",
    "thousands":      ",",
    "lengthMenu":     "Mostrar _MENU_ registros",
    "loadingRecords": "Cargando...",
    "processing":     "Procesando...",
    "search":         "Buscar:",
    "zeroRecords":    "No se encontraron coincidencias",
    "paginate": {
        "first":      "Primero",
        "last":       "Último",
        "next":       "Próximo",
        "previous":   "Anterior"
    }    }
   },
   "aria": {
        "sortAscending":  ": Activar orden de columna ascendente",
        "sortDescending": ": Activar orden de columna desendente"
    },
        "columns": [
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("admin/members_page") ?>",
            type : 'GET',
            data : function ( d ) {
                d.search_type = $('#search_type').val();
            }
        },
        "drawCallback": function(settings, json) {
        $('[data-toggle="tooltip"]').tooltip();
      }
    });
    $('#form-search-input').on('keyup change', function () {
    table.search(this.value).draw();
});

function change_search(search) 
    {
      var options = [
        "search-like", 
        "search-exact",
        "user-exact",
        "fn-exact",
        "ln-exact",
        "role-exact",
        "email-exact"
      ];
      set_search_icon(options[search], options);
        $('#search_type').val(search);
        $( "#form-search-input" ).trigger( "change" );
    }

function set_search_icon(icon, options) 
    {
      for(var i = 0; i<options.length;i++) {
        if(options[i] == icon) {
          $('#' + icon).fadeIn(10);
        } else {
          $('#' + options[i]).fadeOut(10);
        }
      }
    }
    });
	
function setCookie(cookieName,cookieValue,daysToExpire)
        {
          var date = new Date();
          date.setTime(date.getTime()+(daysToExpire*24*60*60*1000));
          document.cookie = cookieName + "=" + cookieValue + "; expires=" + date.toGMTString();
        }
		
function getCookie(cookieName)
{
  var name = cookieName + "=";
  var allCookieArray = document.cookie.split(';');
  for(var i=0; i<allCookieArray.length; i++)
  {
	var temp = allCookieArray[i].trim();
	if (temp.indexOf(name)==0)
	return temp.substring(name.length,temp.length);
  }
	return "";
}
</script>
