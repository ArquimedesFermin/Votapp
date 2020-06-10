<?php
// Convert timestamp to days hours mins
  $time = $this->common->convert_time($poll->timestamp);
  unset($time['secs']);
?>
<input type="hidden" id="pollid" value="<?php echo $poll->ID ?>">
<input type="hidden" id="pollhash" value="<?php echo $poll->hash ?>">


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
<input type="submit" class="btn btn-primary btn-sm pull-center vote-button" value="<?php echo lang("ctn_427") ?>" />
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
