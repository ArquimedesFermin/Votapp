<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">

         <!-- Styles -->
        <link href="<?php echo base_url();?>styles/layouts/basic/main.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>styles/layouts/basic/dashboard.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>styles/layouts/basic/responsive.css" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,500,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />

        <!-- SCRIPTS -->
        <script type="text/javascript">
        var global_base_url = "<?php echo site_url('/') ?>";
        var global_hash = "<?php echo $this->security->get_csrf_hash() ?>";
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <?php if($this->settings->info->auto_updating) : ?>
            <script src="<?php echo base_url();?>scripts/custom/auto-update.js"></script>
        <?php endif; ?>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        

        <script type="text/javascript">
            $(document).ready(function() {
                $( document ).tooltip();
            });
        </script>

        <!-- CODE INCLUDES -->
        <?php if(isset($cssincludes)) : ?>
            <?php echo $cssincludes ?> 
        <?php endif; ?>

        <?php if(!isset($poll->css_code)) : ?>
            <link href="'. base_url() .'styles/user_poll.css" 
            rel="stylesheet" type="text/css">
        <?php else : ?>
            <style type="text/css">
                <?php echo $poll->css_code ?>
            </style>
        <?php endif; ?>

        <style type="text/css">
body {background:none transparent; padding-top: 0px !important;
}
</style>
    </head>
    <body>
<br>
<br>
<center>
<?php
// Convert timestamp to days hours mins
  $time = $this->common->convert_time($poll->timestamp);
  unset($time['secs']);
?>
<input type="hidden" id="pollid" value="<?php echo $poll->ID ?>">
<input type="hidden" id="pollhash" value="<?php echo $poll->hash ?>">

<?php $gl = $this->session->flashdata('globalmsg'); ?>

        <?php if(!empty($gl)) :?>
           <div class="alert alert-success"><b><span class="glyphicon glyphicon-ok"></span></b> <h4><?php echo $this->session->flashdata('globalmsg') ?></h4></div>
        <?php endif; ?>

<div class="user-poll clearfix" id="poll-area">

<?php 
if($poll->timestamp > time()):
	$status = $this->common->convert_time($poll->timestamp);
	unset($status['secs']);
	$status = "";
elseif($poll->timestamp > 0):
		$status = '<left><span class="label label-danger">'. lang("ctn_384") .'</span></left>';
else:
		$status = "";
endif;
?>

<div> <b><center> <?php echo $poll->name ."  ".$status?> </center></b> </div>
<hr />
<?php echo $poll->question ?>
<hr>
<?php if( (!$poll->ip_restricted || $user_vote_num ==0) && ($poll->timestamp == 0 || $poll->timestamp > time())) : ?> 
<?php echo form_open(site_url("polls/vote_poll/" . $poll->ID . "/" . $poll->hash . "/1")); ?>
<?php endif; ?>
<?php foreach($answers->result() as $r) : ?>
<div class="answer-poll" id="poll-answer-<?php echo $r->ID ?>">
<label class="answer-label" for="answer-input-<?php echo $r->ID ?>">
<?php if($r->image) : ?>
<div class="answer-image">
<a href="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" target="_blank"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" height="40" width="40"></a>
</div>
<?php endif; ?>
<?php echo $r->answer ?> 
<?php if( ($user_vote_num > 0 && $poll->show_results) || ($poll->show_results && $poll->timestamp < time() && $poll->timestamp != 0)) : ?>
<span class="small-text" style="font-weight: normal;">(<?php echo $r->votes ?> <?php if($r->votes > 1 || $r->votes == 0) : ?><?php echo lang("ctn_426") ?><?php else : ?><?php echo lang("ctn_427") ?><?php endif; ?>)</span>
<?php endif; ?>
<?php if( (!$poll->ip_restricted || $user_vote_num ==0) && ($poll->timestamp == 0 || $poll->timestamp > time())) : ?> 
<?php if($poll->vote_type == 0) :?>
<input type="radio" name="poll_answer" class="poll-voting-box" id="answer-input-<?php echo $r->ID ?>" value="<?php echo $r->ID ?>">
<?php else : ?>
<input type="checkbox" name="poll_answer_<?php echo $r->ID ?>" class="poll-voting-box" id="answer-input-<?php echo $r->ID ?>" value="<?php echo $r->ID ?>">
<?php endif; ?>
<?php endif; ?>
</label>
<?php if( ($user_vote_num > 0 && $poll->show_results) || ($poll->show_results && $poll->timestamp < time() && $poll->timestamp != 0)) : ?>
	<?php $vote_percent = @intval(($r->votes/$poll->votes) * 100); ?>
<div class="progress">
	<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $vote_percent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $vote_percent ?>%; min-width: 15px;">
		<span class="" title="(<?php echo $vote_percent ?>%)"><?php echo $vote_percent ?> %</span>
	</div>
</div>
<?php endif; ?>
</div>
<?php endforeach; ?>
<?php if($user_vote_num > 0 && $poll->show_results) : ?>
<?php echo lang("ctn_428") ?>: <?php echo $poll->votes ?>
<?php endif; ?>
<hr>
<?php if($poll->timestamp > time() ) : ?>
<div class="poll-expire">
<?php echo lang("ctn_313") ?>: <?php echo $this->common->get_time_string($time); ?>
</div>
<?php endif; ?>
<?php if(!$this->user->info->admin && (!$poll->ip_restricted || $user_vote_num ==0) && ($poll->timestamp == 0 || $poll->timestamp > time())) : ?> 
<?php if(!$this->user->info->poll_creator) : ?>
<?php if($poll->userid !== $this->user->info->ID): ?>
<div>
<input type="submit" class="btn btn-primary btn-sm pull-center vote-button" value="<?php echo lang("ctn_426") ?>" />
 </div>
 <hr>
<?php else: ?>
<span><?php echo lang("ctn_557") ?></span>
<?php endif; ?>
<?php else: ?>
<span><?php echo lang("ctn_558") ?></span>
<?php endif; ?><?php echo form_close() ?>
<?php endif; ?>
 <?php if($poll->ex_a == 0):?>
<div class="alert alert-success" role="alert">
 <?php echo lang("ctn_570"); ?>
</div>
<?php elseif($poll->ex_a == 1):?>
<div class="alert alert-success" role="alert">
 <?php echo lang("ctn_571"); ?>
</div>
<?php endif; ?>
</div>
<?php if($this->settings->info->enable_ads) : ?>
    <hr>
<?php include("adsense.php"); ?>
<?php endif; ?>






</body>
</html>