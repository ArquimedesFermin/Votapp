<div class="white-area-content">
<div class="row">




<?php if($this->user->info->poll_creator || $this->user->info->voter) : ?>

<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #62acec; border-left: 5px solid #5798d1;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-comment giant-white-icon"></span>
	</div>
	<div class="d-w-text">
<?php if($this->user->info->poll_creator) : ?>
		 <span class="d-w-num"><?php echo number_format($polls_admin_all->num_rows()) ?></span><br /><?php echo lang("ctn_351") ?>
<?php elseif($this->user->info->voter) : ?>
		 <span class="d-w-num"><?php echo number_format($polls_all->num_rows()) ?></span><br /><?php echo lang("ctn_581") ?>
<?php endif; ?>
	</div>
</div>
</div>


<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #5cb85c; border-left: 5px solid #4f9f4f;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-comment giant-white-icon"></span>
	</div>
	<div class="d-w-text">
<?php if($this->user->info->poll_creator) : ?>
		 <span class="d-w-num"><?php echo number_format($polls_admin_active->num_rows()) ?></span><br /><?php echo lang("ctn_601") ?>
<?php elseif($this->user->info->voter) : ?>
		 <span class="d-w-num"><?php echo number_format($polls_active->num_rows()) ?></span><br /><?php echo lang("ctn_601") ?>
<?php endif; ?>
	</div>
</div>
</div>


<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #d9534f; border-left: 5px solid #b94643;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-comment giant-white-icon"></span>
	</div>
	<div class="d-w-text">
<?php if($this->user->info->poll_creator) : ?>
		 <span class="d-w-num"><?php echo number_format($polls_admin_inactive->num_rows()) ?></span><br /><?php echo lang("ctn_602") ?>
<?php elseif($this->user->info->voter) : ?>
		 <span class="d-w-num"><?php echo number_format($polls_inactive->num_rows()) ?></span><br /><?php echo lang("ctn_602") ?>
<?php endif; ?>
	</div>
</div>
</div>

<?php if($this->user->info->voter) : ?>
<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #f0ad4e; border-left: 5px solid #d89b45;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-ok-sign giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($user_votes->num_rows()) ?></span><br /><?php echo lang("ctn_352") ?>
	</div>
</div>
</div>
<?php endif; ?>
</div>

<hr>
<?php endif; ?>

<div class="row">

<div class="col-md-6">

<div class="panel panel-default">
<div class="panel-body small-text">
<h4 class="home-label"><?php echo lang("ctn_506") ?></h4>

<?php date_default_timezone_set ('America/La_Paz'); ?> 
<p><?php echo lang("ctn_433") ?>: <b><?php echo  date("d/m/Y h:i a" ,$this->user->info->online_timestamp) ?></b></p>
</div>
</div>

<?php if($this->common->has_permissions(array("admin", "admin_poll"), $this->user)) : ?>
<div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_356") ?></h4>

<table class="table table-bordered small-text">
<tr class="table-header"><td><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_52") ?></td><td><?php echo lang("ctn_591") ?></td></tr>

<?php foreach($polls_admin_active->result() as $r) : ?>
<?php if($r->status==1) : ?>
<?php $public = '<span class="label label-default"></span>'; ?> 
<?php if($r->public == 0): ?> 
<?php $public = '<center><span class="label label-success"> <strong>Público</strong></span>'; ?>
<?php elseif($r->public == 1): ?> 
<?php $public = '<center><span class="label label-danger"> <strong>Privado</strong> </span>'; ?>
<?php endif; ?>

<tr><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("polls/edit_poll/" . $r->ID) ?>"  class="btn btn-warning btn-xs"><?php echo lang("ctn_358") ?></a></td><td> <?php echo $public; ?> </td></tr>
<?php endif; ?>
<?php endforeach; ?>
</table>
</div>
</div>

<?php elseif($this->user->info->voter || $this->user->info->user_role == 9) : ?>

<div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_614") ?></h4>
<table class="table table-bordered small-text">
<tr class="table-header"><td><?php echo lang("ctn_579") ?></td><td><?php echo lang("ctn_578") ?></td><td><?php echo lang("ctn_330") ?></td><td><?php echo lang("ctn_591") ?></td></tr>
<?php foreach($polls_public->result() as $r) : ?>
<?php if($r->status == 1) : ?>
<?php $span = '<span class="label label-default"></span>'; ?> 
<?php $public = '<span class="label label-default"></span>'; ?> 
 
<?php if($r->status == 0): ?> 
<?php $span = '<center><span class="label label-danger"> Inactivo </span>'; ?>
<?php elseif($r->status == 1): ?>
<?php $span = '<center><span class="label label-success"> Activo </span>'; ?>
<?php elseif($r->status == 2): ?> 
<?php $span = '<center><span class="label label-warning"> Bloqueado </span>'; ?>
<?php endif; ?>

<?php if($r->public == 0): ?> 
<?php $public = '<center><span class="label label-success"> <strong>Público</strong></span>'; ?>
<?php elseif($r->public == 1): ?> 
<?php $public = '<center><span class="label label-danger"> <strong>Privado</strong> </span>'; ?>
<?php endif; ?>

<?php if($r->ex_a == 1 && $r->ex_r==1): ?>
<tr valign="middle"><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("polls/view_poll2/" . $r->ID . "/" . $r->hash ."/1") ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_335") ?></a></td> <td valign="middle"> <?php echo $span; ?></td><td valign="middle"> <?php echo $public; ?></td></tr>
<?php else: ?>
<tr valign="middle"><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("polls/view_poll/" . $r->ID . "/" . $r->hash) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_335") ?></a></td> <td valign="middle"><?php echo $span; ?></td><td valign="middle"><?php echo $public; ?></td></tr>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>

</table>

</div>
</div>

<!--  00000000000000   -->
<div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_505") ?></h4>
<table class="table table-bordered small-text">
<tr class="table-header"><td><?php echo lang("ctn_579") ?></td><td><?php echo lang("ctn_578") ?></td><td><?php echo lang("ctn_330") ?></td><td><?php echo lang("ctn_591") ?></td></tr>
<?php foreach($polls_assoc->result() as $r) : ?>
<?php if($r->status == 1): ?> 
<?php $span = '<span class="label label-default"></span>'; ?> 
<?php $public = '<span class="label label-default"></span>'; ?> 
 
<?php if($r->status == 0): ?> 
<?php $span = '<center><span class="label label-danger"> Inactivo </span>'; ?>
<?php elseif($r->status == 1): ?>
<?php $span = '<center><span class="label label-success"> Activo </span>'; ?>
<?php elseif($r->status == 2): ?> 
<?php $span = '<center><span class="label label-warning"> Bloqueado </span>'; ?>
<?php endif; ?>

<?php if($r->public == 0): ?> 
<?php $public = '<center><span class="label label-success"> <strong>Público</strong></span>'; ?>
<?php elseif($r->public == 1): ?> 
<?php $public = '<center><span class="label label-danger"> <strong>Privado</strong> </span>'; ?>
<?php endif; ?>

<?php if($r->ex_a == 1 && $r->ex_r==1): ?>
<tr valign="middle"><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("polls/view_poll2/" . $r->ID . "/" . $r->hash ."/1") ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_335") ?></a></td> <td valign="middle"> <?php echo $span; ?></td><td valign="middle"> <?php echo $public; ?></td></tr>
<?php else: ?>
<tr valign="middle"><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("polls/view_poll/" . $r->ID . "/" . $r->hash) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_335") ?></a></td> <td valign="middle"><?php echo $span; ?></td><td valign="middle"><?php echo $public; ?></td></tr>
<?php endif; ?>
<?php endif; ?>

<?php endforeach; ?>

</table>

</div>
</div>

<?php endif; ?>
</div>
</div>
</div>