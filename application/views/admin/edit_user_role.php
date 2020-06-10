<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_1") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li><a href="<?php echo site_url("admin/user_roles") ?>"><?php echo lang("ctn_470") ?></a></li>
  <li class="active"><?php echo lang("ctn_487") ?></li>
</ol>


<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/edit_user_role_pro/" . $role->ID), array("class" => "form-horizontal")) ?>

<div class="form-group">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_472") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="email-in" name="name" value="<?php echo $role->name ?>">
        </div>
</div>
<hr>
            <h4><?php echo lang("ctn_473") ?></h4>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_457") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_457") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="voter" value="1" <?php if($role->voter) echo "checked" ?>>
                        </div>
            </div>			
			
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_475") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_474") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin" value="1" <?php if($role->admin) echo "checked" ?>>
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_477") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_476") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin_settings" value="1" <?php if($role->admin_settings) echo "checked" ?>>
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_479") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_478") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin_members" value="1" <?php if($role->admin_members) echo "checked" ?>>
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_481") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_480") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin_payment" value="1" <?php if($role->admin_payment) echo "checked" ?>>
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_486") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_485") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="admin_poll" value="1" <?php if($role->admin_poll) echo "checked" ?>>
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_33") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_482") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="banned" value="1" <?php if($role->banned) echo "checked" ?>>
                        </div>
            </div>
            <div class="form-group">
                        <label for="username-in" class="col-md-4 label-heading"><?php echo lang("ctn_484") ?> <span class="glyphicon glyphicon-question-sign" title="<?php echo lang("ctn_483") ?>"></span></label>
                        <div class="col-md-8">
                            <input type="checkbox" name="poll_creator" value="1" <?php if($role->poll_creator) echo "checked" ?>>
                        </div>
            </div>
           
      </div>
            <hr>

<input type="submit" class="form-control btn btn-primary" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>
</div>