<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li><a href="<?php echo site_url("admin/poll_themes") ?>"><?php echo lang("ctn_299") ?></a></li>
  <li class="active"><?php echo lang("ctn_340") ?></li>
</ol>

<p><?php echo lang("ctn_341") ?></p>

<hr>


<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/edit_poll_theme_pro/" . $theme->ID), array("class" => "form-horizontal")) ?>

<div class="form-group">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_342") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="email-in" name="name" value="<?php echo $theme->name ?>">
        </div>
</div>
<div class="form-group">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_343") ?></label>
        <div class="col-md-9">
             <textarea class="form-control" rows="4" name="css_code"><?php echo $theme->css_code ?></textarea>
        </div>
</div>

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>
</div>