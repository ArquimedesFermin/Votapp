<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->template->loadData("activeLink", 
			array("home" => array("general" => 1)));
		$this->load->model("user_model");
		$this->load->model("home_model");
		$this->load->model("polls_model");
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
	}

	public function index()
	{
		$this->load->model("funds_model");
		$last_dates = array();

		for ($i=6; $i>-1; $i--) {
			$date = date("Y-m-d", strtotime($i." days ago"));
			$vote = array(
				"date" => $date,
				"votes" => $this->polls_model
					->count_user_votes_date($date, $this->user->info->ID)
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
			.base_url().'scripts/custom/home_results.js" /></script>');

		$polls = $this->polls_model->get_user_polls_groups($this->user->info->ID);
			
		$polls_all = $this->polls_model->get_user_polls_groups($this->user->info->ID);
		$polls_admin_all = $this->polls_model->get_user_polls_all($this->user->info->ID);
		
		$polls_active = $this->polls_model->get_user_active_polls_groups($this->user->info->ID);
		$polls_admin_active = $this->polls_model->get_user_polls_active($this->user->info->ID);
		
		$polls_inactive = $this->polls_model->get_user_inactive_polls_groups($this->user->info->ID);
		$polls_admin_inactive = $this->polls_model->get_user_polls_inactive($this->user->info->ID);
		
		$user_votes = $this->polls_model->get_user_votes($this->user->info->ID);
		$polls_assoc =  $this->polls_model->get_user_polls_groups($this->user->info->ID);
		$polls_public =  $this->polls_model->get_polls_public();
		
		$stats = $this->home_model->get_user_stats($this->user->info->ID);
		
		if($stats->num_rows() == 0) {
			$stats = $this->get_fresh_results($stats);
			$this->home_model->add_user_stats($this->user->info->ID, $stats);
		} else {
			$stats = $stats->row();
			if($stats->timestamp < time() - 3600) {
				$stats = $this->get_fresh_results($stats);
				// Update Row
				$this->home_model
					->update_user_stats($this->user->info->ID, $stats);
			}
		}
		

		$time = $this->common->convert_time($this->user->info->premium_time);
		unset($time['secs']);

		$plan = $this->funds_model->get_plan($this->user->info->premium_planid);

		$this->template->loadContent("home/index.php", array(
			"polls" => $polls,
			"stats" => $stats,
			"time" => $time,
			"plan" => $plan,
			"polls_all"=> $polls_all,
			"polls_active"=> $polls_active,
			"polls_inactive"=> $polls_inactive,			
			"polls_admin_all"=> $polls_admin_all,
			"polls_admin_active"=> $polls_admin_active,
			"polls_admin_inactive"=> $polls_admin_inactive,
			"polls_public" => $polls_public,
			"polls_assoc" => $polls_assoc,
			"user_votes"=> $user_votes
			)
		);
	}

	private function get_fresh_results($stats) 
	{
		$data = new STDclass;

		$data->polls = $this->polls_model
			->get_total_user_polls($this->user->info->ID);
		$data->poll_votes = $this->polls_model
			->get_total_poll_votes($this->user->info->ID);
		$data->poll_votes_today = $this->polls_model
			->get_total_poll_votes_today($this->user->info->ID);

		return $data;
	}

	public function change_language() 
	{	
		$languages = $this->config->item("available_languages");
		if(!isset($_COOKIE['language'])) {
			$lang = "";
		} else {
			$lang = $_COOKIE["language"];
		}
		$this->template->loadContent("home/change_language.php", array(
			"languages" => $languages,
			"user_lang" => $lang
			)
		);
	}

	public function change_language_pro() 
	{
		$lang = $this->common->nohtml($this->input->post("language"));
		$languages = $this->config->item("available_languages");
		if(!in_array($lang, $languages, TRUE)) {
			$this->template->error(lang("error_25"));
		}

		setcookie("language", $lang, time()+3600*7, "/");
		$this->session->set_flashdata("globalmsg", lang("success_14"));
		redirect(site_url());
	}

}

?>