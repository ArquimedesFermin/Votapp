<?php

class Polls_Model extends CI_Model 
{

	public function create_poll($data)
	{
		$this->db->insert("user_polls", $data);
		return $this->db->insert_id();
	}

	public function get_poll($id){
		return $this->db
			->select("user_polls.ID, user_polls.userid, user_polls.name, 
				user_polls.question, user_polls.timestamp, 
				user_polls.show_results, user_polls.ip_restricted,
				user_polls.cookie_restricted, user_polls.public,
				user_polls.status, user_polls.votes, user_polls.created, 
				user_polls.updated, user_polls.user_restricted,
				user_polls.hash, user_polls.vote_type, user_polls.votes_limit, user_polls.votes_today,
				user_polls.votes_today_timestamp, user_polls.votes_month,
				user_polls.votes_month_timestamp, user_polls.themeid, user_polls.ex_a, user_polls.ex_r, user_polls.groupid,
				poll_themes.name as themename, poll_themes.css_code, user_polls.start,
				users.premium_time, users.premium_planid")
			->where("user_polls.ID", $id)
			->join("poll_themes", "poll_themes.ID = user_polls.themeid", "left outer")
			->join("users", "users.ID = user_polls.userid", "left outer")
			->get("user_polls");
	}

	public function get_user_poll($id, $userid) 
	{
		return $this->db
			->select("user_polls.ID, user_polls.userid, user_polls.name, 
				user_polls.question, user_polls.timestamp, 
				user_polls.show_results, user_polls.ip_restricted,
				user_polls.cookie_restricted, user_polls.public,
				user_polls.status, user_polls.votes, user_polls.created, 
				user_polls.updated, user_polls.user_restricted,
				user_polls.hash, user_polls.vote_type, user_polls.votes_limit, user_polls.votes_today,
				user_polls.votes_today_timestamp, user_polls.votes_month,
				user_polls.votes_month_timestamp, user_polls.themeid, user_polls.start, user_polls.ex_a, user_polls.ex_r, user_polls.groupid,
				poll_themes.name as themename, poll_themes.css_code")
			->where("user_polls.ID", $id)
			->where("user_polls.userid", $userid)
			->join("poll_themes", "poll_themes.ID = user_polls.themeid", "left outer")
			->join("users", "users.ID = user_polls.userid", "left outer")
			->get("user_polls");
	}

	public function get_user_polls($userid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"user_polls.name"
			)
		);

		return $this->db
			->where("user_polls.userid", $userid)
			->where("status !=", 2)
			->limit($datatable->length, $datatable->start)
			->get("user_polls");
	}

	public function get_user_polls_all($userid){

		return $this->db
			->where("user_polls.userid", $userid)
			->get("user_polls");
	}
	
	public function get_user_polls_inactive($userid){

		return $this->db
			->where("user_polls.userid", $userid)
			->where("status =", 0)
			->get("user_polls");
	}
	
	public function get_user_polls_active($userid){

		return $this->db
			->where("user_polls.userid", $userid)
			->where("status =", 1)
			->get("user_polls");
	}	
	
	public function get_user_votes($userid){

		return $this->db
			->where("userid", $userid)
			->group_by("pollid")
			->get("user_poll_votes");
	}

	
	public function get_user_polls_group2($userid, $datatable) 
	{
		//$polls_group = $this->admin_model->get_group_polls($groupid);
		
		$datatable->db_order();
		$datatable->db_search(array(
			"user_group_users.ID"
			)
		);

		return $this->db
			->select("user_group_users.groupid, user_groups.name as group_name,
			users_polls.ID as poll_id, users_polls.public, users_polls.hash, 
			poll_name as users_polls.name, users_polls.created, users_polls.ex_a, users_polls.ex_r,
			users_polls.timestamp, users_polls.status")
			->where("user_group_users.userid", $userid->ID)
			->join("users_groups", "users_groups.ID = user_group_users.groupid", "left outer")
			->join("users_polls", "users_polls.groupid = user_group_users.groupid", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("user_group_users");		
	}
	
	public function get_user_polls_group($userid, $datatable) {
		$datatable->db_order();
		$datatable->db_search(array(
			"users_polls.name"
			)
		);
$userid = intval($userid);
		return $this->db
		->query('SELECT
    user_groups.ID AS GroupID,
    user_groups.name AS GroupName,
    user_polls.ID,
    user_polls.name,
    user_polls.hash,
    user_polls.timestamp,
    user_polls.created,
    user_polls.votes,
    user_polls.status,
    user_polls.public,
	user_polls.ex_a,
    user_polls.ex_r,
	user_polls.created
FROM
    user_group_users
INNER JOIN user_groups ON user_groups.ID = user_group_users.groupid
INNER JOIN user_polls ON user_polls.groupid = user_group_users.groupid
WHERE
    user_group_users.userid = '.$userid.'
GROUP BY 
    user_polls.name
Order BY
	user_polls.ID');
	}
	
	public function get_user_polls_groups($userid) 
	{
		$userid = intval($userid);
		return $this->db
		->query('SELECT
					user_groups.ID AS GroupID,
					user_groups.name AS GroupName,
					user_polls.ID,
					user_polls.name,
					user_polls.hash,
					user_polls.timestamp,
					user_polls.created,
					user_polls.votes,
					user_polls.status,
					user_polls.public,
					user_polls.ex_a,
					user_polls.ex_r
					
				FROM
					user_group_users
				INNER JOIN user_groups ON user_groups.ID = user_group_users.groupid
				INNER JOIN user_polls ON user_polls.groupid = user_group_users.groupid
				WHERE
					user_group_users.userid = '.$userid.'
				GROUP BY 
					user_polls.name
				Order BY
					user_polls.ID');
	}
	
	public function get_user_active_polls_groups($userid) {
		$userid = intval($userid);
		return $this->db
		->query('SELECT
					user_groups.ID AS GroupID,
					user_groups.name AS GroupName,
					user_polls.ID,
					user_polls.name,
					user_polls.hash,
					user_polls.timestamp,
					user_polls.created,
					user_polls.votes,
					user_polls.status,
					user_polls.public,
					user_polls.ex_a,
					user_polls.ex_r
					
				FROM
					user_group_users
				INNER JOIN user_groups ON user_groups.ID = user_group_users.groupid
				INNER JOIN user_polls ON user_polls.groupid = user_group_users.groupid
				WHERE
					user_group_users.userid = '.$userid.'
				AND
					user_polls.status = 1
				GROUP BY 
					user_polls.name
				Order BY
					user_polls.ID');
	}
	
		public function get_user_inactive_polls_groups($userid) {
			
		$userid = intval($userid);
		return $this->db
		->query('SELECT
					user_groups.ID AS GroupID,
					user_groups.name AS GroupName,
					user_polls.ID,
					user_polls.name,
					user_polls.hash,
					user_polls.timestamp,
					user_polls.created,
					user_polls.votes,
					user_polls.status,
					user_polls.public,
					user_polls.ex_a,
					user_polls.ex_r
					
					FROM
						user_group_users
					INNER JOIN user_groups ON user_groups.ID = user_group_users.groupid
					INNER JOIN user_polls ON user_polls.groupid = user_group_users.groupid
					WHERE
						user_group_users.userid = '.$userid.'
					AND
						user_polls.status = 0
					GROUP BY 
						user_polls.name
					Order BY
						user_polls.ID');
	}
	
	public function get_all_user_polls($userid){

		return $this->db
			->where("user_group_users.userid",1)
			->select("user_groups.ID AS GroupID,
					user_groups.name AS GroupName,
					user_polls.ID,
					user_polls.name,
					user_polls.hash,
					user_polls.timestamp,
					user_polls.created,
					user_polls.votes,
					user_polls.status,
					user_polls.public")			
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->join("user_polls", "user_polls.groupid = user_group_users.groupid")
			->group_by("user_polls.name")
			->order_by("user_polls.ID")
			->limit(0, 10)
			->get("user_group_users");
	}
	
	public function get_user_groups($userid) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"user_polls.name"
			)
		);

		return $this->db
			->where("user_polls.userid", $userid)
			->where("status !=", 2)
			->limit($datatable->length, $datatable->start)
			->get("user_polls");
	}

	public function get_all_polls($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"user_polls.name"
			)
		);

		return $this->db
			->where("status !=", 2)
			->where("public", 0)
			->limit($datatable->length, $datatable->start)
			->get("user_polls");
	}
	
	public function get_polls_public(){
		return $this->db
			->where("status !=", 2)
			->where("public", 0)
			->get("user_polls");
	}

	public function get_user_polls_archived($userid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"user_polls.name"
			)
		);

		return $this->db
			->where("user_polls.userid", $userid)
			->where("status", 2)
			->limit($datatable->length, $datatable->start)
			->get("user_polls");
	}


	public function get_total_user_polls($userid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("userid", $userid)
			->get("user_polls");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function get_total_user_groups($userid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("userid", $userid)
			->get("user_polls_users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}


	public function get_total_polls() 
	{
		$s = $this->db
			->where("public", 0)
			->select("COUNT(*) as num")
			->get("user_polls");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_total_poll_votes($userid) 
	{
		$s = $this->db
			->select("SUM(votes) as num")
			->where("userid", $userid)
			->get("user_polls");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_total_poll_votes_today($userid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("userid", $userid)
			->where("timestamp >", time() - (24*3600))
			->get("user_poll_votes");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_total_user_polls_archived($userid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("userid", $userid)
			->where("status", 2)
			->get("user_polls");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function get_answer_vote($userid, $pollid, $answerid) 
	{
		return $this->db
			->select("mods")
			->where("userid", $userid)
			->where("pollid", $pollid)
			->where("answerid", $answerid)
			->get("user_poll_votes");
	}

	public function update_poll($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_polls", $data);
	}
	
	public function update_poll_vote($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_poll_votes", $data);
	}

	public function get_poll_answers($pollid) 
	{
		return $this->db->where("pollid", $pollid)->get("user_poll_answers");
	}

	public function add_answer($pollid, $answer) 
	{
		$this->db->insert("user_poll_answers", array(
			"pollid" => $pollid, 
			"answer" => $answer
			)
		);
		return $this->db->insert_id();
	}
	
	public function add_answer_mod($userid, $pollid, $answerid=0) {
		$this->db->insert("user_answer_mods", array(
			"userid" => $userid, 
			"pollid" => $pollid, 
			"answerid" => $answerid,
			"timestamp" => time()
			)
		);
		return $this->db->insert_id();
	}
	
	
	public function get_answer_mod($userid, $pollid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("userid", $userid)			
			->where("pollid", $pollid)
			
			->get("user_answer_mods");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function get_answer_id($userid, $pollid) 
	{
		return $this->db
			->select("ID")
			->where("userid", $userid)			
			->where("pollid", $pollid)	
			->get("user_poll_votes");

	}
	
	public function get_poll_answer($pollid, $answerid) 
	{
		return $this->db
			->where("ID", $answerid)
			->where("pollid", $pollid)
			->get("user_poll_answers");
	}

	public function update_answer($answerid, $data) {
		$this->db->where("ID", $answerid)->update("user_poll_answers", $data);
	}

	public function change_vote($answerid, $old_answer, $new_answer){
		$data1 = array("answerid" => $new_answer);
		$this->db->where("ID", $answerid)->update("user_poll_votes", $data1);
		
		$this->db->where('ID', $old_answer)->set('votes', 'votes-1', FALSE)->update('user_poll_answers');
		$this->db->where('ID', $new_answer)->set('votes', 'votes+1', FALSE)->update('user_poll_answers');
	}	
	
	public function change_vote3($pollid, $userid, $votes){

		$this->db
			->where("pollid",$pollid)
			->where("userid",$userid)
			->where("answerid", $votes['answerid'])
			->update("user_poll_votes", array(
				"ex_a"=> $votes['ex_a'],
				"ex_r"=> $votes['ex_r']));
		
		$v = $this->db
		->query("SELECT user_poll_votes.answerid as AnswerID,
		user_poll_answers.answer as Answer, 
		Sum(user_poll_votes.ex_a) AS A, 
		Sum(user_poll_votes.ex_r) AS R 
		FROM user_poll_votes 
		INNER JOIN user_poll_answers ON user_poll_answers.ID = AnswerID 
		WHERE user_poll_votes.pollid = ".$pollid." GROUP BY user_poll_answers.answer");
		
		foreach($v->result() as $answer){
		$this->db
		->where("ID",$answer->AnswerID)
		->update("user_poll_answers", array(
			"ex_a"=>$answer->A,
			"ex_r"=>$answer->R));
		
		}
	}
	
	public function delete_answer($id) 
	{
		$this->db->where("ID", $id)->delete("user_poll_answers");
	}	
	
	public function remove_answer($pollid, $answerid) 
	{
		$this->db->
		where("pollid", $pollid)->
		where("answerid", $answerid)->
		delete("user_poll_votes");
	}

	public function check_user_vote($pollid) 
	{
		return $this->db
			->where("pollid", $pollid)
			->where("IP", $_SERVER['SERVER_NAME'])
			->get("user_poll_votes");
	}

	public function get_user_group($id) 
	{
		return $this->db->where("ID", $id)->get("user_groups");
	}
	
	public function get_user_group_name($id) 
	{
		return $this->db->select("name")->where("ID", $id)->get("user_groups");
	}
	
	public function get_poll_vote($pollid, $userid) 
	{
		return $this->db->where("pollid", $pollid)
			->where("userid", $userid)
			->get("user_poll_votes");
	}
	
	public function get_poll_vote_count($pollid, $userid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("pollid", $pollid)
			->where("userid", $userid)
			->get("user_poll_votes");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function add_vote($data) 
	{
		$this->db->insert("user_poll_votes", $data);
	}

	public function get_recent_votes($pollid) 
	{
		return $this->db->where("user_poll_votes.pollid", $pollid)
			->select("user_poll_votes.IP, user_poll_votes.user_agent, 
				user_poll_votes.timestamp, user_poll_votes.ID, 
				user_poll_answers.answer, users.ID as UserID, users.username as UserName, 
				users.first_name as FirstName, users.last_name as LastName, 
				user_poll_votes.ex_r as R, user_poll_votes.ex_a as A, user_poll_votes.mods")				
			->join("user_poll_answers", "user_poll_answers.ID = user_poll_votes.answerid")
			->join("users", "users.ID = user_poll_votes.userid")
			->order_by("user_poll_votes.ID", "DESC")
			->get("user_poll_votes");
	}
	
	public function get_recent_votes2($pollid, $page=0) 
	{
		
		return $this->db->where("user_poll_votes.pollid", $pollid)
			->select("user_poll_votes.IP, user_poll_votes.user_agent, 
				user_poll_votes.timestamp, user_poll_votes.ID, 
				user_poll_answers.answer, users.ID as UserID, users.username as UserName, 
				users.first_name as FirstName, users.last_name as LastName, 
				sum(user_poll_votes.ex_r) as R, sum(user_poll_votes.ex_a) as A, user_poll_votes.mods")				
			->join("user_poll_answers", "user_poll_answers.ID = user_poll_votes.answerid")
			->join("users", "users.ID = user_poll_votes.userid")
			->order_by("user_poll_votes.ID", "ASC")
			->group_by("users.ID")->group_by("user_poll_votes.answerid")
			->get("user_poll_votes");
	}

	public function get_total_voters($pollid) 
	{
		
		$s = $this->db
		->select("userid")
		->where("pollid", $pollid)
		->group_by("userid")
		->get("user_poll_votes");
				
		return $s->num_rows();
	}
	
	public function count_votes_date($date, $pollid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("date_stamp", $date)
			->where("pollid", $pollid)
			->get("user_poll_votes");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function count_poll_answers($pollid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("pollid", $pollid)
			->get("user_poll_answers");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function count_poll_voters($pollid) 
	{
		return $this->db
			->select("userid")
			->where("pollid", $pollid)
			->group_by("userid")
			->get("user_poll_votes");
	}
	
	public function get_votes($pollid, $page) 
	{
		return $this->db->where("user_poll_votes.pollid", $pollid)
			->select("user_poll_votes.timestamp, user_poll_votes.ID, 
				user_poll_answers.answer, users.ID as UserID, users.username as UserName, users.first_name as FirstName, users.last_name as LastName")				
			->join("user_poll_answers", "user_poll_answers.ID = 
				user_poll_votes.answerid")
			->join("users", "users.ID = 
				user_poll_votes.userid")
			->limit($page, 20)
			->order_by("user_poll_votes.ID", "DESC")
			->get("user_poll_votes");
	}

	public function get_total_votes_count($pollid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("pollid", $pollid)
			->get("user_poll_votes");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function update_all_poll_answers($id, $data) 
	{
		$this->db->where("pollid", $id)->update("user_poll_answers", $data);
	}

	public function delete_poll_votes($pollid) 
	{
		$this->db->where("pollid", $pollid)->delete("user_poll_votes");
	}


	public function get_poll_themes() 
	{
		return $this->db->get("poll_themes");
	}
	
	public function get_groups_creator($id) 
	{
		return $this->db->where("ID", $id)->get("user_polls");
	}

	public function get_poll_theme($id) 
	{
		return $this->db->where("ID", $id)->get("poll_themes");
	}

	public function add_poll_theme($data) 
	{
		$this->db->insert("poll_themes", $data);
	}

	public function delete_theme($id) 
	{
		$this->db->where("ID", $id)->delete("poll_themes");
	}

	public function update_poll_theme($id, $data) 
	{
		$this->db->where("ID", $id)->update("poll_themes", $data);
	}

	public function delete_poll($id) 
	{
		$this->db->where("ID", $id)->delete("user_polls");
	}

	public function count_user_votes_date($date, $userid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("date_stamp", $date)
			->where("userid", $userid)
			->get("user_poll_votes");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_recent_polls($userid) 
	{
		$s =  $this->db
		->select("pollid")
		->where("userid", $userid)
		->group_by("pollid")
		->get("user_poll_votes");
		
		$r = $s->num_rows();
		if(isset($r)) return $r;
		return 0;
	}
	
	public function get_top_answers_votes($pollid){
		
			return $this->db
				->select("*")
				->where("pollid",$pollid)
				->limit(1)
				->order_by("votes", "DESC")
				->get("user_poll_answers");
	}
	
	
}
?>