<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Polls extends CI_Controller 
{

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

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}
		if(!$this->common->has_permissions(array("admin", "admin_poll",
		 "poll_creator","voter", "admin_peyment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
			
	}

	
	private function avalible_poll($poll){
		$votes = $this->polls_model->get_recent_votes($poll->ID);
		
		 if($poll->status != 1){
			if($votes->num_rows() > 0 && ($poll->timestamp > 0 || $poll->timestamp == 0)){
				$this->template->error(lang("error_104"));
			}
		 }
		}

	public function index()
	{
		$this->requirements();

		$this->template->loadData("activeLink", 
			array("poll" => array("active" => 1)));			
		$page = "index";
		$this->template->loadContent("polls/edit_polls.php", array(
			"page" => $page
			)
		);
	}

	public function poll_page($page) 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_polls.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"user_polls.name" => 0
				 ),
				 1 => array(
				 	"user_polls.votes" => 0
				 ),
				 2 => array(
				 	"user_polls.status" => 0
				 ),
				 3 => array(
				 	"user_polls.created" => 0
				 ),
				 4 => array(
				 	"user_polls.timestamp" => 0
				 ),
			)
		);

		if($page == "index"){

			$this->datatables->set_total_rows(
				$this->polls_model->get_total_user_polls($this->user->info->ID)
			);

			$polls = $this->polls_model->get_user_polls($this->user->info->ID, 
				$this->datatables);
		
		} else if($page == "all") {
			$this->datatables->set_total_rows(
				$this->polls_model->get_total_polls()
			);

			$polls = $this->polls_model->get_all_polls(
				$this->datatables);
		
		}else if($page == "assoc"){

			$this->datatables->set_total_rows(
				$this->polls_model->get_total_user_polls($this->user->info->ID)
			);

			$polls = $this->polls_model->get_user_polls_group($this->user->info->ID, 
				$this->datatables);
												
		}else if($page == "archived"){
			$this->datatables->set_total_rows(
				$this->polls_model->get_total_user_polls_archived($this->user->info->ID)
			);

			$polls = $this->polls_model->get_user_polls_archived($this->user->info->ID, 
				$this->datatables);
				
		}
		
		foreach($polls->result() as $r) {	
		
			if($r->status == 0) {
				$status = lang("ctn_334");
			} elseif($r->status == 1) {
				$status = lang("ctn_332");
			} elseif($r->status == 2) {
				$status = lang("ctn_333");
			}

			if($r->timestamp > time() ) {
				$time = $this->common->convert_time($r->timestamp);
				unset($time['secs']);
				$time = $this->common->get_time_string($time);
			} else {
				if($r->timestamp > 0) {
					$time = '<span class="label label-danger">'. lang("ctn_384") .'</span>';
				} else {
					$time = lang("ctn_385");
				}
			}	

			if($page == "all") {
			if($r->ex_a == 1 && $r->ex_r == 1){
				$options = '<a href="'.site_url("polls/view_poll2/" . $r->ID . "/" . $r->hash ."/1").'" class="btn btn-primary btn-xs">'.lang("ctn_335").'</a>';
				}else{
				$options = '<a href="'.site_url("polls/view_poll/" . $r->ID . "/" . $r->hash).'" class="btn btn-primary btn-xs">'.lang("ctn_335").'</a>';
			}
			}elseif($page == "assoc"){
				$options = '<a href="'.site_url("polls/view_poll/" . $r->ID . "/" . $r->hash).'" class="btn btn-primary btn-xs">'.lang("ctn_335").'</a>';
			}else{
				$options = '<a href="'.site_url("polls/ajax_poll/".$r->ID . "/" . $r->hash).'" class="btn btn-default btn-xs">'.lang("ctn_408").'</a> <a href="'.site_url("polls/view_poll/" . $r->ID . "/" . $r->hash).'" class="btn btn-primary btn-xs">'.lang("ctn_335").'</a> <a href="'.site_url("polls/edit_poll_pro/" . $r->ID) .'" class="btn btn-info btn-xs" title="'.lang("ctn_379").'" data-toggle="tooltip" data-placement="bottom">Ajustes</a> <a href="'.site_url("polls/edit_poll/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_358").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("polls/delete_poll/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_386").'\')" title="'.lang("ctn_387").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
			}
			
			$link = '';
			
			if($page == "assoc"){
			$this->datatables->data[] = array(
				$r->GroupName,
				$r->name,
				$status,
				date($this->settings->info->date_format, $r->created),
				$time,								
				$options
			);
			}else{
			$this->datatables->data[] = array(
				$r->name,
				$r->votes,
				$status,
				date($this->settings->info->date_format, $r->created),
				$time,								
				$options
			);
				
			}
		}
		echo json_encode($this->datatables->process());

	}

	public function all() 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}

		$this->template->loadData("activeLink", 
			array("poll" => array("all" => 1)));

		$page = "all";

		$this->template->loadContent("polls/edit_polls.php", array(
			"page" => $page
			)
		);
	}

	public function archived($page =0){
		$this->requirements();
		$this->template->loadData("activeLink", 
			array("poll" => array("archived" => 1)));

		$this->template->loadContent("polls/edit_polls.php", array(
			"page" => "archived"
			)
		);
	}

	public function assoc($page =0){
		$this->requirements();
		$this->template->loadData("activeLink", 
			array("poll" => array("assoc" => 1)));

		$this->template->loadContent("polls/edit_polls.php", array(
			"page" => "assoc"
			)
		);
	}
	
	
	public function create() 
	{

		if (!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		if(!$this->common->has_permissions(array("admin", "admin_settings",
			"admin_members","poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		
		$this->template->loadData("activeLink", 
			array("poll" => array("create" => 1)));

		$themes = $this->polls_model->get_poll_themes();
		//$groups = $this->user_model->get_user_group();
		$groups = $this->admin_model->get_user_groups_creator($this->user->info->ID);
		
		$this->template->loadContent("polls/create.php", array(
			"themes" => $themes,
			"groups" => $groups
			)
		);
	}

	public function create_pro() 
	{
		$this->requirements();
		$name = $this->common->nohtml($this->input->post("name"));
		$question = $this->common->nohtml($this->input->post("question"));
		$days = intval($this->input->post("days"));
		$hours = intval($this->input->post("hours"));
		$minutes = intval($this->input->post("minutes"));
		$ip_restriction = 0;
		$show_results = 1;
		$vote_type = intval($this->input->post("vote_type"));
		$votes_limit = intval($this->input->post("votes_limit"));
		$themeid = 1;
		$cookie_restriction = 1;
		$user_restriction = 1;
		$ex_a = intval($this->input->post("ex_a"));
		$ex_r = intval($this->input->post("ex_r"));

		$public = intval($this->input->post("public"));
		$groupid = intval($this->input->post("groupid"));

		$theme = $this->polls_model->get_poll_theme($themeid);
		$groups = $this->admin_model->get_user_groups_creator($this->user->info->ID);
		
		if($theme->num_rows() == 0) $this->template->error(lang("error_66"));

		$time = 0;
		$time = ($days * (3600 *24)) + ($hours * 3600) + ($minutes * 60);
		if($time > 0) {
			//$time = time() + $time;
		}

		if(empty($name)) $this->template->error(lang("error_70"));
		if(empty($question)) $this->template->error(lang("error_71"));

		$hash = md5(rand(1,10000000) . "polls");

		$pollid = $this->polls_model->create_poll(array(
			"userid" => $this->user->info->ID,
			"name" => $name,
			"question" => $question,
			"timestamp" => $time,
			"created" => time(),
			"ip_restricted" => $ip_restriction,
			"show_results" => $show_results,
			"updated" => time(),
			"hash" => $hash,
			"vote_type" => $vote_type,
			"votes_limit" => $votes_limit,
			"themeid" => $themeid,
			"cookie_restricted" => $cookie_restriction,
			"user_restricted" => $user_restriction,
			"public" => $public,
			"groupid" => $groupid,
			"ex_a" => $ex_a,
			"ex_r" => $ex_r
			
			)
		);
		
		if($public==1 && $group!=0){
			$site_name = $this->settings->info->site_name;
			$last_poll = $this->admin_model->get_last_poll();
			$link = site_url("polls/view_poll/".$last_poll . "/" . $hash);
			$title = $site_name." - Nuevo modelo asociado";
			$users_group = $this->admin_model->get_users_from_groups($group);
			
			foreach($users_group->result() as $r){
				$message = 'Hola '.$r->first_name.', se ha agregado un nuevo modelo "'.$name.' - '.$question.'" al padrón '.$r->name.' \tHacer click en el siguiente enlace para ver el modelo '.$link.'\t'.$site_name;
				$this->common->send_email($title, $message, $r->email);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_34"));
		redirect(site_url("polls/edit_poll/" . $pollid));
	}

	public function edit_poll($pollid) 
	{
		$saved = true;
		$this->requirements();
		$this->template->loadData("activeLink", 
			array("poll" => array("active" => 1)));
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/custom/polls.js" /></script>'.
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>'
		);
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_user_poll($pollid, $this->user->info->ID);
		if($poll->num_rows() == 0) $this->template->error(lang("error_72"));
		$poll = $poll->row();
		
		$groups = $this->polls_model->get_user_group($poll->groupid);
		$groups = $groups->row();
		$answers = $this->polls_model->get_poll_answers($poll->ID);
		$votes = $this->polls_model->get_recent_votes($poll->ID);
		
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}

		if($poll->ip_restricted) {
			$user_vote = $this->polls_model->check_user_vote($pollid);
		} else {
			$user_vote = $this->polls_model->get_poll_vote($pollid, 
				$userid);
		}
		
		if($poll->public==0){
		$total_users = $this->admin_model->get_total_user_count();
		}else if($poll->public==1){
		$total_users = $this->admin_model->get_total_user_group_members_count($poll->groupid);
		}
		
		$votes = $this->polls_model->get_recent_votes($poll->ID);
		$mods = $this->polls_model->get_answer_mod($userid, $pollid);
		$total_voters = $this->polls_model->get_total_voters($poll->ID);


		$last_dates = array();

		for ($i=6; $i>-1; $i--) {
			$date = date("Y-m-d", strtotime($i." days ago"));
			$vote = array(
				"date" => $date,
				"votes" => $this->polls_model->count_votes_date($date, $poll->ID)
			);
		    $last_dates[] = $vote;
		}

		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/custom/results.js" /></script>');
		
		$this->template->loadContent("polls/edit_poll.php", array(
			"poll" => $poll,
			"answers" => $answers,
			"groups" => $groups,
			"votes" => $votes,
			"user_vote" => $user_vote,
			"user_vote_num" => $user_vote->num_rows(),
			"user" => $this->user->info,
			"total_users" => $total_users,
			"mods" => $mods,
			"total_voters" => $total_voters
			)
		);
	}

	public function edit_poll_pro($pollid) 
	{
		$saved = true;
		$this->requirements();
		$this->template->loadData("activeLink", 
			array("poll" => array("active" => 1)));

		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->error(lang("error_72"));
		
		$poll = $poll->row();
		
		$votes = $this->polls_model->get_recent_votes($poll->ID);
		
		 if($poll->status == 1){
			if($votes->num_rows() > 0 && ($poll->timestamp > 0 || $poll->timestamp == 0)){
				$this->template->error(lang("error_104"));
			}
		 }

		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		$themes = $this->polls_model->get_poll_themes();
		$groups = $this->admin_model->get_user_groups_creator($this->user->info->ID);
		$groupdata = $this->admin_model->get_user_group($poll->groupid);
		$groupdata = $groupdata->row();
		
		$this->template->loadContent("polls/edit_poll_pro.php", array(
			"poll" => $poll,
			"themes" => $themes,
			"groups" => $groups,
			"groupdata" => $groupdata
			
			)
		);
	}

	public function edit_poll_pro_pro($pollid) 
	{
		$this->requirements();
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if(isset($groupdata)){
			$groupdata = $this->admin_model->get_user_group($poll->groupid);
		}
		if($poll->num_rows() == 0) $this->template->error(lang("error_72"));

		$poll = $poll->row();
		$this->avalible_poll($poll);

		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$question = $this->common->nohtml($this->input->post("question"));
		$days = intval($this->input->post("days"));
		$hours = intval($this->input->post("hours"));
		$minutes = intval($this->input->post("minutes"));
		$ip_restriction = intval(0);
		$show_results = intval(1);
		$expired = intval($this->input->post("reset_expired"));
		$vote_type = intval($this->input->post("vote_type"));
		$votes_limit = intval($this->input->post("votes_limit"));		
		$themeid = intval(1);
		$cookie_restriction = intval(1);
		$user_restriction = intval(1);

		$public = intval($this->input->post("public"));
		$ex_a = intval($this->input->post("ex_a"));
		$ex_r = intval($this->input->post("ex_r"));
		$groupid = intval($this->input->post("groupid"));

		$theme = $this->polls_model->get_poll_theme($themeid);
		
		if($theme->num_rows() == 0) $this->template->error(lang("error_66"));

		$time = 0;
		$time = ($days * (3600 *24)) + ($hours * 3600) + ($minutes * 60);
		if($time > 0) {
			//$time = time() + $time;
		} else {
			if($expired) {
				$time = 0;
			} else {
				$time = $poll->timestamp;
			}
		}

		if(empty($name)) $this->template->error(lang("error_70"));
		if(empty($question)) $this->template->error(lang("error_71"));

		$this->polls_model->update_poll($poll->ID, array(
			"name" => $name,
			"question" => $question,
			"timestamp" => $time,
			"ip_restricted" => $ip_restriction,
			"show_results" => $show_results,
			"updated" => time(),
			"vote_type" => $vote_type,
			"votes_limit" => $votes_limit,		
			"themeid" => $themeid,
			"cookie_restricted" => $cookie_restriction,
			"user_restricted" => $user_restriction,
			"public" => $public,
			"ex_a" => $ex_a,
			"ex_r" => $ex_r,
			"groupid" => $groupid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_35"));
		redirect(site_url("polls/edit_poll/" . $pollid));
	}

	public function update_status($pollid, $hash) 
	{
		$this->requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$pollid = intval($pollid);
		$status = intval($this->input->get("status"));

		if($status < 0 || $status > 2) $this->template->jsonError(lang("error_73"));

		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		$this->polls_model->update_poll($poll->ID, array(
			"status" => $status,
			"start" => time()
			)
		);

		echo json_encode(array(
			"success" => 1, 
			"success_msg" => lang("success_36")
			)
		);		
		exit();
	 
	}

	public function update_question($pollid, $hash) 
	{
		$this->requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$pollid = intval($pollid);
		$question = $this->lib_filter->go($this->input->get("question"));

		if(empty($question)) $this->template->jsonError(lang("error_71"));


		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		$this->polls_model->update_poll($poll->ID, array(
			"question" => $question
			)
		);

		echo json_encode(array(
			"success" => 1, 
			"success_msg" => lang("success_36")
			)
		);
		exit();
	}

	public function add_answer($pollid, $hash) 
	{
		$this->requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		$answer = "Ingresa tu alternativa.";
		$answerid = $this->polls_model->add_answer($pollid, $answer);

		echo json_encode(
			array(
				"success" => 1, 
				"success_msg" => lang("success_36"), 
				"answerid" => $answerid
			)
		);
		exit();
	}

	public function update_answer($pollid, $answerid, $hash) 
	{
		$this->requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		// Get answer
		$answer = $this->polls_model->get_poll_answer($pollid, $answerid);
		if($answer->num_rows() == 0) $this->template->jsonError(lang("error_74"));

		// Update
		$answer = $this->common->nohtml($this->input->get("answer"));
		$this->polls_model->update_answer($answerid, array(
			"answer" => $answer
			)
		);
		echo json_encode(
			array(
				"success" => 1, 
				"success_msg" => lang("success_36"), 
				"answerid" => $answerid
			)
		);
		exit();
	}

	public function upload_image_answer($pollid, $answerid) 
	{
		$this->requirements();
		$this->load->library("upload");
		$pollid = intval($pollid);
		$answerid = intval($answerid);
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		// Get answer
		$answer = $this->polls_model->get_poll_answer($pollid, $answerid);
		if($answer->num_rows() == 0) $this->template->error(lang("error_74"));

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "gif|jpg|png|jpeg",
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
			$this->template->error(lang("error_75"));
		}

		// Add image
		$this->polls_model->update_answer($answerid, array(
			"image" => $image
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_37"));
		redirect(site_url("polls/edit_poll/" . $pollid));
	}

	public function delete_image($pollid, $answerid, $hash) 
	{
		$this->requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		// Get answer
		$answer = $this->polls_model->get_poll_answer($pollid, $answerid);
		if($answer->num_rows() == 0) $this->template->jsonError(lang("error_74"));
		$answer = $answer->row();
		$this->polls_model->update_answer($answerid, array(
			"image" => ""
			)
		);

		if($answer->image) {
		if (file_exists($this->settings->info->upload_path.'/'.$answer->image)) {
    		unlink($this->settings->info->upload_path.'/'.$answer->image);
    	}
    	}
    	echo json_encode(
			array(
				"success" => 1, 
				"success_msg" => lang("success_36"), 
				"answerid" => $answerid
			)
		);
		exit();
	}

	public function delete_answer($pollid, $answerid, $hash) 
	{
		$this->requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		// Get answer
		$answer = $this->polls_model->get_poll_answer($pollid, $answerid);
		if($answer->num_rows() == 0) $this->template->jsonError(lang("error_74"));
		$answer = $answer->row();
		
		$this->polls_model->delete_answer($answerid);

		if($answer->image) {
		if (file_exists($this->settings->info->upload_path.'/'.$answer->image)) {
    		unlink($this->settings->info->upload_path.'/'.$answer->image);
    	}
    	}
    	echo json_encode(
			array(
				"success" => 1, 
				"success_msg" => lang("success_38"), 
				"answerid" => $answerid
			)
		);
		exit();
	}

	public function view_poll($pollid,$hash) 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		$pollid = intval($pollid);
		$hash = $this->common->nohtml($hash);

		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}
		$poll = $poll->row();
		if($poll->hash != $hash) {
			$this->template->error(lang("error_76"));
		}
		
		if($this->user->info->ID!=$poll->userid){
		if($poll->public==1){
			$group = $this->admin_model->get_user_from_group($this->user->info->ID,$poll->groupid);		
			if($group->num_rows() == 0){
					$this->template->error(lang("error_96"));
				}
		}
		}		
		
		if($this->user->info->user_role == 9){
			redirect(site_url("analytics/poll/".$poll->ID));
		}
		
		$answers = $this->polls_model->get_poll_answers($poll->ID);

		if(!isset($poll->css_code)) {
			$this->template->loadExternal('
				<link href="'. base_url() .'styles/user_poll.css" 
				rel="stylesheet" type="text/css">');
		} else {
			$this->template->loadExternal('<style type="text/css">' . 
				$poll->css_code . '</style>');
		}
		$this->template->loadExternal('
				<link href="'. base_url() .'styles/notify.css" 
				rel="stylesheet" type="text/css">');

/* 		if($this->settings->info->auto_updating) {
			$this->template->loadExternal('
            <script src="'. base_url() .'scripts/custom/auto-update.js"></script>
            <script>var redirect = 0;</script>
            ');
		}
 */
		if($poll->user_restricted) {
			if(!$this->user->loggedin) {
				$this->template->error(lang("error_94"));
			}
		}

		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}

		if($poll->ip_restricted) {
			$user_vote = $this->polls_model->check_user_vote($pollid);
		} else {
			$user_vote = $this->polls_model->get_poll_vote($pollid, 
				$userid);
		}
		
		if($poll->public==0){
		$total_users = $this->admin_model->get_total_user_count();
		}else if($poll->public==1){
		$total_users = $this->admin_model->get_total_user_group_members_count($poll->groupid);
		}
		
		$votes = $this->polls_model->get_recent_votes($poll->ID);
		$mods = $this->polls_model->get_answer_mod($userid, $pollid);
		$total_voters = $this->polls_model->get_total_voters($poll->ID);
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("polls/view_poll2/" .$poll->ID ."/".$hash ."/");
		$config['total_rows'] = $this->polls_model->get_total_votes_count($poll->ID);
		$config['per_page'] = 20;
		$config['uri_segment'] = 4;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 
		
		$this->template->loadContent("polls/view_poll.php", array(
			"poll" => $poll,
			"answers" => $answers,
			"user_vote" => $user_vote,
			"user_vote_num" => $user_vote->num_rows(),
			"user" => $this->user->info,
			"total_users" => $total_users,
			"votes" => $votes,
			"mods" => $mods,
			"total_voters" => $total_voters
			)
		);
	}
	
	public function view_poll2($pollid,$hash,$page) 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		$pollid = intval($pollid);
		$hash = $this->common->nohtml($hash);
		
		$poll = $this->polls_model->get_poll($pollid);
		
		if($poll->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}
		$poll = $poll->row();
		if($poll->hash != $hash) {
			$this->template->error(lang("error_76"));
		}
		
		if($this->user->info->ID!=$poll->userid){
		if($poll->public==1){
			$group = $this->admin_model->get_user_from_group($this->user->info->ID,$poll->groupid);		
			if($group->num_rows() == 0){
					$this->template->error(lang("error_96"));
				}
		}
		}		
		
				if($this->user->info->user_role == 9){
			redirect(site_url("analytics/poll/".$poll->ID));
		}
		
		
		$answers = $this->polls_model->get_poll_answers($poll->ID);

		if(!isset($poll->css_code)) {
			$this->template->loadExternal('
				<link href="'. base_url() .'styles/user_poll.css" 
				rel="stylesheet" type="text/css">');
		} else {
			$this->template->loadExternal('<style type="text/css">' . 
				$poll->css_code . '</style>');
		}
		
		$this->template->loadExternal('
		<link href="'. base_url() .'styles/notify.css" 
		rel="stylesheet" type="text/css">');
		
		

		if($poll->user_restricted) {
			if(!$this->user->loggedin) {
				$this->template->error(lang("error_94"));
			}
		}

		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}
		
		if($poll->public==0){
		$total_users = $this->admin_model->get_total_user_count();
		}else if($poll->public==1){
		$total_users = $this->admin_model->get_total_user_group_members_count($poll->groupid);
		}
		
		$alternatives = $this->polls_model->count_poll_answers($poll->ID);
		
		//FORMULA PARA DETERMINAR LA CANTIDAD DE BOLAS POSIBLES.
		$total=($alternatives/2)*($alternatives+1);
		$total_balls = $total*$total_users;
		
		//////////////////////////////////////////
		$balls_user = $total_balls / $total_users;
		//////////////////////////////////////////
		
		$user_vote = $this->polls_model->get_poll_vote($pollid, $userid);   


		$this->template->loadExternal(
		'<script type="text/javascript" src="'
		.base_url().'scripts/libraries/Chart.min.js" /></script>');
		
		
		$votes = $this->polls_model->get_recent_votes2($poll->ID, $page);
		$total_voters = $this->polls_model->get_total_voters($poll->ID);

		$this->load->library('pagination');
		$config['base_url'] = site_url("polls/view_poll2/" .$poll->ID ."/".$hash ."/");
		$config['total_rows'] = $this->polls_model->get_total_votes_count($poll->ID);
		$config['per_page'] = 20;
		$config['uri_segment'] = 4;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 
		
		$mods = $this->polls_model->get_answer_mod($userid, $pollid);

		  $this->template->loadContent("polls/view_poll2.php", array(
			"poll" => $poll,
			"answers" => $answers,
			"user_vote" => $user_vote,
			"user_vote_num" => intval($user_vote->num_rows()),
			"total_users" => $total_users,
			"total_balls" => $total_balls,
			"balls_user" => $balls_user,
			"votes" => $votes,
			"total_voters" => $total_voters,
			"mods" => $mods
			)
		);
	}

	public function vote_poll($pollid, $hash, $redirect=0) 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		$pollid = intval($pollid);
		$redirect = intval($redirect);
		$hash = $this->common->nohtml($hash);

		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}
		$poll = $poll->row();
		if($poll->hash != $hash) {
			$this->template->error(lang("error_76"));
		}

		if($poll->status != 1) {
			$this->template->error(lang("error_77"));
		}

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}

		if($poll->user_restricted) {
			if($userid == 0) {
				$this->template->error(lang("error_94"));
			}
			
			$vote = $this->polls_model->get_poll_vote($pollid, $userid);
			$answerid = $this->input->post("poll_answer");
			
			if($answerid==null){
				$this->template->error(lang("error_71"));
			}
			
			$mods = $this->polls_model->get_answer_mod($userid, $pollid);
			
			
			if($vote->num_rows() > 0) {
				if($poll->votes_limit == 0){
					
					$this->session->set_flashdata("warning", lang("success_50"));
					//$this->session->set_flashdata("globalmsg", lang("success_50"));
					
					if($poll->ex_a == 1 && $poll->ex_r == 1){
							redirect(site_url("polls/view_poll2/" . $poll->ID 
								. $poll->hash. "/1" ));
						}else{
							redirect(site_url("polls/view_poll/" . $poll->ID . "/" 
								. $poll->hash));	
						}
				}else{
					
				if($mods<=($poll->votes_limit-1)){
					
						$this->polls_model->add_answer_mod($userid, $pollid, $answerid);

						$old_vote = $vote->row()->answerid;
						$get = $this->polls_model->get_answer_id($userid, $pollid);
						$aid = $get->row();
												
						$this->polls_model->change_vote($aid->ID, $old_vote, $answerid);
						
						$this->session->set_flashdata("globalmsg", lang("success_49"));
						if($redirect){		
							if($poll->ex_a == 1 && $poll->ex_r == 1){
								redirect(site_url("polls/view_poll2/" . $poll->ID . 
									$poll->hash ."/1" ));
							}else{
								redirect(site_url("polls/view_poll/" . $poll->ID . "/" 
									. $poll->hash));	
							}
							} else {	
							if($poll->ex_a == 1 && $poll->ex_r == 1){
								redirect(site_url("polls/view_poll2/" . $poll->ID 
									. $poll->hash. "/1" ));
							}else{
								redirect(site_url("polls/view_poll/" . $poll->ID . "/" 
									. $poll->hash));	
							}		
						}					
				}else{
					$this->session->set_flashdata("warning", lang("error_100"));
					redirect(site_url("polls/view_poll/" . $poll->ID . "/" 
									. $poll->hash));
					//$this->session->set_flashdata("globalmsg", lang("success_50"));
				}
			}
		}
			
		} else {
			if($poll->ip_restricted) {
				// Check user has not already voted
				$s = $this->polls_model->check_user_vote($pollid);
				if($s->num_rows() > 0) {
					$this->template->error(lang("error_78"));
				}
			}

			if($poll->cookie_restricted) {
				$this->load->helper('cookie');
				if($this->input->cookie("vote_poll_" . $pollid)) {
					$this->template->error(lang("error_78"));
				}

				// Set Cookie
				set_cookie(array(
					"name" => "vote_poll_" . $pollid,
					"value" => $pollid,
					"expire" => time() + ((24*3600) * 91),
					"path" => "/"
					)
				);
			}
		}

		
			// Convert timestamp to days hours mins
		  $ff =($poll->start+($poll->timestamp));
		  $time = $this->common->convert_time($ff);
		  unset($time['secs']);

		  $fecha_final = date($this->settings->info->date_format, $poll->start+$poll->timestamp);

		// Make sure poll has expired
		if($poll->timestamp > 0) {
			if($ff < time()) {
				$this->template->error(lang("error_79"));
			}
		}

		// Check the poll hasn't hit the restricted amount of votes
		if($this->settings->info->default_votes > 0) {

			// Get user plan
			$plan = null;
			if($poll->premium_time > time() || $poll->premium_time == -1) {
				$plan = $this->funds_model
					->get_plan($poll->premium_planid);
			}
			if($plan != null && $plan->num_rows() > 0) {
				$plan = $plan->row();
				if($plan->votes != 0) {
					if($poll->votes >= $plan->votes) {
						$this->template->error(lang("error_80"));
					}
				}
			} else {
				if($poll->votes >= $this->settings->info->default_votes) {
					$this->template->error(lang("error_80"));
				}
			}
		}


		$votes = array();

		if($poll->vote_type == 0) {
			$answerid = intval($this->input->post("poll_answer"));

			$answer = $this->polls_model->get_poll_answer($pollid, $answerid);
			if($answer->num_rows() == 0) {
				$this->template->error(lang("error_81"));
			}
			$answer = $answer->row();
			$votes[] = array("answerid" => (int) $answerid, 
				"votes" => (int) $answer->votes);
		} else {
			// Checkbox check
			$answers = $this->polls_model->get_poll_answers($pollid);
			foreach($answers->result() as $a) {
				if(isset($_POST['poll_answer_' . $a->ID])) {
					$votes[] = array("answerid" => (int) $a->ID, 
						"votes" => (int) $a->votes);
				}
			}
		}

		if(empty($votes)) {
			$this->template->error(lang("error_95"));	
		}

		if($this->settings->info->country_tracking) {
			$ip = $_SERVER['SERVER_NAME'];
			$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
			if($details) {
				// Look to see if country is voted in
				$country_string = $details->country;
				$country = $this->polls_model
					->get_country_vote($poll->ID, $country_string);
				if($country->num_rows() > 0) {
					$country = $country->row();
					$this->polls_model->update_country_vote($country->ID, 
						array(
							"votes" => $country->votes + 1
						)
					);
				} else {
					$this->polls_model->add_country_vote(array(
						"pollid" => $poll->ID,
						"country" => $country_string,
						"votes" => 1
						)
					);
				}
			}
		}

		foreach($votes as $answer) {
			// Add the vote
			$this->polls_model->add_vote(array(
				"userid" => $userid,
				"pollid" => $pollid,
				"answerid" => $answer['answerid'],
				"IP" => $_SERVER['SERVER_NAME'],
				"user_agent" => $_SERVER['HTTP_USER_AGENT'],
				"timestamp" => time(),
				"date_stamp" => date("Y-m-d")
				)
			);

			// Update votes 
			$this->polls_model->update_answer($answer['answerid'], array(
				"votes" => $answer['votes'] + 1
				)
			);
		}

		if(date("y-m-d",$poll->votes_today_timestamp) == date("y-m-d")) 
		{
			$votes_today = $poll->votes_today + count($votes);
			$votes_today_timestamp = $poll->votes_today_timestamp;
		} else {
			$votes_today = count($votes);
			$votes_today_timestamp = time();
		}

		if(date("y-m",$poll->votes_month_timestamp) == date("y-m")) 
		{
			$votes_month = $poll->votes_month + count($votes);
			$votes_month_timestamp = $poll->votes_month_timestamp;
		} else {
			$votes_month = count($votes);
			$votes_month_timestamp = time();
		}

		$this->polls_model->update_poll($pollid, array(
			"votes" => $poll->votes + count($votes),
			"votes_today" => $votes_today,
			"votes_today_timestamp" => $votes_today_timestamp,
			"votes_month" => $votes_month,
			"votes_month_timestamp" => $votes_month_timestamp
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_39"));
		if($redirect) {		
		if($poll->ex_a == 1 && $poll->ex_r == 1){
			redirect(site_url("polls/view_poll2/" . $poll->ID
				. $poll->hash . "/1" ));
		}else{
			redirect(site_url("polls/view_poll/" . $poll->ID . "/" 
				. $poll->hash));	
		}
		} else {	
		if($poll->ex_a == 1 && $poll->ex_r == 1){
			redirect(site_url("polls/view_poll2/" . $poll->ID
				. $poll->hash . "/1" ));
		}else{
			redirect(site_url("polls/view_poll/" . $poll->ID . "/" 
				. $poll->hash));	
		}		
		}
	}
	
	/*
	
	VOTACION DE PRUEBA PARA MODELO CON APROVACION Y RECHAZO COMPLETA
	
	*/
	
		public function vote_poll2($pollid, $hash, $redirect) 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		$pollid = intval($pollid);
		$redirect = intval($redirect);
		$hash = $this->common->nohtml($hash);

		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}
		$poll = $poll->row();
		if($poll->hash != $hash) {
			$this->template->error(lang("error_76"));
		}

		if($poll->status != 1) {
			$this->template->error(lang("error_77"));
		}

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}

		if($poll->user_restricted) {
			if($userid == 0) {
				$this->template->error(lang("error_94"));
			}			
		} else {

			if($poll->cookie_restricted) {
				$this->load->helper('cookie');
				if($this->input->cookie("vote_poll_" . $pollid)) {
					$this->template->error(lang("error_78"));
				}

				// Set Cookie
				set_cookie(array(
					"name" => "vote_poll_" . $pollid,
					"value" => $pollid,
					"expire" => time() + ((24*3600) * 91),
					"path" => "/"
					)
				);
			}
		}

			// Convert timestamp to days hours mins
		  $ff =($poll->start+($poll->timestamp));
		  $time = $this->common->convert_time($ff);
		  unset($time['secs']);

		  $fecha_final = date($this->settings->info->date_format, $poll->start+$poll->timestamp);

		// Make sure poll has expired
		if($poll->timestamp > 0) {
			if($ff < time()) {
				$this->template->error(lang("error_79"));
			}
		}
		
		$vote = $this->polls_model->get_poll_vote($pollid, $userid);
		$mods = $this->polls_model->get_answer_mod($userid, $pollid);
		
			if($vote->num_rows() > 0){ //Comprueba si el usuario ha votado
				if($poll->votes_limit == 0){ //Verficia el limite de modificaciones en el modelo
					
					//Muestra el mensaje de que ya votaste
					 $this->template->error(lang("error_1"));
					 
					$this->session->set_flashdata("globalmsg", lang("error_50"));
					//Redirecciona al modelo anterior
					redirect(site_url("polls/view_poll2/" . $poll->ID . $poll->hash. "/1" ));

				}
				
				if($mods<=$poll->votes_limit-1){ //Comprueba que el usuario no haya votado mas del limite
						
						//Obtiene todas las respuestas			
						$answers = $this->polls_model->get_poll_answers($pollid);
						$votes_t = array();
												
						foreach($answers->result() as $a) {
							if(isset($_POST['answer-a-' . $a->ID]) && isset($_POST['answer-r-' . $a->ID])) {
								$votes_t[] = array(
								"answerid" => (int) $a->ID, 
									"ex_a" => (int) $a->ex_a,
									"ex_r" => (int) $a->ex_r);
							}
						}
					
						//Si no hay respuesta envia un error
						if(empty($votes_t)){
							$this->template->error(lang("error_99"));	
						}
						
						foreach($votes_t as $answer) {
						//Cambia el voto
						$this->polls_model->change_vote3($pollid, $userid, array(
							"answerid" => $answer['answerid'],
							"ex_a" => $_POST['answer-a-' . $answer['answerid']],
							"ex_r" => $_POST['answer-r-' . $answer['answerid']]							
							));
						}
						
						//Agrega a la base de datos la modificacion del voto
						$this->polls_model->add_answer_mod($userid, $pollid);
						
						//Mensaje diciendo que todo salio bien
						$this->session->set_flashdata("globalmsg", lang("success_49"));
						
						//Redirecciona al usuario al modelo anterior
							redirect(site_url("polls/view_poll2/" . $poll->ID . "/" 
								. $poll->hash. "/1/"));				
								
				}else{
						$this->session->set_flashdata("globalmsg", lang("success_50"));
						//Redirecciona al usuario al modelo anterior
							redirect(site_url("polls/view_poll2/" . $poll->ID . "/" 
								. $poll->hash. "/1/"));	
				}
		}else{
			
			$votes_t = array();
			
			// Checkbox check
			$answers = $this->polls_model->get_poll_answers($pollid);
			
			foreach($answers->result() as $a) {
				if(isset($_POST['answer-a-' . $a->ID]) && isset($_POST['answer-r-' . $a->ID])) {
					$votes_t[] = array("answerid" => (int) $a->ID, 
						"ex_a" => (int) $a->ex_a,
						"ex_r" => (int) $a->ex_r);
				}
			}

			if(empty($votes_t)) {
				$this->template->error(lang("error_99"));	
			}

		
			foreach($votes_t as $answer) {
				// Add the vote
				$this->polls_model->add_vote(array(
					"userid" => $userid,
					"pollid" => $pollid,
					"answerid" => $answer['answerid'],
					"IP" => $_SERVER['SERVER_NAME'],
					"user_agent" => $_SERVER['HTTP_USER_AGENT'],
					"timestamp" => time(),
					"date_stamp" => date("Y-m-d"),
					"ex_a" => $_POST['answer-a-' . $answer['answerid']],
					"ex_r" => $_POST['answer-r-' . $answer['answerid']]
					
					)
				);

				// Update votes 
				$this->polls_model->update_answer($answer['answerid'], array(
					"ex_a" => $answer['ex_a'] + $_POST['answer-a-' . $answer['answerid']],
					"ex_r" => $answer['ex_r'] + $_POST['answer-r-' . $answer['answerid']]
					
					)
				);
			}
				
		if(date("y-m-d",$poll->votes_today_timestamp) == date("y-m-d")) 
		{
			$votes_today = $poll->votes_today++;
			$votes_today_timestamp = $poll->votes_today_timestamp;
		} else {
			$votes_today++;
			$votes_today_timestamp = time();
		}

		if(date("y-m",$poll->votes_month_timestamp) == date("y-m")) 
		{
			$votes_month = $poll->votes_month ++;
			$votes_month_timestamp = $poll->votes_month_timestamp;
		} else {
			$votes_month++;
			$votes_month_timestamp = time();
		}

		$this->polls_model->update_poll($pollid, array(
			"votes_today" => $votes_today,
			"votes_today_timestamp" => $votes_today_timestamp,
			"votes_month" => $votes_month,
			"votes_month_timestamp" => $votes_month_timestamp
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_39"));
		if($redirect) {		
			redirect(site_url("polls/view_poll2/" . $poll->ID . "/" 
				. $poll->hash. "/1/"));
		} else {	
			redirect(site_url("polls/view_poll2/" . $poll->ID . "/" 
				. $poll->hash. "/1/"));
		}
	}
	}
	//---------------------------------------------------------------
	public function get_poll_results($pollid) 
	{
		$this->requirements();
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		$answers = $this->polls_model->get_poll_answers($poll->ID);

		$this->template->loadAjax("polls/view_poll_results.php", array(
			"poll" => $poll,
			"answers" => $answers
			),1
		);
	}

	public function results($pollid) 
	{
		$this->requirements();
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			/* if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			} */
		}

		$votes = $this->polls_model->get_recent_votes2($poll->ID, 1);

		$last_dates = array();

		for ($i=6; $i>-1; $i--) {
			$date = date("Y-m-d", strtotime($i." days ago"));
			$vote = array(
				"date" => $date,
				"votes" => $this->polls_model->count_votes_date($date, $poll->ID)
			);
		    $last_dates[] = $vote;
		}

		$javascript = 'var data_graph = {
					    labels: [';
		    foreach($last_dates as $d) {
		    	$javascript .= '"'.$d['date'].'",';
		    }
		    $javascript.='],
		    datasets: [
		        {
		            label: "My First dataset",
		            fillColor: "rgba(220,220,220,0.2)",
		            strokeColor: "rgba(220,220,220,1)",
		            pointColor: "rgba(220,220,220,1)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(220,220,220,1)",
		            data: [';
		            foreach($last_dates as $d) {
				    	$javascript .= $d['votes'].',';
				    }
		            $javascript.=']
		        }
		    ]
		};';

		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>
			<script type="text/javascript">'.$javascript.'</script>
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/results.js" /></script>');



		$this->template->loadContent("polls/results.php", array(
			"poll" => $poll,
			"votes" => $votes,
			)
		);
	}

	public function results_votes($pollid, $page=0) 
	{
		$this->requirements();
		$pollid = intval($pollid);
		$page = intval($page);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
		/* 	if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			} */
		}

		$votes = $this->polls_model->get_votes($pollid, $page);

		$this->load->library('pagination');
		$config['base_url'] = site_url("polls/results_votes/" . $pollid ."/");
		$config['total_rows'] = $this->polls_model->get_total_votes_count($pollid);
		$config['per_page'] = 20;
		$config['uri_segment'] = 4;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 
		
		$this->template->loadContent("polls/results_votes.php", array(
			"poll" => $poll,
			"votes" => $votes
			)
		);
	}

	public function clear_results($pollid, $hash) 
	{
		$this->requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		// Reset
		$this->polls_model->update_poll($pollid, array(
			"votes" => 0,
			"votes_today" => 0,
			"votes_today_timestamp" => 0,
			"votes_month" => 0,
			"votes_month_timestamp" => 0
			)
		);

		$this->polls_model->update_all_poll_answers($pollid, array(
			"votes" => 0
			)
		);

		$this->polls_model->delete_poll_votes($pollid);
		$this->session->set_flashdata("globalmsg", lang("success_40"));
		redirect(site_url("polls/edit_poll/" . $pollid));
	}

	public function ajax_poll($pollid, $hash) 
	{		
		$pollid = intval($pollid);
		$hash = $this->common->nohtml($hash);

		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) {
			$this->template->errori(lang("error_76"));
		}
		$poll = $poll->row();
		if($poll->hash != $hash) {
			$this->template->errori(lang("error_76"));
		}

		if($poll->status != 1) {
			$this->template->errori(lang("error_77"));
		}
		
		if($this->user->info->ID!=$poll->userid){
		if($poll->public==1){
			$group = $this->admin_model->get_user_from_group($this->user->info->ID,$poll->groupid);		
			if($group->num_rows() == 0){
					$this->template->error(lang("error_96"));
				}
		}
		}
		
		$answers = $this->polls_model->get_poll_answers($poll->ID);


		if($poll->user_restricted) {
			if(!$this->user->loggedin) {
				$this->template->errori(lang("error_94"));
			}
		}

		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}

		if($poll->ip_restricted) {
			$user_vote = $this->polls_model->check_user_vote($pollid);
		} else {
			$user_vote = $this->polls_model->get_poll_vote($pollid, 
				$userid);
		}

		$this->template->loadAjax("polls/view_poll_ajax.php", array(
			"poll" => $poll,
			"answers" => $answers,
			"user_vote" => $user_vote,
			"user_vote_num" => $user_vote->num_rows()
			),1
		);
	}

	public function ajax_poll_refresh($pollid, $hash) 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		$pollid = intval($pollid);
		$hash = $this->common->nohtml($hash);
		$redirect = intval($this->input->get("redirect"));

		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) {
			$this->template->errori(lang("error_76"));
		}
		$poll = $poll->row();
		if($poll->hash != $hash) {
			$this->template->errori(lang("error_76"));
		}

		if($poll->status != 1) {
			$this->template->errori(lang("error_77"));
		}

		$answers = $this->polls_model->get_poll_answers($poll->ID);

		if($poll->user_restricted) {
			if(!$this->user->loggedin) {
				$this->template->errori(lang("error_94"));
			}
		}

		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}

		if($poll->ip_restricted) {
			$user_vote = $this->polls_model->check_user_vote($pollid);
		} else {
			$user_vote = $this->polls_model->get_poll_vote($pollid, 
				$userid);
		}

		$this->template->loadAjax("polls/view_poll_ajax_refresh.php", array(
			"poll" => $poll,
			"answers" => $answers,
			"user_vote" => $user_vote,
			"user_vote_num" => $user_vote->num_rows(),
			"ajax_redirect" => $redirect
			),1
		);
	}

	public function delete_poll($pollid, $hash) 
	{
		$this->requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->jsonError(lang("error_72"));

		$poll = $poll->row();
		if($poll->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array("admin", "admin_poll"),
			 $this->user)) {
				$this->template->jsonError(lang("error_2"));
			}
		}

		$this->polls_model->delete_poll($pollid);
		$this->polls_model->delete_poll_votes($pollid);

		$this->session->set_flashdata("globalmsg", lang("success_30"));
		redirect(site_url("polls"));
	}

}

?>