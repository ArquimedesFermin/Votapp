<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Settings extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		$this->template->loadData("activeLink", 
			array("settings" => array("general" => 1)));
	}

	public function index() 
	{
		$this->template->loadContent("user_settings/index.php", array(
			)
		);
	}

	public function pro() 
	{
		$this->load->model("register_model");
		
		$this->load->helper('email');
		$this->load->library("upload");
		$email = $this->common->nohtml($this->input->post("email"));
		$first_name = $this->common->nohtml($this->input->post("first_name"));
		$last_name = $this->common->nohtml($this->input->post("last_name"));
		$aboutme = $this->common->nohtml($this->input->post("aboutme"));

		$this->load->helper('email');

		if (empty($email)) $this->template->error(lang("error_18"));

		if (!valid_email($email)) {
			$this->template->error(lang("error_47"));
		}

		if($email != $this->user->info->email) {
			if (!$this->register_model->checkEmailIsFree($email)) {
				$this->template->error(lang("error_20"));
			}
		}

		$enable_email_notification = 
			intval($this->input->post("enable_email_notification"));
		if($enable_email_notification > 1 || $enable_email_notification < 0) 
			$enable_email_notification = 0;

		if ($this->settings->info->avatar_upload) {
			if ($_FILES['userfile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => $this->settings->info->file_types,
			       "max_size" => $this->settings->info->file_size,
			       "xss_clean" => TRUE,
			       "max_width" => 300,
			       "max_height" => 300
			    ));

			    if (!$this->upload->do_upload()) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    }

			    $data = $this->upload->data();

			    $image = $data['file_name'];
			} else {
				$image= $this->user->info->avatar;
			}
		} else {
			$image= $this->user->info->avatar;
		}


		$this->user_model->update_user($this->user->info->ID, array(
			"email" => $email, 
			"first_name" => $first_name, 
			"last_name" => $last_name,
			"email_notification" => $enable_email_notification,
			"avatar" => $image,
			"aboutme" => $aboutme		)
		);

		$this->session->set_flashdata("globalmsg", lang("success_22"));
		redirect(site_url("user_settings"));
	}


	public function change_password() 
	{
		$this->template->loadContent("user_settings/change_password.php", array(
			)
		);
	}

	public function change_password_pro() 
	{
		$current_password = 
			$this->common->nohtml($this->input->post("current_password"));
		$new_pass1 = $this->common->nohtml($this->input->post("new_pass1"));
		$new_pass2 = $this->common->nohtml($this->input->post("new_pass2"));

		if(empty($new_pass1)) $this->template->error(lang("error_45"));
		if($new_pass1 != $new_pass2) $this->template->error(lang("error_22"));

		$phpass = new PasswordHash(12, false);
    	if (!$phpass->CheckPassword($current_password, 
    		$this->user->getPassword())) {
    		$this->template->error(lang("error_59"));
    	}

    	$pass = $this->common->encrypt($new_pass1);
    	$this->user_model->update_user($this->user->info->ID, 
    		array("password" => $pass));

    	$this->session->set_flashdata("globalmsg", lang("success_23"));
    	redirect(site_url("user_settings/change_password"));
	}

}

?>