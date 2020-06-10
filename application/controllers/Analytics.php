<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Analytics extends CI_Controller {
public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("admin_model");	
		$this->load->model("polls_model");
		$this->load->model("funds_model");
	}

	private function requirements() 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		if(!$this->common->has_permissions(array("admin", "admin_poll",
		 "poll_creator", "admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		
	}


		public function poll($pollid){
			
		if(empty($pollid)) $this->template->error(lang("error_65"));
		$this->requirements();
		$this->template->loadData("activeLink", 
			array("analytics" => array("index" => 1)));
		
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->error(lang("error_72"));
		$poll = $poll->row();
		
		//print_r($poll);
		
		$group = $this->admin_model->get_user_group($poll->groupid);
		$group = $group->row();
		
		//print_r($group);	

		$answers = $this->polls_model->get_poll_answers($poll->ID);
		
		if($answers->num_rows() == 0) $this->template->error(lang("error_101"));

		$answers_votes = $this->polls_model->get_top_answers_votes($poll->ID);
		$top_answer = $answers_votes->row();
		
		$answers_votes2 = $this->polls_model->count_poll_voters($poll->ID);
		
		//print_r($top_answer);
		
		$votes = $this->polls_model->get_recent_votes2($poll->ID, 1);
		
		$total_members = $this->admin_model
			->get_total_user_group_members_count($poll->groupid);
		
		$type="";
		if($poll->public == 0): $type="Público";
		elseif($poll->public == 1): $type="Privado";
		 endif; 
		
		if($poll->public==0){
			$total_users = $this->admin_model->get_total_user_count();
		}else if($poll->public==1){
			$total_users = $this->admin_model->get_total_user_group_members_count($poll->groupid);
		}
		$this->template->loadContent("pages/analytics/poll.php", array(
			"pollid" => $pollid,
			"top_answer" => $top_answer,
			"poll" => $poll,
			"group" => $group,
			"votes" => $votes,
			"answers" => $answers,
			"total_users" => $total_users,
			"total_members" => $total_members,
			"answers_votes2" => intval($answers_votes2->num_rows())
			)
		);
	}
	
	public function members() {
		$this->requirements();		
		// TOTAL DE MIEMBROS
		$total_members = $this->user_model->get_total_members_count();
		$total_members_active = $this->user_model->get_all_members_active();
		$total_members_banned = $this->user_model->get_all_members_banned();
		
		$total_members_admin = $this->user_model->get_all_members_admin();
		$total_members_voter = $this->user_model->get_all_members_voter();
		$total_members_external = $this->user_model->get_all_members_external();
		
		
		$members = $this->user_model->get_all_members();
		
		//print_r($members->row());
		
		$this->template->loadContent("pages/analytics/members.php", array(
		"members" => $members,
		"total_members" => $total_members,
		"total_members_active" => $total_members_active,
		"total_members_banned" => $total_members_banned,
		"total_members_external" => $total_members_external,
		"total_members_admin" => $total_members_admin,
		"total_members_voter" => $total_members_voter,
		"total_members_external" => $total_members_external
		
		));
	}
		
}
?>