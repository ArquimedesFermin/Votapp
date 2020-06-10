			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
		    				
			<div class="container">
				<div class="row">
				<div class="col-md-5 center-block-e">

			<div class="login-page-header">
			 <?php echo lang("ctn_212") ?> <?php echo $this->settings->info->site_name ?>
			</div>
			
			<div class="login-page" onload="visibles();">
    		<?php if(!empty($fail)) : ?>
				<div class="alert alert-danger" role="alert"><div class="row vertical-align" style="display: flex; align-items: center"><div class="col-md-1 text-right"><span class="glyphicon glyphicon-alert"></span></div><div class="col-xs-11"><b><h5><?php echo $fail ?></h5></b></div></div></div>			<?php endif; ?>
    		<p class="space-content"><?php echo lang("ctn_213") ?></p>

    		<hr>


    		<?php echo form_open(site_url("register"), array("class" => "form-horizontal")) ?>

				<div class="form-group">
				<label for="role-in" class="col-md-3 label-heading"><?php echo lang("ctn_543") ?></label>
				<div class="col-sm-9">
				
				  <select id="user_role" name="user_role" class="form-control" onchange="visibles();" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; }else{ echo "7";} ?>">
					<option value="7"><?php echo lang("ctn_545") ?></option>
					<option value="8"><?php echo lang("ctn_546") ?></option>
					<option value="9"><?php echo lang("ctn_547") ?></option>
				  </select>
				  <span class="help-text"><?php echo lang("ctn_544") ?></span>
				  
				</div>
				</div>
				
				<div class="form-group">

					    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_214") ?></label>
					    <div class="col-md-9">
					    	<input type="email" class="form-control" id="email-in" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
					    </div>
			  	</div>

			  	<div class="form-group">

					    <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_215") ?></label>
					    <div class="col-md-6">
					    	<input type="text" class="form-control" id="username" name="username" onchange="checkUsername()" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
					    	<div id="username_check"></div>
					    </div>
					    <div class="col-md-3">
					    	<input type="button" class="btn btn-default" value="<?php echo lang("ctn_210") ?>" onclick="checkUsername()" />
					    </div>
			  	</div>

			  	<div class="form-group">

					    <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_216") ?></label>
					    <div class="col-md-9">
					    	<input type="password" class="form-control" id="password-in" name="password" value="">
					    </div>
			  	</div>

			  	<div class="form-group">

					    <label for="cpassword-in" class="col-md-3 label-heading"><?php echo lang("ctn_217") ?></label>
					    <div class="col-md-9">
					    	<input type="password" class="form-control" id="cpassword-in" name="password2" value="">
					    </div>
			  	</div>

			  	<div id="firstname_panel" name="dni_panel" class="form-group">

					    <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_218") ?></label>
					    <div class="col-md-9">
					    	<input type="text" class="form-control" id="first_name" name="first_name" value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name']; ?>">
					    </div>
			  	</div>
				
			  	<div  id="lastname_panel" name="dni_panel"class="form-group">

					    <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_219") ?></label>
					    <div class="col-md-9">
					    	<input type="text" class="form-control" id="last_name" name="last_name" value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name']; ?>">
					    </div>
			  	</div>
				
				<div id="dni_panel" name="dni_panel" class="form-group">

					    <label name="dni_label" id="dni_label" for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_562") ?></label>
					    <div class="col-md-9">
					    	<input type="text" class="form-control" id="dni" name="dni" value="<?php if(isset($_POST['dni'])) echo $_POST['dni']; ?>">
						<div id="dni_check"></div>
					   </div>
			  	</div>

				<div id="birth_panel" name="birth_panel" class="form-group">

					    <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_563") ?></label>
					    <div class="col-md-9">
<input type="date" min="1910-01-01"  class="form-control" id="birth" name="birth" value="<?php if(isset($_POST['birth'])) echo $_POST['birth']; ?>">					    </div>
			  	</div>
				
				<div id="sex_panel" name="sex_panel" class="form-group">

				<label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_564") ?></label>
				<div class="col-md-9">
				  <select id="sex" name="sex" class="form-control" value="<?php if(isset($_POST['sex'])) echo $_POST['sex']; ?>">
					<option value="1"><?php echo lang("ctn_566") ?></option>
					<option value="2"><?php echo lang("ctn_567") ?></option>
				  </select>
					    </div>
			  	</div>

				<div id="nac_panel" name="nac_panel" class="form-group">

					    <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_565") ?></label>
					    <div class="col-md-9">
					
				  <select id="nac" name="nac" class="form-control" value="<?php if(isset($_POST['nac'])) echo $_POST['nac']; ?>">
				  <option value=""></option>	
				  <?php for ($x = 700; $x <= 732; $x++): ?>
					<option value="<?php echo lang("ctn_".$x) ?>"><?php echo lang("ctn_".$x) ?></option>
				  <?php endfor; ?>
				  </select>					   
				  </div>
			  	</div>				
				

			  	<?php if(!$this->settings->info->disable_captcha) : ?>
		  		<div class="form-group">

				    <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_220") ?></label>
				    <div class="col-md-9">
				    	<p><?php echo $cap['image'] ?></p>
						<input type="text" class="form-control" id="captcha-in" name="captcha" value="">
				    </div>
		  		</div>
		  		<?php endif; ?>
		  		<?php if($this->settings->info->google_recaptcha) : ?>
		  			<div class="form-group">

				    <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_220") ?></label>
				    <div class="col-md-9">
				    	<div class="g-recaptcha" data-sitekey="<?php echo $this->settings->info->google_recaptcha_key ?>"></div>
				    </div>
		  		</div>
		  		<?php endif ?>


		  		<input type="submit" name="s" class="btn btn-primary form-control" value="<?php echo lang("ctn_221") ?>" />

		  		<hr>

		  		<p><?php echo lang("ctn_222") ?></p>

		  		<p class="decent-margin"><a href="<?php echo site_url("login") ?>" class="btn btn-success form-control" ><?php echo lang("ctn_223") ?></a></p>

		  	<?php echo form_close() ?>
</div>

</div>
</div>
</div>

<script type="text/javascript">

	$("#dni").mask("000-0000000-0");
	var first_name = document.getElementById("first_name");
	var last_name = document.getElementById("last_name");
	var dni = document.getElementById("dni");
	var sex = document.getElementById("sex");
	var birth = document.getElementById("birth");
	document.getElementById("user_role").value = "<?php if(isset($_POST['user_role'])) echo $_POST['user_role']; ?>";
	document.getElementById("sex").value = "<?php if(isset($_POST['sex'])) echo $_POST['sex']; ?>";
	document.getElementById("nac").value = "<?php if(isset($_POST['nac'])) echo $_POST['nac']; ?>";

	visibles();

 function visibles() {
   
	var first_name = document.getElementById("first_name");
	var last_name = document.getElementById("last_name");
	var dni = document.getElementById("dni");
	var sex = document.getElementById("sex");
	var birth = document.getElementById("birth");

	if(document.getElementById("user_role").value == 7){
		
		firstname_panel.style.display = "block";
		lastname_panel.style.display = "block";
		dni_panel.style.display = "block";
		sex_panel.style.display = "block";
		birth_panel.style.display = "block";
		nac_panel.style.display = "block";
		dni_label.innerHTML = "Cédula";
		$("#dni").mask("000-0000000-0");
		 
	}else if(document.getElementById("user_role").value == 8){
		
		firstname_panel.style.display = "none";
		lastname_panel.style.display = "none";
		dni_panel.style.display = "block";
		sex_panel.style.display = "none";
		birth_panel.style.display = "none";
		nac_panel.style.display = "none";
		dni_label.innerHTML = "RNC";
		$("#dni").mask("000000000");

		 
	} else	if(document.getElementById("user_role").value == 9){
		firstname_panel.style.display = "none";
		lastname_panel.style.display = "none";
		dni_panel.style.display = "none";
		sex_panel.style.display = "none";
		birth_panel.style.display = "none";
		nac_panel.style.display = "none";
	}
}

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; 
var yyyy = today.getFullYear();

 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("birth").setAttribute("max", today);
</script>