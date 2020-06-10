<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_351") ?></div>
    <div class="db-header-extra form-inline"> 

     <div class="form-group has-feedback no-margin">
<div class="input-group">
<input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_446") ?>" id="form-search-input" />
<div class="input-group-btn">
    <input type="hidden" id="search_type" value="0">
        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
        <ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
          <li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_447") ?></a></li>
          <li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_448") ?></a></li>
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="name-exact"></span> Poll Name</a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>


<?php if($this->common->has_permissions(array("admin", "poll_creator"), $this->user)) : ?>
    <a href="<?php echo site_url("polls/create") ?>" class="btn btn-success btn-sm"><?php echo lang("ctn_360") ?></a>
	<?php endif; ?>
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_359") ?></li>
</ol>

<?php if($page=="assoc"){
echo'
<table id="poll-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header">
<td>'.lang("ctn_587").'</td>
<td>'.lang("ctn_588").'</td>
<td>'.lang("ctn_381").'</td>
<td>'.lang("ctn_382").'</td>
<td>'.lang("ctn_383").'</td>
<td>'.lang("ctn_52").'</td>
</tr>
</thead>
<tbody>
</tbody>
</table>';
}else{
	
echo '<table id="poll-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header">
<td>'.lang("ctn_362").'</td>
<td>'.lang("ctn_591").'</td>
<td>'.lang("ctn_381").'</td>
<td>'.lang("ctn_382").'</td>
<td>'.lang("ctn_383").'</td>
<td>'.lang("ctn_52").'</td>
</tr>
</thead>
<tbody>
</tbody>
</table>';
}
?>



</div>
<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#poll-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          
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
    }    },
        "columns": [
        null,
        null,
        null,
        null,
        null,
        { orderable: false}
    ],
        "ajax": {
            url : "<?php echo site_url("polls/poll_page/" . $page) ?>",
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

} );
function change_search(search) 
    {
      var options = [
        "search-like", 
        "search-exact",
        "name-exact",
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
</script>