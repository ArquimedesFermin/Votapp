<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-cog"></span> <?php echo lang("ctn_224") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("user_settings/change_password") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_225") ?></a>
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_224") ?></li>
</ol>

<p><?php echo lang("ctn_226") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<p class="panel-subheading"><?php echo lang("ctn_227") ?></p>
<?php echo form_open_multipart(site_url("user_settings/pro"), array("class" => "form-horizontal")) ?>
	
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_215") ?></label>
	    <div class="col-sm-10">
	      <a href="<?php echo site_url("profile/" . $this->user->info->username) ?>"><?php echo $this->user->info->username ?></a>
	    </div>
	</div>


	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_229") ?></label>
	    <div class="col-sm-10">
	    <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->user->info->avatar ?>" />
	    <?php if($this->settings->info->avatar_upload) : ?>
	     	<input type="file" name="userfile" /> 
	     <?php endif; ?>
	    </div>
	</div>
    <div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_230") ?></label>
	    <div class="col-sm-10">
	      <input type="email" class="form-control" name="email" value="<?php echo $this->user->info->email ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_231") ?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="first_name" value="<?php echo $this->user->info->first_name ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_232") ?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="last_name" value="<?php echo $this->user->info->last_name ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_233") ?></label>
	    <div class="col-sm-10">
	      <textarea class="form-control" name="aboutme" rows="8"><?php echo nl2br($this->user->info->aboutme) ?></textarea>
	    </div>
	</div>
	
	<p class="panel-subheading"><?php echo lang("ctn_234") ?></p>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_235") ?></label>
	    <div class="col-sm-10">
	      <input type="checkbox" name="enable_email_notification" value="1" <?php if($this->user->info->email_notification) echo "checked" ?>>
	    </div>
	</div>
	 <input type="submit" name="s" value="<?php echo lang("ctn_236") ?>" class="btn btn-primary form-control" />
<?php echo form_close() ?>
</div>
</div>
</div>