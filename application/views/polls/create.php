<?php 
if(!empty($_GET["saved"])){
$gettin = $_GET["saved"];
if($gettin==true):
echo 'checkCookie()';
endif; 
}
?>

<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-stats"></span> <?php echo lang("ctn_351") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("polls") ?>"><?php echo lang("ctn_359") ?></a></li>
  <li class="active"><?php echo lang("ctn_360") ?></li>
</ol>

<p><?php echo lang("ctn_361") ?></p>
<p><?php echo lang("ctn_540") ?> <a href="<?php echo site_url("politics") ?>"><?php echo lang("ctn_509") ?></a> / <a href="<?php echo site_url("politics") ?>"><?php echo lang("ctn_520") ?></a></p>


<hr>

<div class="panel panel-default" onload="visible();">
  <div class="panel-heading"><?php echo lang("ctn_360") ?></div>
  <div class="panel-body">   
	<?php echo form_open(site_url("polls/create_pro"), array("class" => "form-horizontal")) ?>
	
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_362") ?></label>
	    <div class="col-sm-10">
	      <input id="name" type="text" class="form-control" name="name" value="">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_363") ?></label>
	    <div class="col-sm-10">
	      <textarea id="question" name="question" rows="3" class="form-control"></textarea>
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
	    <select id="days" name="days" class="form-control">
	    <option value="0">0</option>
	    <?php for($i=1;$i<=365;$i++) : ?>
	    	<option value="<?php echo $i ?>"><?php echo $i ?> <?php echo lang("ctn_277") ?></option>
	    <?php endfor; ?>
	    </select>
	    </div>
	    <div class="col-sm-3"><p><?php echo lang("ctn_278") ?></p>
	    <select id="hours" name="hours" class="form-control">
	    <option value="0">0</option>
	    <?php for($i=1;$i<=24;$i++) : ?>
	    	<option value="<?php echo $i ?>"><?php echo $i ?> <?php echo lang("ctn_278") ?></option>
	    <?php endfor; ?>
	    </select>
	    </div>
	    <div class="col-sm-3"><p><?php echo lang("ctn_378") ?></p>
	    <select id="minutes" name="minutes" class="form-control">
	    <option value="0">0</option>
	    <?php for($i=1;$i<=60;$i++) : ?>
	    	<option value="<?php echo $i ?>"><?php echo $i ?> <?php echo lang("ctn_378") ?></option>
	    <?php endfor; ?>
	    </select>
	    </div>
	</div>
	<div class="form-group">
	<label for="inputEmail3" class="col-sm-2 control-label"></label>
	    <div class="col-sm-10">
		<span class="help-text"><?php echo lang("ctn_366") ?></span>
		</div>
	</div>
	
	<div id="publics" class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_495") ?></label>
	    <div class="col-sm-10">
	      <select id="public" name="public" class="form-control" onchange="visible();">
	      <option value="0" selected><?php echo lang("ctn_496") ?></option>
	      <option value="1"><?php echo lang("ctn_497") ?></option>
	      </select>
	      <span class="help-text"><?php echo lang("ctn_498") ?></span>
	    </div>
	</div>

	<div name="groups" class="form-group" id="groups">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_15") ?></label>
	    <div class="col-sm-10">
	      <select name="groupid" class="form-control">
		  
			<?php if($groups->num_rows() > 0) : ?>
				<?php foreach($groups->result() as $r) : ?>
						<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
				<?php endforeach; ?>
				<?php else: ?>
				<option value="NULL">**<?php echo lang("ctn_46") ?>**</option>
			<?php endif; ?>
			
	      </select>
	      <span class="help-text"> <?php echo lang("ctn_309") ?> <a onclick="saveData();" class="btn btn-success btn-sm"><?php echo lang("ctn_15") ?></a></span>
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
	<label for="inputEmail3" class="col-sm-5 control-label" style="font-size:20px"> <?php echo lang("ctn_584") ?> </span>
	<br />
	<br />
	</div>

	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_548") ?></label>
	    <div class="col-sm-3">
	      <select id="ex_a" name="ex_a" class="form-control" onchange="exa();">
	      <option value="0"><?php echo lang("ctn_549") ?></option>
	      <option value="1"><?php echo lang("ctn_550") ?></option>
	      </select>
	      <span class="help-text"><?php echo lang("ctn_551") ?></span>
	    </div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_552") ?></label>
	    <div class="col-sm-3">
	      <select id="ex_r" name="ex_r" class="form-control" onchange="exr();">
	      <option value="0"><?php echo lang("ctn_553") ?></option>
	      <option value="1"><?php echo lang("ctn_554") ?></option>
	      </select>
	      <span class="help-text"><?php echo lang("ctn_555") ?></span>
	    </div>
	</div></div>
		
	<!--<div class="form-group" hidden>
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_372") ?></label>
	    <div class="col-sm-10">
	      <select id="vote_type" name="vote_type" class="form-control">
	      <option value="0"><?php echo lang("ctn_373") ?></option>
	      <option value="1"><?php echo lang("ctn_374") ?></option>
	      </select>
	      <span class="help-text"><?php echo lang("ctn_375") ?></span>
	    </div>
	</div>-->
	<hr>
	<input onclick="resetData()" type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_360") ?>">
	<?php echo form_close() ?>
  </div>
</div>

</div>

<script>
checkCookie();
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

function setCookie(cookieName,cookieValue,daysToExpire)
        {
          var date = new Date();
          date.setTime(date.getTime()+(daysToExpire*24*60*60*1000));
          document.cookie = cookieName + "=" + cookieValue + "; expires=" + date.toGMTString();
        }
		
function getCookie(cookieName)
{
  var name = cookieName + "=";
  var allCookieArray = document.cookie.split(';');
  for(var i=0; i<allCookieArray.length; i++)
  {
	var temp = allCookieArray[i].trim();
	if (temp.indexOf(name)==0)
	return temp.substring(name.length,temp.length);
  }
	return "";
}

function checkCookie() {
	if(getCookie("name") !="" || getCookie("name") !=null) document.getElementById("name").value = getCookie("name");
	if(getCookie("question") !="" || getCookie("question")!=null) document.getElementById("question").value = getCookie("question");
	if(getCookie("days") !="" || getCookie("days")!=null) document.getElementById("days").value = getCookie("days");
	if(getCookie("hours") !="" || getCookie("hours")!=null) document.getElementById("hours").value = getCookie("hours");
	if(getCookie("minutes") !="" || getCookie("minutes")!=null) document.getElementById("minutes").value = getCookie("minutes");
	if(getCookie("public") !="" || getCookie("public")!=null) document.getElementById("public").value = getCookie("public");
	if(getCookie("votes_limit") !="" || getCookie("votes_limit")!=null) document.getElementById("votes_limit").value = getCookie("votes_limit");
	if(getCookie("ex_a") !="" || getCookie("ex_a")!=null) document.getElementById("ex_a").value = getCookie("ex_a");
	if(getCookie("ex_r") !="" || getCookie("ex_r")!=null) document.getElementById("ex_r").value = getCookie("ex_r");
}

function saveData() {

setCookie("name",document.getElementById("name").value,1);
setCookie("question",document.getElementById("question").value,1);
setCookie("days",document.getElementById("days").value,1);
setCookie("hours",document.getElementById("hours").value,1);
setCookie("minutes",document.getElementById("minutes").value,1);
setCookie("public",document.getElementById("public").value,1);
setCookie("votes_limit",document.getElementById("votes_limit").value,1);
setCookie("ex_a",document.getElementById("ex_a").value,1);
setCookie("ex_r",document.getElementById("ex_r").value,1);
setCookie("saved","true",1);

window.location.href = '../admin/user_groups?saved=true';
}

function resetData() {
setCookie("name","",1);
setCookie("question","",1);
setCookie("days","",1);
setCookie("hours","",1);
setCookie("minutes","",1);
setCookie("public","",1);
setCookie("votes_limit","",1);
setCookie("ex_a","",1);
setCookie("ex_r","",1);
setCookie("saved","false",1);
}




</script>
