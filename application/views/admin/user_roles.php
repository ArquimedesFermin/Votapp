<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"><input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_469") ?>" data-toggle="modal" data-target="#memberModal" />
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_470") ?></li>
</ol>

<p><?php echo lang("ctn_471") ?></p>


<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_472") ?></td><td><?php echo lang("ctn_473") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($roles->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td>
<td>
  <?php if($r->voter) : ?><span class="user_role_button voter" title="<?php echo lang("ctn_457") ?>" data-placement="bottom" data-toggle="tooltip"><?php echo lang("ctn_457") ?></span><?php endif; ?>
  <?php if($r->admin) : ?><span class="user_role_button admin" title="<?php echo lang("ctn_474") ?>" data-placement="bottom" data-toggle="tooltip"><?php echo lang("ctn_475") ?></span><?php endif; ?>
  <?php if($r->admin_settings) : ?><span class="user_role_button admin" title="<?php echo lang("ctn_476") ?>" data-placement="bottom" data-toggle="tooltip"><?php echo lang("ctn_477") ?></span><?php endif; ?>
  <?php if($r->admin_members) : ?><span class="user_role_button admin" title="<?php echo lang("ctn_478") ?>" data-placement="bottom" data-toggle="tooltip"><?php echo lang("ctn_479") ?></span><?php endif; ?>
  <?php if($r->admin_payment) : ?><span class="user_role_button admin" title="<?php echo lang("ctn_480") ?>" data-placement="bottom" data-toggle="tooltip"><?php echo lang("ctn_481") ?></span><?php endif; ?>
  <?php if($r->banned) : ?><span class="user_role_button banned" title="<?php echo lang("ctn_482") ?>" data-placement="bottom" data-toggle="tooltip"><?php echo lang("ctn_33") ?></span><?php endif; ?>
  <?php if($r->poll_creator) : ?><span class="user_role_button pollrole" title="<?php echo lang("ctn_483") ?>" data-placement="bottom" data-toggle="tooltip"><?php echo lang("ctn_484") ?></span><?php endif; ?>
  <?php if($r->admin_poll) : ?><span class="user_role_button admin" title="<?php echo lang("ctn_485") ?>" data-placement="bottom" data-toggle="tooltip"><?php echo lang("ctn_486") ?></span><?php endif; ?>
</td>
<td><a href="<?php echo site_url("admin/edit_user_role/" . $r->ID) ?>" class="btn btn-warning btn-xs" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("admin/delete_user_role/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_86") ?>')" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach; ?>
</table>

<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_469") ?></h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("admin/add_user_role_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_472") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="name">
                    </div>
            </div>
            <hr>
            <h4><?php echo lang("ctn_473") ?></h4>
			
            <div class="form-group">
			<label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_457") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_457") ?>"></span></label>
			<div class="col-md-8">
				<input type="checkbox" name="voter" value="1">
			</div>
            </div>   
			
			<div class="form-group">
			<label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_475") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_474") ?>"></span></label>
			<div class="col-md-8">
				<input type="checkbox" name="admin" value="1">
			</div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_477") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_476") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin_settings" value="1">
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_479") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_478") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin_members" value="1">
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_481") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_480") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin_payment" value="1">
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_486") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_485") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin_poll" value="1">
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_33") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_482") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="banned" value="1">
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_484") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_483") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="poll_creator" value="1">
                        </div>
            </div>
           
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_61") ?>" />
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
</div>