<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="row">

<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #62acec; border-left: 5px solid #5798d1;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-book giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($stats->total_members) ?></span><br /><?php echo lang("ctn_136") ?>
	</div>
</div>
</div>

<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #5cb85c; border-left: 5px solid #4f9f4f;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-plus giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($stats->new_members) ?></span><br /><?php echo lang("ctn_137") ?>
	</div>
</div>
</div>
<!--
<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #f0ad4e; border-left: 5px solid #d89b45;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-folder-close giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($stats->active_today) ?></span><br /><?php echo lang("ctn_138") ?>
	</div>
</div>
</div>
-->
<div class="col-md-3">
<div class="dashboard-window clearfix" style="background: #d9534f; border-left: 5px solid #b94643;">
	<div class="d-w-icon">
		<span class="glyphicon glyphicon-user giant-white-icon"></span>
	</div>
	<div class="d-w-text">
		 <span class="d-w-num"><?php echo number_format($online_count) ?></span><br /><?php echo lang("ctn_139") ?>
	</div>
</div>
</div>

</div>

<hr>


<div class="row">
<div class="col-md-8">
<div class="block-area align-center">
<h4 class="home-label"><?php echo lang("ctn_140") ?></h4>
<canvas id="myChart" class="graph-height"></canvas>
</div>
</div>
<div class="col-md-4">

<div class="block-area">
<h4 class="home-label"><?php echo lang("ctn_141") ?></h4>
<div class="table-responsive">
<table class="table table-bordered small-text">
<tr class="table-header"><td><?php echo lang("ctn_142") ?></td><td><?php echo lang("ctn_143") ?></td></tr>
<?php foreach($new_members->result() as $r) : ?>
	<?php
		if($r->joined + (3600*24) > time()) {
			$joined = lang("ctn_144");
		} else {
			$joined = date($this->settings->info->date_format, $r->joined);
		}

	?>
<tr><td><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->username ?></a></td><td><?php echo $joined ?></td></tr>
<?php endforeach; ?>
</table>
</div>
</div>
<!--
<div class="block-area align-center" id="membersTypeChatArea">
<h4 class="home-label"><?php echo lang("ctn_145") ?></h4>
<canvas id="memberTypesChart"></canvas>
</div>
-->
</div>
</div>
</div>