<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("admin_model");
		
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		
		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}
	}

	public function index($username="") 
	{
		if(empty($username)) $this->template->error(lang("error_51"));

		$user = $this->user_model->get_user_by_username($username);
		if($user->num_rows() == 0) $this->template->error(lang("error_52"));
		$user = $user->row();

		if($user->user_level == -1) $this->template->error(lang("error_53"));
		
		$tag;
		$st = "info";
		if($user->user_role == 6 || $user->user_role == 7){
		$groups = $this->user_model->get_user_groups($user->ID);
		$tag = "Padrones asociados";
		$st = "info";
		}else if($user->user_role == 1 || $user->user_role == 8){
		$groups = $this->admin_model->get_user_groups_creator($user->ID);
		$tag = "Padrones creados";
		$st = "success";
		}

		$this->template->loadContent("profile/index.php", array(
			"user" => $user,
			"groups" => $groups,
			"tag" => $tag,
			"st" => $st
			)
		);
	}

}

?>