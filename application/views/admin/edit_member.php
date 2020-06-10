<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li><a href="<?php echo site_url("admin/members") ?>"><?php echo lang("ctn_21") ?></a></li>
  <li class="active"><?php echo lang("ctn_22") ?></li>
</ol>

<p><?php echo lang("ctn_23") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("admin/edit_member_pro/" . $member->ID), array("class" => "form-horizontal")) ?>

<div class="form-group">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_24") ?></label>
        <div class="col-md-9">
            <input type="email" class="form-control" id="email-in" name="email" value="<?php echo $member->email ?>">
        </div>
</div>
<div class="form-group">

            <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_25") ?></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $member->username ?>">
                <div id="username_check"></div>
            </div>
</div>
<div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_26") ?></label>
        <div class="col-sm-9">
        <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $member->avatar ?>" />
            <input type="file" name="userfile" /> 
        </div>
    </div>
<div class="form-group">

            <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_27") ?></label>
            <div class="col-md-9">
                <input type="password" class="form-control" id="password-in" name="password" value="">
                <span class="help-text"><?php echo lang("ctn_28") ?></span>
            </div>
    </div>

<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_29") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="name-in" name="first_name" value="<?php echo $member->first_name ?>">
        </div>
</div>
<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_30") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="name-in" name="last_name" value="<?php echo $member->last_name ?>">
        </div>
</div>
<!-- <div class="form-group">

        <label for="name-in" class="col-md-3 label-heading">Credits</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="name-in" name="credits" value="<?php echo $member->points ?>">
        </div>
</div> -->
<div class="form-group">
        <label for="inputEmail3" class="col-sm-3 label-heading"><?php echo lang("ctn_31") ?></label>
        <div class="col-sm-9">
          <textarea class="form-control" name="aboutme" rows="8"><?php echo nl2br($member->aboutme) ?></textarea>
        </div>
</div>
<div class="form-group">

        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_36") ?></label>
        <div class="col-md-9">
            <?php echo lang("ctn_37") ?> : <?php echo $member->IP ?> <br />
            <?php echo lang("ctn_38") ?> : <?php echo date($this->settings->info->date_format, $member->joined) ?><br />
            <?php echo lang("ctn_39") ?> : <?php echo date($this->settings->info->date_format, $member->online_timestamp) ?>
        </div>
</div>
<div class="form-group">

<?php if($member->user_role==8){?>
	<label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_583") ?></label>

<?php if($groups->num_rows() > 0) : ?>
			<div class="col-md-9">
			<?php foreach($groups->result() as $r) : ?>
			<p><a href="<?php echo site_url("admin/view_group/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
			<?php endforeach; ?>
			<?php else: ?>
			<option value="NULL">**<?php echo lang("ctn_46") ?>**</option>
		<?php endif; ?>
</div>
<?php
}else{
?>
		<label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_40") ?></label>
		<div class="col-md-9">
		<?php foreach($user_groups->result() as $r) : ?>
			<p><a href="<?php echo site_url("admin/view_group/" . $r->groupid) ?>"><?php echo $r->name ?></a></p>
		<?php endforeach; ?>
	</div  >

<?php } ?>
</div>
<!--		
</div>
<?php if($plan->num_rows() >0 ) :?>
    <?php
    $plan = $plan->row();
    $time = $this->common->convert_time($member->premium_time); ?>
<div class="form-group">
        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_312") ?></label>
        <div class="col-md-9">
            <p><?php echo $plan->name ?></p>
            <p><?php echo lang("ctn_313") ?>: <?php echo $this->common->get_time_string($time) ?></p>
        </div>
</div>
<?php endif; ?>

<div class="form-group">
        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_323") ?></label>
        <div class="col-md-9">
            <p><a href="<?php echo site_url("admin/user_polls/" . $member->ID) ?>" class="btn btn-info btn-xs"><?php echo lang("ctn_324") ?></a></p>
        </div>
</div>
-->
<div class="form-group">
                        <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_455") ?></label>
                        <div class="col-md-9">
                            <select name="user_role" class="form-control">
                            <option value="0" selected><?php echo lang("ctn_46") ?></option>
                            <?php foreach($user_roles->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if($r->ID == $member->user_role) echo "selected" ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                </div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>
</div>