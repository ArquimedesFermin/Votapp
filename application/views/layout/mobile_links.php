<div id="responsive-menu-links">
          <select name='link' OnChange="window.location.href=$(this).val();" class="form-control">
          <option value='<?php echo site_url() ?>'><?php echo lang("ctn_154") ?></option>
          <option value="<?php echo site_url("polls/all") ?>"><?php echo lang("ctn_494") ?></option>
          <?php if($this->common->has_permissions(array("admin", "poll_creator"), $this->user)) : ?>
          <option value='<?php echo site_url("polls") ?>'><?php echo lang("ctn_301") ?></option>
          <option value='<?php echo site_url("polls/archived") ?>'><?php echo lang("ctn_302") ?></option>
        <?php endif; ?>
          <option value='<?php echo site_url("members") ?>'><?php echo lang("ctn_155") ?></option>
          <option value='<?php echo site_url("user_settings") ?>'><?php echo lang("ctn_156") ?></option>
          <?php if($this->user->loggedin && isset($this->user->info->user_role_id) && 
           ($this->user->info->admin || $this->user->info->admin_settings || $this->user->info->admin_members || $this->user->info->admin_payment)

           ) : ?>
           <?php if($this->user->info->admin || $this->user->info->admin_settings) : ?>
            <option value='<?php echo site_url("admin/settings") ?>'><?php echo lang("ctn_158") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
            <option value='<?php echo site_url("admin/members") ?>'><?php echo lang("ctn_160") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin) : ?>
            <option value='<?php echo site_url("admin/user_roles") ?>'><?php echo lang("ctn_470") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
            <option value='<?php echo site_url("admin/user_groups") ?>'><?php echo lang("ctn_161") ?></option>
            <?php endif; ?>
          <?php endif; ?>
          </select> 
        </div>