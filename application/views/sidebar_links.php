<?php if(!$this->user->loggedin) header("login"); ?>

<ul class="newnav nav nav-sidebar">
           <?php if($this->user->loggedin && isset($this->user->info->user_role_id) && 
           ($this->user->info->admin || $this->user->info->admin_settings || $this->user->info->admin_members || $this->user->info->admin_payment)

           ) : ?>
              <li id="admin_sb">
                <a data-toggle="collapse" data-parent="#admin_sb" href="#admin_sb_c" class="collapsed <?php if(isset($activeLink['admin'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-wrench sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_157") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['admin'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="admin_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['admin'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <?php if($this->user->info->admin || $this->user->info->admin_settings) : ?>
                      <li class="<?php if(isset($activeLink['admin']['settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/settings") ?>"> <?php echo lang("ctn_158") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                    <li class="<?php if(isset($activeLink['admin']['members'])) echo "active" ?>"><a href="<?php echo site_url("admin/members") ?>"> <?php echo lang("ctn_160") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin) : ?>
                    <li class="<?php if(isset($activeLink['admin']['user_roles'])) echo "active" ?>"><a href="<?php echo site_url("admin/user_roles") ?>"> <?php echo lang("ctn_470") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                    <li class="<?php if(isset($activeLink['admin']['user_groups'])) echo "active" ?>"><a href="<?php echo site_url("admin/user_groups") ?>"> <?php echo lang("ctn_161") ?></a></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </li>
			  
				<?php if($this->common->has_permissions(array("admin", "admin_poll"), $this->user)) : ?>
                    <li class="<?php if(isset($activeLink['admin_poll']['manage'])) echo "active" ?>"><a href="<?php echo site_url("admin/manage_polls") ?>"><span class="glyphicon glyphicon-th-list sidebar-icon sidebar-icon-red"></span><?php echo lang("ctn_298") ?></a></li>
				<?php endif; ?>
            <?php endif; ?>
            <li class="<?php if(isset($activeLink['home']['general'])) echo "active" ?>"><a href="<?php echo site_url() ?>"><span class="glyphicon glyphicon-home sidebar-icon sidebar-icon-orange"></span> <?php echo lang("ctn_154") ?> <span class="sr-only">(current)</span></a></li>
            <li class="<?php if(isset($activeLink['poll']['active'])) echo "active" ?>"><a href="<?php echo site_url("polls/all") ?>"><span class="glyphicon glyphicon-stats sidebar-icon sidebar-icon-brown"></span> <?php echo lang("ctn_494") ?></a></li>
            <?php if($this->common->has_permissions(array("admin", "poll_creator"), $this->user)) : ?>
            <li class="<?php if(isset($activeLink['poll']['active'])) echo "active" ?>"><a href="<?php echo site_url("polls") ?>"><span class="glyphicon glyphicon-stats sidebar-icon"></span> <?php echo lang("ctn_301") ?></a></li>
            <li class="<?php if(isset($activeLink['poll']['archived'])) echo "active" ?>"><a href="<?php echo site_url("polls/archived") ?>"><span class="glyphicon glyphicon-tasks sidebar-icon"></span> <?php echo lang("ctn_302") ?></a></li>
          <?php endif; ?>

            <li class="<?php if(isset($activeLink['members']['general'])) echo "active" ?>"><a href="<?php echo site_url("members") ?>"><span class="glyphicon glyphicon-user sidebar-icon sidebar-icon-green"></span> <?php echo lang("ctn_155") ?></a></li>
            <li class="<?php if(isset($activeLink['settings']['general'])) echo "active" ?>"><a href="<?php echo site_url("user_settings") ?>"><span class="glyphicon glyphicon-cog sidebar-icon sidebar-icon-grey"></span> <?php echo lang("ctn_156") ?></a></li>
			<li class="<?php if(isset($activeLink['politics'])) echo "active" ?>"><a href="<?php echo site_url("politics") ?>"><span class="glyphicon glyphicon-list-alt sidebar-icon sidebar-icon-brown"></span> <?php echo lang("ctn_509") ?></a></li>
			<li class="<?php if(isset($activeLink['rules'])) echo "active" ?>"><a href="<?php echo site_url("rules") ?>"><span class="glyphicon glyphicon-list-alt sidebar-icon sidebar-icon-brown"></span> <?php echo lang("ctn_521") ?></a></li>
          </ul>