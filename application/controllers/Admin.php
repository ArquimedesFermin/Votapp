<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("admin_model");
		$this->load->model("user_model");
		$this->load->model("home_model");
		$this->load->model("polls_model");

		if (!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		if(!$this->common->has_permissions(array("admin", "admin_settings",
			"admin_members", "admin_payment","poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
	}


	public function index(){	
		$this->template->loadData("activeLink", 
			array("admin" => array("general" => 1)));

		$new_members = $this->user_model->get_new_members(5);
		$months = array();

		// Graph Data
		$current_month = date("n");
		$current_year = date("Y");

		// First month
		for($i=6;$i>=0;$i--) {
			// Get month in the past
			$new_month = $current_month - $i;
			// If month less than 1 we need to get last years months
			if($new_month < 1) {
				$new_month = 12 + $new_month;
				$new_year = $current_year - 1;
			} else {
				$new_year = $current_year;
			}
			// Get month name using mktime
			$timestamp = mktime(0,0,0,$new_month,1,$new_year);
			$count = $this->user_model
				->get_registered_users_date($new_month, $new_year);
			$months[] = array(
				"date" => date("F", $timestamp),
				"count" => $count
				);
		}


		$javascript = 'var data_graph = {
					    labels: [';
		    foreach($months as $d) {
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
		            foreach($months as $d) {
				    	$javascript .= $d['count'].',';
				    }
		            $javascript.=']
		        }
		    ]
		};';


		$stats = $this->home_model->get_home_stats();
		if($stats->num_rows() == 0) {
			$this->template->error(lang("error_24"));
		} else {
			$stats = $stats->row();
			if($stats->timestamp < time() - 3600 * 5) {
				$stats = $this->get_fresh_results($stats);
				// Update Row
				$this->home_model->update_home_stats($stats);
			}
		}


		$javascript .= ' var social_data = [
		    {
		        value: '.$stats->google_members.',
		        color:"#F7464A",
		        highlight: "#FF5A5E",
		        label: "Google"
		    },
		    {
		        value: '.($stats->total_members - ($stats->google_members +
		         $stats->facebook_members + $stats->twitter_members)).',
		        color: "#39bc2c",
		        highlight: "#5AD3D1",
		        label: "'.lang("ctn_242").'"
		    },
		    {
		        value: '.$stats->facebook_members.',
		        color: "#2956BF",
		        highlight: "#FFC870",
		        label: "Facebook"
		    },
		    {
		        value: '.$stats->twitter_members.',
		        color: "#5BACD4",
		        highlight: "#7db864",
		        label: "Twitter"
		    }
		];';


		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>
			<script type="text/javascript">'.$javascript.'</script>
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/home.js" /></script>
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/jquery.filtertable.min.js" /></script>'
		);

		$online_count = $this->user_model->get_online_count();

		$this->template->loadContent("admin/index.php", array(
			"new_members" => $new_members,
			"stats" => $stats,
			"online_count" => $online_count
			)
		);
	}

	private function get_fresh_results($stats) 
	{
		$data = new STDclass;

		$data->google_members = $this->user_model->get_oauth_count("google");
		$data->facebook_members = $this->user_model->get_oauth_count("facebook");
		$data->twitter_members = $this->user_model->get_oauth_count("twitter");
		$data->total_members = $this->user_model->get_total_members_count();
		$data->new_members = $this->user_model->get_new_today_count();
		$data->active_today = $this->user_model->get_active_today_count();

		return $data;
	}

	public function user_roles() 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$this->template->loadData("activeLink", 
			array("admin" => array("user_roles" => 1)));
		$roles = $this->admin_model->get_user_roles();
		$this->template->loadContent("admin/user_roles.php", array(
			"roles" => $roles
			)
		);
	}

	public function add_user_role_pro() 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));

		$name = $this->common->nohtml($this->input->post("name"));
		if (empty($name)) $this->template->error(lang("error_89"));

		$admin = intval($this->input->post("admin"));
		$voter = intval($this->input->post("voter"));
		$admin_settings = intval($this->input->post("admin_settings"));
		$admin_members = intval($this->input->post("admin_members"));
		$admin_payment = intval($this->input->post("admin_payment"));
		$banned = intval($this->input->post("banned"));

		$poll_creator = intval($this->input->post("poll_creator"));
		$admin_poll = intval($this->input->post("admin_poll"));

		$this->admin_model->add_user_role(
			array(
				"name" =>$name,
				"admin" => $admin,
				"voter" => $voter,
				"admin_settings" => $admin_settings,
				"admin_members" => $admin_members,
				"admin_payment" => $admin_payment,
				"banned" => $banned,
				"poll_creator" => $poll_creator,
				"admin_poll" => $admin_poll
				)
			);
		$this->session->set_flashdata("globalmsg", lang("success_44"));
		redirect(site_url("admin/user_roles"));
	}

	public function edit_user_role($id) 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$id = intval($id);
		$role = $this->admin_model->get_user_role($id);
		if ($role->num_rows() == 0) $this->template->error(lang("error_90"));

		$this->template->loadData("activeLink", 
			array("admin" => array("user_roles" => 1)));

		$this->template->loadContent("admin/edit_user_role.php", array(
			"role" => $role->row()
			)
		);
	}

	public function edit_user_role_pro($id) 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$id = intval($id);
		$role = $this->admin_model->get_user_role($id);
		if ($role->num_rows() == 0) $this->template->error(lang("error_90"));

		$name = $this->common->nohtml($this->input->post("name"));
		if (empty($name)) $this->template->error(lang("error_89"));

		$admin = intval($this->input->post("admin"));
		$voter = intval($this->input->post("voter"));
		$admin_settings = intval($this->input->post("admin_settings"));
		$admin_members = intval($this->input->post("admin_members"));
		$admin_payment = intval($this->input->post("admin_payment"));
		$banned = intval($this->input->post("banned"));
		$poll_creator = intval($this->input->post("poll_creator"));
		$admin_poll = intval($this->input->post("admin_poll"));

		$this->admin_model->update_user_role($id, 
			array(
				"name" =>$name,
				"admin" => $admin,
				"admin_settings" => $admin_settings,
				"admin_members" => $admin_members,
				"admin_payment" => $admin_payment,
				"banned" => $banned,
				"poll_creator" => $poll_creator,
				"admin_poll" => $admin_poll
				)
		);
		$this->session->set_flashdata("globalmsg", lang("success_45"));
		redirect(site_url("admin/user_roles"));
	}

	public function delete_user_role($id, $hash) 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_role($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_90"));

		$this->admin_model->delete_user_role($id);
		// Delete all user groups from member

		$this->session->set_flashdata("globalmsg", lang("success_46"));
		redirect(site_url("admin/user_roles"));
	}

	public function premium_users() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("premium_users" => 1)));

		$this->template->loadContent("admin/premium_users.php", array(
			)
		);
	}

	public function premium_users_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.joined", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"users.first_name" => 0
				 ),
				 2 => array(
				 	"users.last_name" => 0
				 ),
				 3 => array(
				 	"users.email" => 0
				 ),
				 4 => array(
				 	"payment_plans.name" => 0
				 ),
				 5 => array(
				 	"users.premium_time" => 0
				 ),
				 6 => array(
				 	"users.joined" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_premium_users_count()
		);
		$members = $this->admin_model->get_premium_users($this->datatables);

		foreach($members->result() as $r) {
			$time = $this->common->convert_time($r->premium_time); 
			  unset($time['mins']);
			  unset($time['secs']);
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->first_name,
				$r->last_name,
				$r->email,
				$r->name,
				$this->common->get_time_string($time),
				date($this->settings->info->date_format, $r->joined),
				'<a href="'.site_url("admin/edit_member/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("admin/delete_member/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function user_polls($userid,$page=0) 
	{
		/* if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		} */
		$userid = intval($userid);
		$this->template->loadData("activeLink", 
			array("admin_poll" => array("manage" => 1)));

		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) $this->template->error(lang("error_52"));

		$page = intval($page);
		$polls = $this->admin_model->get_user_polls($userid, $page);
		if($polls->num_rows() == 0) $this->template->error(lang("error_64"));

		$this->load->library('pagination');
		$config['base_url'] = site_url("admin/user_polls/" . $userid);
		$config['total_rows'] = $this->admin_model
			->get_total_user_polls_count($userid);
		$config['per_page'] = 20;
		$config['uri_segment'] = 3;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 

		$this->template->loadContent("admin/user_polls.php", array(
			"polls" => $polls,
			"user" => $user->row()
			)
		);
	}

	public function manage_polls($page = 0) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin_poll" => array("manage" => 1)));

		$this->template->loadContent("admin/polls.php", array(
		
			)
		);
	}

	public function poll_page() 
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
				 	"users.username" => 0
				 ),
			)
		);

		

		$this->datatables->set_total_rows(
			$this->admin_model->get_total_polls()
		);

		$polls = $this->admin_model->get_all_polls($this->datatables);
		

		foreach($polls->result() as $r) {

			if($r->status == 0) {
				$status = lang("ctn_334");
			} else if($r->status == 1) {
				$status = lang("ctn_332");
			} else if($r->status == 2) {
				$status = lang("ctn_333");
			}

			if($r->ex_a == 1 && $r->ex_r == 1){
				$op = '<a href="'.site_url("polls/view_poll2/" . $r->ID . "/" . $r->hash ."/1").'" class="btn btn-primary btn-xs">'.lang("ctn_335").'</a> <a href="'.site_url("polls/edit_poll_pro/" . $r->ID) .'" class="btn btn-info btn-xs" title="'.lang("ctn_379").'" data-toggle="tooltip" data-placement="bottom">Ajustes</a> <a href="'.site_url("polls/edit_poll/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_358").'"><span class="glyphicon glyphicon-cog"></span></a>';
			}else{
				$op = '<a href="'.site_url("polls/view_poll/" . $r->ID . "/" . $r->hash).'" class="btn btn-primary btn-xs">'.lang("ctn_335").'</a> <a href="'.site_url("polls/edit_poll_pro/" . $r->ID) .'" class="btn btn-info btn-xs" title="'.lang("ctn_379").'" data-toggle="tooltip" data-placement="bottom">Ajustes</a> <a href="'.site_url("polls/edit_poll/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_358").'"><span class="glyphicon glyphicon-cog"></span></a>';
			}			
			
			
			// Convert timestamp to days hours mins
			  $ff =($r->start+($r->timestamp));
			  $time = $this->common->convert_time($ff);
			  unset($time['secs']);	
			
			if($ff > time() ) {
				$time = $this->common->convert_time($ff);
				unset($time['secs']);
				$time = $this->common->get_time_string($time);
			} else {
				if($ff > 0) {
					$time = '<span class="label label-danger">'. lang("ctn_384") .'</span>';
				} else {
					$time = lang("ctn_385");
				}
			}	
			
			$this->datatables->data[] = array(
				$r->name,
				$r->votes,
				$status,
				date($this->settings->info->date_format, $r->created),
				$time,
				$op
				);
		}
		echo json_encode($this->datatables->process());
	}

	


	public function delete_poll($pollid, $hash) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$this->load->model("polls_model");
		$pollid = intval($pollid);
		$poll = $this->polls_model->get_poll($pollid);
		if($poll->num_rows() == 0) $this->template->error(lang("error_65"));

		$this->polls_model->delete_poll($pollid);
		$this->polls_model->delete_poll_votes($pollid);
		$this->polls_model->delete_poll_countries($pollid);

		$this->session->set_flashdata("globalmsg", lang("success_30"));
		redirect(site_url("admin/manage_polls"));
	}

	public function poll_themes() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin_poll" => array("poll_themes" => 1)));

		$themes = $this->polls_model->get_poll_themes();

		$this->template->loadContent("admin/poll_themes.php", array(
			"themes" => $themes
			)
		);
	}

	public function edit_poll_theme($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$theme = $this->polls_model->get_poll_theme($id);
		if($theme->num_rows() == 0) $this->template->error(lang("error_66"));

		$this->template->loadContent("admin/edit_poll_theme.php", array(
			"theme" => $theme->row()
			)
		);
	}

	public function edit_poll_theme_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$theme = $this->polls_model->get_poll_theme($id);
		if($theme->num_rows() == 0) $this->template->error(lang("error_66"));

		$name = $this->common->nohtml($this->input->post("name"));
		$css_code = $this->common->nohtml($this->input->post("css_code"));

		if(empty($name)) $this->template->error(lang("error_67"));

		// Add
		$this->polls_model->update_poll_theme($id, array(
			"name" => $name,
			"css_code" => $css_code
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_31"));
		redirect(site_url("admin/poll_themes"));
	}

	public function add_poll_theme() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$css_code = $this->common->nohtml($this->input->post("css_code"));

		if(empty($name)) $this->template->error(lang("error_67"));

		// Add
		$this->polls_model->add_poll_theme(array(
			"name" => $name,
			"css_code" => $css_code
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_32"));
		redirect(site_url("admin/poll_themes"));
	}

	public function delete_poll_theme($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$theme = $this->polls_model->get_poll_theme($id);
		if($theme->num_rows() == 0) $this->template->error(lang("error_66"));

		// Delete
		$this->polls_model->delete_theme($id);
		$this->session->set_flashdata("globalmsg", lang("success_33"));
		redirect(site_url("admin/poll_themes"));
	}

	public function poll_settings() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin_poll" => array("poll_settings" => 1)));
		$this->template->loadContent("admin/poll_settings.php", array(
			)
		);
	}

	public function poll_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_poll"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$country_tracking = intval($this->input->post("country_tracking"));
		$auto_updating = intval($this->input->post("auto_updating"));
		$default_votes = intval($this->input->post("default_votes"));
		$enable_ads = intval($this->input->post("enable_ads"));

		$this->admin_model->updateSettings(
			array(
				"country_tracking" => $country_tracking,
				"auto_updating" => $auto_updating,
				"default_votes" => $default_votes,
				"enable_ads" => $enable_ads
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/poll_settings"));
	}

	public function payment_logs() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_logs" => 1)));


		$this->template->loadContent("admin/payment_logs.php", array(
			)
		);
	}

	public function payment_logs_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.joined", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 2 => array(
				 	"payment_logs.amount" => 0
				 ),
				 3 => array(
				 	"payment_logs.timestamp" => 0
				 ),
				 4 => array(
				 	"payment_logs.processor" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_payment_logs_count()
		);
		$logs = $this->admin_model->get_payment_logs($this->datatables);

		foreach($logs->result() as $r) {
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->email,
				number_format($r->amount, 2),
				date($this->settings->info->date_format, $r->timestamp),
				$r->processor
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function payment_plans() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_plans" => 1)));
		$plans = $this->admin_model->get_payment_plans();

		$this->template->loadContent("admin/payment_plans.php", array(
			"plans" => $plans
			)
		);
	}

	public function add_payment_plan() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$cost = abs($this->input->post("cost"));
		$color = $this->common->nohtml($this->input->post("color"));
		$fontcolor = $this->common->nohtml($this->input->post("fontcolor"));
		$days = intval($this->input->post("days"));
		$votes = intval($this->input->post("votes"));

		$this->admin_model->add_payment_plan(array(
			"name" => $name,
			"cost" => $cost,
			"hexcolor" => $color,
			"days" => $days,
			"description" => $desc,
			"fontcolor" => $fontcolor,
			"votes" => $votes
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_25"));
		redirect(site_url("admin/payment_plans"));
	}

	public function edit_payment_plan($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_plans" => 1)));
		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$this->template->loadContent("admin/edit_payment_plan.php", array(
			"plan" => $plan->row()
			)
		);
	}

	public function edit_payment_plan_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$cost = abs($this->input->post("cost"));
		$color = $this->common->nohtml($this->input->post("color"));
		$fontcolor = $this->common->nohtml($this->input->post("fontcolor"));
		$days = intval($this->input->post("days"));
		$votes = intval($this->input->post("votes"));

		$this->admin_model->update_payment_plan($id, array(
			"name" => $name,
			"cost" => $cost,
			"hexcolor" => $color,
			"days" => $days,
			"description" => $desc,
			"fontcolor" => $fontcolor,
			"votes" => $votes
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_26"));
		redirect(site_url("admin/payment_plans"));
	}

	public function delete_payment_plan($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$this->admin_model->delete_payment_plan($id);
		$this->session->set_flashdata("globalmsg", lang("success_27"));
		redirect(site_url("admin/payment_plans"));
	}

	public function payment_settings() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_settings" => 1)));
		$this->template->loadContent("admin/payment_settings.php", array(
			)
		);
	}

	public function payment_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$paypal_email = $this->common->nohtml(
			$this->input->post("paypal_email"));
		$paypal_currency = $this->common->nohtml(
			$this->input->post("paypal_currency"));
		$payment_enabled = intval($this->input->post("payment_enabled"));
		$payment_symbol = $this->common->nohtml(
			$this->input->post("payment_symbol"));
		$global_premium = intval($this->input->post("global_premium"));

		$stripe_publish_key = $this->common->nohtml($this->input->post("stripe_publish_key"));
		$stripe_secret_key = $this->common->nohtml($this->input->post("stripe_secret_key"));

		$checkout2_accountno = intval($this->input->post("checkout2_accountno"));
		$checkout2_secret = $this->common->nohtml($this->input->post("checkout2_secret"));

		// update
		$this->admin_model->updateSettings(
			array(
				"paypal_email" => $paypal_email,
				"paypal_currency" => $paypal_currency,
				"payment_enabled" => $payment_enabled,
				"payment_symbol" => $payment_symbol,
				"global_premium" => $global_premium,
				"stripe_publish_key" => $stripe_publish_key,
				"stripe_secret_key" => $stripe_secret_key,
				"checkout2_accountno" => $checkout2_accountno,
				"checkout2_secret" => $checkout2_secret
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_24"));
		redirect(site_url("admin/payment_settings"));

	}

	public function email_members() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("email_members" => 1)));
		$groups = $this->admin_model->get_user_groups();
		$this->template->loadContent("admin/email_members.php", array(
			"groups" => $groups
			)
		);
	}

	public function email_members_pro() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$usernames = $this->common->nohtml($this->input->post("usernames"));
		$groupid = intval($this->input->post("groupid"));
		$title = $this->common->nohtml($this->input->post("title"));
		$message = $this->lib_filter->go($this->input->post("message"));

		if ($groupid == -1) {
			// All members
			$users = array();
			$usersc = $this->admin_model->get_all_users();
			foreach ($usersc->result() as $r) {
				$users[] = $r;
			}
		} else {
			$usernames = explode(",", $usernames);

			$users = array();
			foreach ($usernames as $username) {
				if (empty($username)) continue;
				$user = $this->user_model->get_user_by_username($username);
				if ($user->num_rows() == 0) {
					$this->template->error(lang("error_3") . $username);
				}
				$users[] = $user->row();
			}

			if ($groupid > 0) {
				$group = $this->admin_model->get_user_group($groupid);
				if ($group->num_rows() == 0) {
					$this->template->error(lang("error_4"));
				}

				$users_g = $this->admin_model->get_all_group_users($groupid);
				$cursers = $users;

				foreach ($users_g->result() as $r) {
					// Check for duplicates
					$skip = false;
					foreach ($cusers as $a) {
						if($a->userid == $r->userid) $skip = true;
					}
					if (!$skip) {
						$users[] = $r;
					}
				}
			}

		}

		foreach ($users as $r) {
			$this->common->send_email($title, $message, $r->email);
		}

		$this->session->set_flashdata("globalmsg", lang("success_1"));
		redirect(site_url("admin/email_members"));
	}

	

	
	
	public function user_groups() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("user_groups" => 1)));
		$groups = $this->admin_model->get_user_groups_creator($this->user->info->ID);
		$users = $this->admin_model->get_all_users();
		
		$this->template->loadContent("admin/groups.php", array(
			"groups" => $groups,
			"users" => $users
			)
		);
	}
	
	public function add_group_pro() 
	{
		
		$saved="";
		if(!empty($_GET["saved"])){
			$saved = $_GET["saved"];
		}
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$default = intval($this->input->post("default_group"));
		$userid = $this->user->info->ID;
		if (empty($name)) $this->template->error(lang("error_5"));

		$this->admin_model->add_group($name, $default, $userid);
		$this->session->set_flashdata("globalmsg", lang("success_2"));
		if($saved == true){
		redirect(site_url("admin/user_groups/?saved=".$saved));
		}else{
		redirect(site_url("admin/user_groups"));
		}
	}

	public function edit_group($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$this->template->loadData("activeLink", 
			array("admin" => array("user_groups" => 1)));

		$this->template->loadContent("admin/edit_group.php", array(
			"group" => $group->row()
			)
		);
	}

	public function edit_group_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$name = $this->common->nohtml($this->input->post("name"));
		$default = intval($this->input->post("default_group"));
		if (empty($name)) $this->template->error(lang("error_5"));

		$this->admin_model->update_group($id, $name, $default);
		$this->session->set_flashdata("globalmsg", lang("success_3"));
		redirect(site_url("admin/user_groups"));
	}

	public function delete_group($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$this->admin_model->delete_group($id);
		// Delete all user groups from member
		$this->admin_model->delete_users_from_group($id); 

		$this->session->set_flashdata("globalmsg", lang("success_4"));
		redirect(site_url("admin/user_groups"));
	}

	public function view_group($id, $page=0) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("user_groups" => 1)));

		$id = intval($id);
		$page = intval($page);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$users = $this->admin_model->get_users_from_groups($id, $page);
		$userlist = $this->admin_model->get_all_users();
		
		$this->load->library('pagination');
		$config['base_url'] = site_url("admin/view_group/" . $id);
		$config['total_rows'] = $this->admin_model
			->get_total_user_group_members_count($id);
		$config['per_page'] = 20;
		$config['uri_segment'] = 3;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 
		

		$this->template->loadContent("admin/view_group.php", array(
			"group" => $group->row(),
			"users" => $users,
			"total_members" => $config['total_rows'],
			"userlist" => $userlist
			)
		);

	}

	public function add_user_to_group_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$usernames = $this->common->nohtml($this->input->post("usernames"));
		$usernames = explode(",", $usernames);

		$users = array();
		foreach ($usernames as $username) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) $this->template->error(lang("error_3") 
				. $username);
			$users[] = $user->row();
		}
			$site_name = $this->settings->info->site_name;
			$link = site_url();
			$title = $site_name." - Nuevo padrón asociado";
			
		foreach ($users as $user) {
			// Check not already a member
			$userc = $this->admin_model->get_user_from_group($user->ID, $id);
			if ($userc->num_rows() == 0) {
				$message = "Hola ".$user->first_name.", usted ha sido agregado al padrón ".$group->name." \t\t".$site_name;
				
				$this->admin_model->add_user_to_group($user->ID, $id);
				$this->common->send_email($title, $message, $user->email);
		
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_5"));
		redirect(site_url("admin/view_group/" . $id));
	}

	public function add_users_to_group($id,$users_list) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		//$id = $this->common->nohtml($this->input->post("id"));
		$id = intval($id);
		echo "<script>alert('".$id."'); </script>";
		$group = $this->admin_model->get_user_group($id);

		if($group->num_rows() == 0) $this->template->error(lang("error_4"));
		

		//$user_list = $this->common->nohtml($this->input->post("usernames"));
		$usernames = explode(",", $users_list);

		$users = array();
		foreach ($usernames as $username) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) $this->template->error(lang("error_3") 
				. $username);
			$users[] = $user->row();
		}
			$site_name = $this->settings->info->site_name;
			$link = site_url();
			$title = $site_name." - Nuevo padrón asociado";
			
		foreach ($users as $user) {
			// Check not already a member
			$userc = $this->admin_model->get_user_from_group($user->ID, $id);
			if ($userc->num_rows() == 0) {
				$message = "Hola ".$user->first_name.", usted ha sido agregado al padrón ".$group->name." \t\t".$site_name;
				
				$this->admin_model->add_user_to_group($user->ID, $id);
				$this->common->send_email($title, $message, $user->email);
		
			}
		}
		$saved="";
		if(!empty($_GET["saved"])){
			$saved = $_GET["saved"];
		}
		$this->session->set_flashdata("globalmsg", lang("success_5"));
		redirect(site_url("admin/view_group/" . $id."?saved=".$saved));
	}

		
	public function remove_user_from_group($userid, $id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members", "poll_creator"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$userid = intval($userid);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$user = $this->admin_model->get_user_from_group($userid, $id);
		if ($user->num_rows() == 0) $this->template->error(lang("error_7"));

		$this->admin_model->delete_user_from_group($userid, $id);
		$this->session->set_flashdata("globalmsg", lang("success_6"));
				$saved="";
		if(!empty($_GET["saved"])){
			$saved = $_GET["saved"];
		}
		redirect(site_url("admin/view_group/" . $id. "?saved=".$saved));
	}

	public function email_templates() 
	{
		if(!$this->common->has_permissions(array("admin"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("email_templates" => 1)));
		$email_templates = $this->admin_model->get_email_templates();
		$this->template->loadContent("admin/email_templates.php", array(
			"email_templates" => $email_templates
			)
		);
	}

	public function edit_email_template($id) 
	{
		if(!$this->common->has_permissions(array("admin"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("email_templates" => 1)));
		$id = intval($id);
		$email_template = $this->admin_model->get_email_template($id);
		if ($email_template->num_rows() == 0) {
			$this->template->error(lang("error_8"));
		}

		$this->template->loadContent("admin/edit_email_template.php", array(
			"email_template" => $email_template->row()
			)
		);
	}

	public function edit_email_template_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("email_templates" => 1)));
		$id = intval($id);
		$email_template = $this->admin_model->get_email_template($id);
		if ($email_template->num_rows() == 0) {
			$this->template->error(lang("error_8"));
		}

		$title = $this->common->nohtml($this->input->post("title"));
		$message = $this->lib_filter->go($this->input->post("message"));

		if (empty($title) || empty($message)) {
			$this->template->error(lang("error_9"));
		}

		$this->admin_model->update_email_template($id, $title, $message);
		$this->session->set_flashdata("globalmsg", lang("success_7"));
		redirect(site_url("admin/email_templates"));
	}

	public function ipblock() 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("ipblock" => 1)));

		$ipblock = $this->admin_model->get_ip_blocks();

		$this->template->loadContent("admin/ipblock.php", array(
			"ipblock" => $ipblock
			)
		);
	}

	public function add_ipblock() 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$ip = $this->common->nohtml($this->input->post("ip"));
		$reason = $this->common->nohtml($this->input->post("reason"));

		if (empty($ip)) $this->template->error(lang("error_10"));

		$this->admin_model->add_ipblock($ip, $reason);
		$this->session->set_flashdata("globalmsg", lang("success_8"));
		redirect(site_url("admin/ipblock"));
	}

	public function delete_ipblock($id) 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$ipblock = $this->admin_model->get_ip_block($id);
		if ($ipblock->num_rows() == 0) $this->template->error(lang("error_11"));

		$this->admin_model->delete_ipblock($id);
		$this->session->set_flashdata("globalmsg", lang("success_9"));
		redirect(site_url("admin/ipblock"));
	}

	public function members() 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("members" => 1))); 

		$user_roles = $this->admin_model->get_user_roles();

		$this->template->loadContent("admin/members.php", array(
			"user_roles" => $user_roles
			)
		);
	}

	public function members_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.joined", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"users.first_name" => 0
				 ),
				 2 => array(
				 	"users.last_name" => 0
				 ),
				 3 => array(
				 	"users.email" => 0
				 ),
				 4 => array(
				 	"user_roles.name" => 0
				 ),
				 5 => array(
				 	"users.joined" => 0
				 ),
				 6 => array(
				 	"users.oauth_provider" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->user_model
				->get_total_members_count()
		);
		$members = $this->user_model->get_members_admin($this->datatables);

		foreach($members->result() as $r) {
			if($r->oauth_provider == "google") {
				$provider = "Google";
			} elseif($r->oauth_provider == "twitter") {
				$provider = "Twitter";
			} elseif($r->oauth_provider == "facebook") {
				$provider = "Facebook";
			} else {
				$provider =  lang("ctn_196");
			}
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->first_name,
				$r->last_name,
				$r->email,
				$this->common->get_user_role($r),
				date($this->settings->info->date_format, $r->joined),
				$provider,
				'<a href="'.site_url("admin/edit_member/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("admin/delete_member/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function edit_member($id) 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("members" => 1)));
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$member = $member->row();
		
		$groups = $this->admin_model->get_user_groups_creator($id);

		$user_groups = $this->user_model->get_user_groups($id);
		
		$plan = $this->admin_model->get_payment_plan($member->premium_planid);

		$user_roles = $this->admin_model->get_user_roles();

		$this->template->loadContent("admin/edit_member.php", array(
			"member" => $member,
			"user_groups" => $user_groups,
			"plan" => $plan,
			"user_roles" => $user_roles,
			"groups" => $groups
			)
		);
	}

	public function edit_member_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$member = $member->row();

		$this->load->model("register_model");
		$email = $this->input->post("email", true);
		$first_name = $this->common->nohtml(
			$this->input->post("first_name", true));
		$last_name = $this->common->nohtml(
			$this->input->post("last_name", true));
		$pass = $this->common->nohtml(
			$this->input->post("password", true));
		$username = $this->common->nohtml(
			$this->input->post("username", true));
		$aboutme = $this->common->nohtml($this->input->post("aboutme"));
		$points = abs($this->input->post("credits"));
		$active = intval($this->input->post("active"));

		$user_role = intval($this->input->post("user_role"));

		if (strlen($username) < 3) $this->template->error(lang("error_14"));

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
			$this->template->error(lang("error_15"));
		}

		if ($username != $member->username) {
			if (!$this->register_model->check_username_is_free($username)) {
				 $this->template->error(lang("error_16"));
			}
		}

		if (!empty($pass)) {
			if (strlen($pass) <= 5) {
				 $this->template->error(lang("error_17"));
			}
			$pass = $this->common->encrypt($pass);
		} else {
			$pass = $member->password;
		}

		$this->load->helper('email');
		$this->load->library('upload');

		if (empty($email)) {
				$this->template->error(lang("error_18"));
		}

		if (!valid_email($email)) {
			$this->template->error(lang("error_19"));
		}

		if ($email != $member->email) {
			if (!$this->register_model->checkEmailIsFree($email)) {
				 $this->template->error(lang("error_20"));
			}
		}

		if($user_role != $member->user_role) {
			if(!$this->user->info->admin) {
				$this->template->error(lang("error_91"));
			}
		}
		if($user_role > 0) {
			$role = $this->admin_model->get_user_role($user_role);
			if($role->num_rows() == 0) $this->template->error(lang("error_90"));
		}

		if ($_FILES['userfile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "gif|jpg|png|jpeg|",
			       "max_size" => 1000,
			       "xss_clean" => TRUE,
			       "max_width" => 80,
			       "max_height" => 80
			    ));

			    if (!$this->upload->do_upload()) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    }

			    $data = $this->upload->data();

			    $image = $data['file_name'];
			} else {
				$image= $member->avatar;
			}

		$this->user_model->update_user($id, 
			array(
				"username" => $username,
				"email" => $email,
				"first_name" => $first_name,
				"last_name" => $last_name,
				"password" => $pass,
				"user_level" => $user_level,
				"avatar" => $image,
				"aboutme" => $aboutme,
				"points" => $points,
				"active" => $active,
				"user_role" => $user_role
				)
		);
		$this->session->set_flashdata("globalmsg", lang("success_10"));
		redirect(site_url("admin/members"));
	}

	public function add_member_pro() 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->load->model("register_model");
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

		$user_role = intval($this->input->post("user_role"));


		if (strlen($username) < 3) $this->template->error(lang("error_14"));

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
			$this->template->error(lang("error_15"));
		}

		if (!$this->register_model->check_username_is_free($username)) {
			 $this->template->error(lang("error_16"));
		}

		if ($pass != $pass2) $this->template->error(lang("error_22"));

		if (strlen($pass) <= 5) {
			 $this->template->error(lang("error_17"));
		}

		$this->load->helper('email');

		if (empty($email)) {
				$this->template->error(lang("error_18"));
		}

		if (!valid_email($email)) {
			$this->template->error(lang("error_19"));
		}

		if (!$this->register_model->checkEmailIsFree($email)) {
			 $this->template->error(lang("error_20"));
		}

		if($user_role > 0) {
			$role = $this->admin_model->get_user_role($user_role);
			if($role->num_rows() == 0) $this->template->error(lang("error_90"));
			$role = $role->row();
			if($role->voter || $role->admin || $role->admin_members || $role->admin_settings 
				|| $role->admin_payment) {
				if(!$this->user->info->admin) {
					$this->template->error(lang("error_92"));
				}
			}
		}

		$pass = $this->common->encrypt($pass);
		$this->register_model->add_user(array(
			"username" => $username,
			"email" => $email,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"password" => $pass,
			"user_role" => $user_role,
			"IP" => $_SERVER['SERVER_NAME'],
			"joined" => time(),
			"joined_date" => date("n-Y"),
			"active" => 1
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_11"));
		redirect(site_url("admin/members"));
	
	}

	public function delete_member($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$this->user_model->delete_user($id);
		$this->session->set_flashdata("globalmsg", lang("success_12"));
		redirect(site_url("admin/members"));
	}

	public function social_settings() 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_settings"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("social_settings" => 1)));
		$this->template->loadContent("admin/social_settings.php", array(
			)
		);
	}

	public function social_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_settings"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$disable_social_login = 
			intval($this->input->post("disable_social_login"));
		$twitter_consumer_key = 
			$this->common->nohtml($this->input->post("twitter_consumer_key"));
		$twitter_consumer_secret = 
			$this->common->nohtml($this->input->post("twitter_consumer_secret"));
		$facebook_app_id = 
			$this->common->nohtml($this->input->post("facebook_app_id"));
		$facebook_app_secret = 
			$this->common->nohtml($this->input->post("facebook_app_secret"));
		$google_client_id = 
			$this->common->nohtml($this->input->post("google_client_id"));
		$google_client_secret = 
			$this->common->nohtml($this->input->post("google_client_secret"));

		$this->admin_model->updateSettings(
			array(
				"disable_social_login" => $disable_social_login,
				"twitter_consumer_key" => $twitter_consumer_key,
				"twitter_consumer_secret" => $twitter_consumer_secret,
				"facebook_app_id" => $facebook_app_id, 
				"facebook_app_secret"=> $facebook_app_secret,  
				"google_client_id" => $google_client_id,
				"google_client_secret" => $google_client_secret,
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/social_settings"));
	}

	public function settings() 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_settings"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("settings" => 1)));

		$layouts = $this->admin_model->get_layouts();

		$user_roles = $this->admin_model->get_user_roles();

		$this->template->loadContent("admin/settings.php", array(
			"layouts" => $layouts,
			"roles" => $user_roles
			)
		);
	}

	public function settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", 
			"admin_settings"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$site_name = $this->common->nohtml($this->input->post("site_name"));
		$site_desc = $this->common->nohtml($this->input->post("site_desc"));
		$site_email = $this->common->nohtml($this->input->post("site_email"));
		$upload_path = $this->common->nohtml($this->input->post("upload_path"));
		$file_types = $this->common
			->nohtml($this->input->post("file_types"));
		$file_size = intval($this->input->post("file_size"));
		$upload_path_rel = 
			$this->common->nohtml($this->input->post("upload_path_relative"));
		$register = intval($this->input->post("register"));
		$avatar_upload = intval($this->input->post("avatar_upload"));
		$disable_captcha = intval($this->input->post("disable_captcha"));
		$date_format = $this->common->nohtml($this->input->post("date_format"));

		$login_protect = intval($this->input->post("login_protect"));
		$activate_account = intval($this->input->post("activate_account"));

		$default_user_role = intval($this->input->post("default_user_role"));

		$logo_option = intval($this->input->post("logo_option"));

		$google_recaptcha = intval($this->input->post("google_recaptcha"));
		$google_recaptcha_secret = $this->common->nohtml($this->input->post("google_recaptcha_secret"));
		$google_recaptcha_key = $this->common->nohtml($this->input->post("google_recaptcha_key"));

		$layoutid = intval($this->input->post("layoutid"));
		$layout = $this->admin_model->get_layout($layoutid);
		if($layout->num_rows() == 0) {
			$this->template->error("Invalid Layout");
		}
		$layout = $layout->row();

		// Validate
		if (empty($site_name) || empty($site_email)) {
			$this->template->error(lang("error_23"));
		}
		$this->load->library("upload");

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => $this->settings->info->file_types,
		       "max_size" => 2000,
		       "xss_clean" => TRUE
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21") 
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= $this->settings->info->site_logo;
		}

		$this->admin_model->updateSettings(
			array(
				"site_name" => $site_name,
				"site_desc" => $site_desc,
				"upload_path" => $upload_path,
				"upload_path_relative" => $upload_path_rel, 
				"site_logo"=> $image,  
				"site_email" => $site_email,
				"register" => $register,
				"avatar_upload" => $avatar_upload,
				"file_types" => $file_types,
				"disable_captcha" => $disable_captcha,
				"date_format" => $date_format,
				"file_size" => $file_size,
				"login_protect" => $login_protect,
				"activate_account" => $activate_account,
				"logo_option" => $logo_option,
				"layout" => $layout->layout_path,
				"default_user_role" => $default_user_role,
				"google_recaptcha" => $google_recaptcha,
				"google_recaptcha_secret" => $google_recaptcha_secret,
				"google_recaptcha_key" => $google_recaptcha_key,
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/settings"));
	}

}

?>