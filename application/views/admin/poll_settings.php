<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_297") ?></li>
</ol>

<p><?php echo lang("ctn_303") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/poll_settings_pro"), array("class" => "form-horizontal")) ?>

<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_304") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="country_tracking" value="1" <?php if($this->settings->info->country_tracking) echo "checked" ?> />
        <span class="help-block"><?php echo lang("ctn_305") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_306") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="auto_updating" value="1" <?php if($this->settings->info->auto_updating) echo "checked" ?> />
        <span class="help-block"><?php echo lang("ctn_307") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_308") ?></label>
    <div class="col-sm-10">
        <input type="checkbox" name="enable_ads" value="1" <?php if($this->settings->info->enable_ads) echo "checked" ?> />
        <span class="help-block"><?php echo lang("ctn_309") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="dpname-in" class="col-sm-2 control-label"><?php echo lang("ctn_310") ?></label>
    <div class="col-sm-10">
        <input type="text" name="default_votes" value="<?php echo $this->settings->info->default_votes ?>" class="form-control" />
        <span class="help-block"><?php echo lang("ctn_311") ?></span>
    </div>
</div>

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>
</div>