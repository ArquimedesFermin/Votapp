<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_199") ?> <?php echo $user->username ?></div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"> <?php echo lang("ctn_199") ?> <?php echo $user->username ?></li>
</ol>


<div class="">
	<div class="">
		<div class="profile-user">
		<table>
		<tr>
		<td width="95">
		<img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $user->avatar ?>">
		</td>
		<td valign="top">
		<h4><?php echo $user->username ?></h4><p class="user_level_display"><?php echo $this->common->getAccessLevel($user->user_level) ?></p>
		</td>
		</tr>
		</table>
		</div>
		<div class="profile-info">
		<table class="table-profile">
		<tr><td class="profile-info-label"><font color="#404040"><?php echo lang("ctn_201") ?></font></td><td class="profile-info-content"><?php echo $user->first_name ?> <?php echo $user->last_name ?></td></tr>
		<tr><td class="profile-info-label"><font color="#404040"><?php echo lang("ctn_202") ?></font></td><td class="profile-info-content"><?php echo date($this->settings->info->date_format, $user->joined) ?></td></tr>
		<tr><td class="profile-info-label"><font color="#404040"><?php echo lang("ctn_203") ?></font></td><td class="profile-info-content"><?php echo date($this->settings->info->date_format, $user->online_timestamp) ?></td></tr>
		</table>
		</div>
		<div class="profile-info-p2">
		<h5><?php echo $tag; ?></h5>
		<?php if($groups->num_rows() > 0) : ?>
			<?php foreach($groups->result() as $r) : ?>
				<label class="label label-<?php echo $st; ?>"><?php echo $r->name ?></label>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
	<div class="">
		<div class="profile-main-content">
		<h4 class="home-label"><?php echo lang("ctn_205") ?></h4>
		<p><?php echo nl2br($user->aboutme) ?></p>
		</div>
	</div>
</div>
</div></div>