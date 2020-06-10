<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_351") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("polls") ?>"><?php echo lang("ctn_359") ?></a></li>
  <li><a href="<?php echo site_url("polls/edit_poll/" . $poll->ID) ?>"><?php echo lang("ctn_358") ?></a></li>
  <li class="active"><?php echo lang("ctn_379") ?></li>
</ol>

<hr>

<?php 
  // Convert timestamp to days hours mins
  $time = $this->common->convert_time($poll->timestamp);
  $disabled = '';
  if($time < time()){
	  $disabled = 'readonly';
  }

?>

<div class="panel panel-default" onload="visible();">
  <div class="panel-heading"><?php echo lang("ctn_379") ?></div>
  <div class="panel-body"> 
	<?php echo form_open(site_url("polls/edit_poll_pro_pro/" . $poll->ID), array("class" => "form-horizontal")) ?>
	
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_362") ?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="name" value="<?php echo $poll->name ?>" <?php echo $disabled; ?>>
	    </div>
	</div>
	
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_363") ?></label>
	    <div class="col-sm-10">
	      <textarea name="question" rows="3" class="form-control" <?php echo $disabled; ?>><?php echo nl2br($poll->question) ?></textarea>
	    </div>
	</div>
	<div class="form-group">
	<hr />
	<label for="inputEmail3" class="col-sm-3 control-label" style="font-size:20px"> <?php echo lang("ctn_507") ?> </span>
	<br />
	<br />
	</div>
	
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_365") ?></label>
	    <div class="col-sm-3"><p><?php echo lang("ctn_277") ?></p>
	    <select name="days" class="form-control">
	    <option value="0">0</option>
	    <?php for($i=1;$i<=365;$i++) : ?>
	    	<option value="<?php echo $i ?>" <?php if($i == $time['days']) echo "selected" ?>><?php echo $i ?> <?php echo lang("ctn_277") ?></option>
	    <?php endfor; ?>
	    </select>
	    </div>
	    <div class="col-sm-3"><p><?php echo lang("ctn_278") ?></p>
	    <select name="hours" class="form-control">
	    <option value="0">0</option>
	    <?php for($i=1;$i<=24;$i++) : ?>
	    	<option value="<?php echo $i ?>" <?php if($i == $time['hours']) echo "selected" ?>><?php echo $i ?> <?php echo lang("ctn_278") ?></option>
	    <?php endfor; ?>
	    </select>
	    </div>
	    <div class="col-sm-3"><p><?php echo lang("ctn_378") ?></p>
	    <select name="minutes" class="form-control">
	    <option value="0">0</option>
	    <?php for($i=1;$i<=60;$i++) : ?>
	    	<option value="<?php echo $i ?>" <?php if($i == $time['mins']) echo "selected" ?>><?php echo $i ?> <?php echo lang("ctn_378") ?></option>
	    <?php endfor; ?>
	    </select>
	    </div>
	</div>
	<?php if($poll->timestamp < time() && $poll->timestamp > 0) : ?>
		<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_501") ?></label>
		    <div class="col-sm-10">
		    <input type="checkbox" name="reset_expired" value="1" />
			<span class="help-block"><?php echo lang("ctn_502") ?></span>
			</div>
		</div>
	<?php endif;  ?>
	
	<div class="form-group">
	<label for="inputEmail3" class="col-sm-2 control-label"></label>
	    <div class="col-sm-10">
		<span class="help-text"><?php echo lang("ctn_366") ?></span>
		</div>
	</div>
	
	<div id="publics" class="form-group">
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_495") ?></label>
	    <div class="col-sm-10">
		<select id="public" name="public" class="form-control" onchange="visible(this);" <?php echo $disabled; ?>>
		<option value="0"><?php echo lang("ctn_496") ?></option>
	      <option value="1" <?php if($poll->public == 1) echo "selected" ?>><?php echo lang("ctn_497") ?></option>
	      </select>
	      <span class="help-text"><?php echo lang("ctn_498") ?></span>
	    </div>
	</div>
	
	<div class="form-group" id="groups">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_15") ?></label>
	    <div class="col-sm-10">
	      <select name="groupid" id="groupid" class="form-control" <?php echo $disabled; ?>>
		  
			<?php if($groups->num_rows() > 0) : ?>
				<?php foreach($groups->result() as $r) : ?>
				<option value="<?php echo $r->ID ?>" <?php if($poll->groupid == $r->ID) echo "selected"; ?> ><?php echo $r->name ?></option>
				<?php endforeach; ?>
				<?php else: ?>
				<option value="NULL">**<?php echo lang("ctn_46") ?>**</option>
			<?php endif; ?>
			
	      </select>
	      <span class="help-text"> <?php echo lang("ctn_309") ?> <a href="<?php echo site_url("admin/user_groups") ?>"> <?php echo lang("ctn_15") ?></a></span>
	    </div>
	</div>	
	
	
	
	  <div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_504") ?></label>
	    <div class="col-sm-10">
		  <select class="form-control" name="votes_limit" id="votes_limit" value="<?php echo $poll->votes_limit ?>" <?php echo $disabled; ?>>
		  
		  <option value="0" selected>0</option>
		  <option value="1">1</option>
		  <option value="2">2</option>
		  <option value="3">3</option>
		  <option value="4">4</option>
		</select>

		  <span class="help-text"><?php echo lang("ctn_515") ?></span>

	    </div>
	</div>
	

	<div class="form-group">
	<hr />
	<label for="inputEmail3" class="col-sm-4 control-label" style="font-size:20px"> <?php echo lang("ctn_508") ?> </span>
	<br />
	<br />
	</div>
	
		<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_548") ?></label>
	    <div class="col-sm-3">
	      <select name="ex_a" id="ex_a" onchange="exa()" class="form-control" <?php echo $disabled; ?>>
	      <option value="0" <?php if($poll->ex_a ==0) echo "selected" ?>><?php echo lang("ctn_549") ?></option>
	      <option value="1" <?php if($poll->ex_a ==1) echo "selected" ?>><?php echo lang("ctn_550") ?></option>
	      </select>
	      <span class="help-text"><?php echo lang("ctn_551") ?></span>
	    </div>
		
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_552") ?></label>
	    <div class="col-sm-3">
	      <select name="ex_r" id="ex_r" onchange="exr()" class="form-control" <?php echo $disabled; ?>>
	      <option value="0" <?php if($poll->ex_r ==0) echo "selected" ?>><?php echo lang("ctn_553") ?></option>
	      <option value="1" <?php if($poll->ex_r ==1) echo "selected" ?>><?php echo lang("ctn_554") ?></option>
	      </select>
	      <span class="help-text"><?php echo lang("ctn_555") ?></span>
	    </div>
	</div></div>

	<hr>
	<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_380") ?>">

	<?php echo form_close() ?>
  </div>
</div>

</div>

<script>
visible();

function exa(){
	var a = document.getElementById("ex_a");
	var r = document.getElementById("ex_r");
	if(a.value==0){
		r.value = 0;
	}	
	
	if(a.value==1){
		r.value = 1;
	}

}

function exr(){
	var a = document.getElementById("ex_a");
	var r = document.getElementById("ex_r");
	if(r.value==0){
		a.value = 0;
	}	
	
	if(r.value==1){
		a.value = 1;
	}

}


 function visible() {
	var x = document.getElementById("groups");
	if(document.getElementById("public").value == 1){
		if (x.style.display === "none") {
			x.style.display = "block";
		}
	}else{
			x.style.display = "none";
	}
 }
</script>
