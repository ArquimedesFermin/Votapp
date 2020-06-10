<?php

class Home_Model extends CI_Model 
{

	public function get_home_stats() 
	{
		return $this->db->get("home_stats");
	}

	public function update_home_stats($stats) 
	{
		$this->db->where("ID", 1)->update("home_stats", array(
			"google_members" => $stats->google_members,
			"facebook_members" => $stats->facebook_members,
			"twitter_members" => $stats->twitter_members,
			"total_members" => $stats->total_members,
			"new_members" => $stats->new_members,
			"active_today" => $stats->active_today,
			"timestamp" => time()
			)
		);
	}

	public function get_user_stats($userid)
	{
		return $this->db->where("userid", $userid)->get("user_stats");
	}

	public function add_user_stats($userid, $stats) 
	{
		$this->db->insert("user_stats", array(
			"userid" => $userid, 
			"polls" => $stats->polls,
			"poll_votes" => $stats->poll_votes,
			"poll_votes_today" => $stats->poll_votes_today
			)
		);
	}

	public function update_user_stats($userid, $stats) 
	{
		$this->db->where("userid", $userid)->update("user_stats", array(
			"polls" => $stats->polls,
			"poll_votes" => $stats->poll_votes,
			"poll_votes_today" => $stats->poll_votes_today,
			"timestamp" => 0
			)
		);
	}

	public function get_email_template($id) 
	{
		return $this->db->where("ID", $id)->get("email_templates");
	}

}

?>