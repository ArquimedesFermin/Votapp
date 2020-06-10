<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("register_model");
		$this->load->model("user_model");
	}

	public function index()
	{

		$this->template->set_error_view("error/login_error.php");
		$this->template->set_layout("layout/login_layout.php");
		if ($this->settings->info->register) {
			$this->template->error(lang("error_54"));
		}

		$this->template->loadExternal(
			'<script src="https://www.google.com/recaptcha/api.js"></script>
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/check_username.js" /></script>
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/mask.js" /></script>
			<link href="'. base_url() .'styles/mask.css" 
				rel="stylesheet" type="text/css">'
		);

		if ($this->user->loggedin) {
			$this->template->error(
				lang("error_27")
			);
		}
		$this->load->helper('email');

		$email = "";
		$name = "";
		$username = "";
		$fail = "";
		$first_name = "";
		$last_name = "";
		$dni = "";
		$sex = "";
		$birth = "";
		$nac = "";

		if (isset($_POST['s'])) {
			$email = $this->input->post("email", true);
			$first_name = $this->common->nohtml(
				$this->input->post("first_name", true));
			$last_name = $this->common->nohtml(
				$this->input->post("last_name", true));
			$pass = $this->common->nohtml(
				$this->input->post("password", true));
			$pass2 = $this->common->nohtml(
				$this->input->post("password2", true));
			$captcha = $this->input->post("captcha", true);
			$username = $this->common->nohtml(
				$this->input->post("username", true));
			
			$user_role = intval($this->input->post("user_role", true));
			
			$dni = $this->input->post("dni", true);
			$sex = intval($this->input->post("sex", true));
			$birth = date($this->input->post("birth", true));
			$nac = $this->input->post("nac", true);
			
			if (strlen($username) < 3) $fail = "error_31";
			if($user_role == 7){
				if (strlen($dni) < 13) $fail = "error_105";
			}else if($user_role == 8){
				if (strlen($dni) < 9) $fail = "error_105";
			}

			if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
				$fail = lang("error_15");
			}

			if (!$this->register_model->check_username_is_free($username)) {
				$fail = lang("error_16");
			}			
			if($user_role == 7 || $user_role == 8){
				if (!$this->register_model->check_dni_is_free($dni)) {
					$fail = lang("error_102");
				}
			}

			if($this->settings->info->google_recaptcha) {
				require(APPPATH . 'third_party/autoload.php');
				$recaptcha = new \ReCaptcha\ReCaptcha(
					$this->settings->info->google_recaptcha_secret);
				$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['SERVER_NAME']);
				if ($resp->isSuccess()) {
				    // verified!
				} else {
				    $errors = $resp->getErrorCodes();
				    $fail = lang("error_55");
				}
			}

			if (!$this->settings->info->disable_captcha) {
				if ($captcha != $_SESSION['sc']) {
					$fail = lang("error_55");
				}
			}
			if ($pass != $pass2) $fail = lang("error_22");

			if (strlen($pass) <= 5) {
				$fail = lang("error_17");
			}
			
			if($user_role == 7){				
			if (strlen($first_name) > 25) {
					$fail = lang("error_56");
				}
				if (strlen($last_name) > 30) {
					$fail = lang("error_57");
				}
			}
			
			if($user_role == 7){
				if (empty($first_name) || empty($last_name)){
					$fail = lang("error_58");
				}
				if (empty($sex) || empty($nac) || empty($dni)){
					$fail = lang("error_97");
				}
			}
			
			if($user_role == 8){
				if (empty($dni)){
						$fail = lang("error_97");
					}
				}
			if (empty($email)) {
				$fail = lang("error_18");
			}

			if (!valid_email($email)) {
				$fail = lang("error_19");
			}

			if (!$this->register_model->checkEmailIsFree($email)) {
				$fail = lang("error_20");
			}

			if (empty($fail)) {

				$pass = $this->common->encrypt($pass);
				$active = 1;
				$activate_code = "";
				$success =  lang("success_20");
				if($this->settings->info->activate_account) {
					$active = 0;
					$activate_code = md5(rand(1,10000000000) . "fhsf" . rand(1,100000));
					$success = lang("success_41");

					// Send email
					$this->load->model("home_model");
					$email_template = $this->home_model->get_email_template(2);
					if($email_template->num_rows() == 0) {
						$this->template->error(lang("error_48"));
					}
					$email_template = $email_template->row();

					$email_template->message = $this->common->replace_keywords(array(
						"[NAME]" => $username,
						"[SITE_URL]" => site_url(),
						"[EMAIL_LINK]" => 
							site_url("register/activate_account/" . $activate_code . 
								"/" . $username),
						"[SITE_NAME]" =>  $this->settings->info->site_name
						),
					$email_template->message);

					$this->common->send_email($email_template->title,
						 $email_template->message, $email);
				}

				$userid = $this->register_model->add_user(array(
					"username" => $username,
					"email" => $email,
					"first_name" => $first_name,
					"last_name" => $last_name,
					"password" => $pass,
					"user_role" => $user_role,
					"IP" => $_SERVER['SERVER_NAME'],
					"joined" => time(),
					"joined_date" => date("n-Y"),
					"active" => $active,
					"activate_code" => $activate_code,
					"sex" => $sex,
					"birth" => $birth,
					"dni" => $dni,
					"nac" => $nac,
					"online_timestamp" => time()
					)
				);

				// Check for any default user groups
				$default_groups = $this->user_model->get_default_groups();
				foreach($default_groups->result() as $r) {
					$this->user_model->add_user_to_group($userid, $r->ID);
				}
				$this->session->set_flashdata("globalmsg", $success);
				redirect(site_url("login"));
			}

		}


		$this->load->helper("captcha");
		$rand = rand(4000,100000);
		$_SESSION['sc'] = $rand;
		$vals = array(
		    'word' => $rand,
		    'img_path' => './images/captcha/',
    		'img_url' => base_url() . 'images/captcha/',
		    'img_width' => 150,
		    'img_height' => 30,
		    'expiration' => 7200
		    );

		$cap = create_captcha($vals);
		$this->template->loadContent("register/index.php", array(
			"cap" => $cap,
			"email" => $email,
			"first_name" => $first_name,
			"last_name" => $last_name,
		    'fail' => $fail,
		    "username" => $username));
	}

	public function add_username() 
	{
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/custom/check_username.js" /></script>'
		);
		if (!$this->user->loggedin) {
			$this->template->error(
				lang("error_1")
			);
		}
		$this->template->loadContent("register/add_username.php", array());
	}

	public function add_username_pro() 
	{
		$this->load->helper('email');
		$email = $this->input->post("email", true);
		$username = $this->common->nohtml(
				$this->input->post("username", true));
		$dni = $this->common->nohtml(
				$this->input->post("dni", true));
				
				
		if (strlen($username) < 3) $fail = lang("error_14");

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
			$fail = lang("error_15");
		}

		if (!$this->register_model->check_username_is_free($username)) {
			$fail = lang("error_16");
		}		

		if (!$this->register_model->check_dni_is_free($dni)) {
			$fail = lang("error_16");
		}
		if (empty($email)) {
			$fail = lang("error_18");
		}

		if (!valid_email($email)) {
			$fail = lang("error_19");
		}

		if (!$this->register_model->checkEmailIsFree($email)) {
			$fail = lang("error_20");
		}

		if(!empty($fail)) $this->template->error($fail);

		$this->register_model
			->update_username($this->user->info->ID, $username, $email);
		$this->session->set_flashdata("globalmsg",  lang("success_21"));
		redirect(site_url());
	}

	public function check_username() 
	{
		$username = $this->common->nohtml(
				$this->input->get("username", true));
		if (strlen($username) < 3) $fail = lang("error_14");

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) $fail = lang("error_15");

		if (!$this->register_model->check_username_is_free($username)) {
			$fail="$username " . lang("ctn_243");
		}
		if (empty($fail)) {
			echo"<span style='color:#4ea117'>". lang("ctn_244")."</span>";
		} else {
			echo $fail;
		}
		exit();
	}	
	
	public function check_dni() {
		$dni = $this->common->nohtml(
				$this->input->get("dni", true));
		if (strlen($dni) < 9) $fail = lang("error_14");

		if (!preg_match("/^[a-z0-9_]+$/i", $dni)) $fail = lang("error_15");

		if (!$this->register_model->check_dni_is_free($dni)) {
			$fail="$dni " . lang("ctn_243");
		}
		if (empty($fail)) {
			echo"<span style='color:#4ea117'>". lang("ctn_244")."</span>";
		} else {
			echo $fail;
		}
		exit();
	}

	public function activate_account($code, $username) 
	{
		$this->template->set_error_view("error/login_error.php");
		$code = $this->common->nohtml($code);
		$username = $this->common->nohtml($username);

		$code = $this->user_model->get_verify_user($code, $username);
		if($code->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$code = $code->row();
		if($code->active) {
			$this->template->error(lang("error_86"));
		}

		$this->user_model->update_user($code->ID, array(
			"active" => 1, 
			"activate_code" => ""
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_42"));
		redirect(site_url("login"));
	}

	public function send_activation_code($userid, $email) 
	{
		$this->template->set_error_view("error/login_error.php");
		$userid = intval($userid);
		$email = $this->common->nohtml(urldecode($email));

		// Check request
		$request = $this->user_model->get_user_event("email_activation_request");
		if($request->num_rows() > 0) {
			$request = $request->row();
			if($request->timestamp + (15*60) > time()) {
				$this->template->error(lang("error_87"));
			}
		}

		$this->user_model->add_user_event(array(
			"event" => "email_activation_request",
			"IP" => $_SERVER['SERVER_NAME'],
			"timestamp" => time()
			)
		);

		// Resend
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_88"));
		}
		$user = $user->row();
		if($user->email != $email) 
		{
			$this->template->error(lang("error_88"));
		}
		if($user->active) {
			$this->template->error(lang("error_88"));
		}
		// Send email
		$this->load->model("home_model");
		$email_template = $this->home_model->get_email_template(2);
		if($email_template->num_rows() == 0) {
			$this->template->error(lang("error_48"));
		}
		$email_template = $email_template->row();

		$email_template->message = $this->common->replace_keywords(array(
			"[NAME]" => $user->username,
			"[SITE_URL]" => site_url(),
			"[EMAIL_LINK]" => 
				site_url("register/activate_account/" . $user->activate_code . 
					"/" . $user->username),
			"[SITE_NAME]" =>  $this->settings->info->site_name
			),
		$email_template->message);

		$this->common->send_email($email_template->title,
			 $email_template->message, $user->email);
		$this->session->set_flashdata("globalmsg", 
			lang("success_43"));
		redirect(site_url("login"));
	}
}

?>