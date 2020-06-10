<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_345") ?>" data-toggle="modal" data-target="#themeModal" />
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_299") ?></li>
</ol>

<p><?php echo lang("ctn_344") ?></p>

<hr>


<table class="table table-bordered tbl">
<tr class="table-header"><td><?php echo lang("ctn_342") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($themes->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("admin/edit_poll_theme/" . $r->ID) ?>" class="btn btn-warning btn-xs"><?php echo lang("ctn_55") ?></a> <a href="<?php echo site_url("admin/delete_poll_theme/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_57") ?></a></td></tr>
<?php endforeach; ?>
</table>

<div class="modal fade" id="themeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_345") ?></h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("admin/add_poll_theme"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_342") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="name">
                    </div>
            </div>
            <div class="form-group">

                        <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_343") ?></label>
                        <div class="col-md-9">
                            <textarea class="form-control" rows="4" name="css_code">
.user-poll { border: 1px solid #000000; border-radius: 4px; padding: 10px; width: 350px;  }
.poll-voting-box { float: right; }
.answer-poll { padding: 10px; }
.answer-poll:hover { background: #cce0ec; }
.answer-label { width: 100%; }
.answer-image { float: left;margin-right: 10px; }
.poll-expire { font-size: 11px; background: #ece2e7; padding: 5px; border-radius: 4px; float: left;  }
                            </textarea>
                        </div>
            </div>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_345") ?>" />
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
</div>