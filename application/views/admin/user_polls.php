<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_298") ?></li>
</ol>

<p><?php echo lang("ctn_327") ?> <a href="<?php echo site_url("admin/edit_member/" . $user->ID) ?>"><?php echo $user->username ?></a>.</p>

<hr>


<table class="table table-bordered">
<tr class="table-header"><td><?php echo lang("ctn_328") ?></td><td><?php echo lang("ctn_329") ?></td><td><?php echo lang("ctn_330") ?></td><td><?php echo lang("ctn_331") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($polls->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><?php echo number_format($r->votes) ?></td><td>
	<?php if($r->status == 1) : ?>
    <label class="label label-success" id="status_button"><?php echo lang("ctn_332") ?></label>
	<?php elseif($r->status == 2) : ?>
	<label class="label label-info" id="status_button"><?php echo lang("ctn_333") ?></label>
	<?php else : ?>
	<label class="label label-default" id="status_button"><?php echo lang("ctn_334") ?></label>
	<?php endif; ?>
<?php if($r->ex_a == 1 && $r->ex_r==1): ?>
	
</td><td><a href="<?php echo site_url("admin/user_polls/" . $r->userid) ?>"><?php echo $r->username ?></a></td><td><a href="<?php echo site_url("polls/view_poll2/" . $r->ID . "/" . $r->hash ."/1") ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_335") ?></a> <a href="<?php echo site_url("admin/delete_poll/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" onclick="return confirm('<?php echo lang("ctn_336") ?>')" class="btn btn-danger btn-xs"><?php echo lang("ctn_57") ?></a></td></tr>

<? else: ?>

</td><td><a href="<?php echo site_url("admin/user_polls/" . $r->userid) ?>"><?php echo $r->username ?></a></td><td><a href="<?php echo site_url("polls/view_poll/" . $r->ID . "/" . $r->hash) ?>" class="btn btn-primary btn-xs"><?php echo lang("ctn_335") ?></a> <a href="<?php echo site_url("admin/delete_poll/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" onclick="return confirm('<?php echo lang("ctn_336") ?>')" class="btn btn-danger btn-xs"><?php echo lang("ctn_57") ?></a></td></tr>

<?php endif; ?>
<?php endforeach; ?>
</table>

<div class="align-center">
<?php echo $this->pagination->create_links() ?>
</div>
</div>