<?php

class Admin_Model extends CI_Model 
{

	public function updateSettings($data) 
	{
		$this->db->where("ID", 1)->update("site_settings", $data);
	}

	public function add_ipblock($ip, $reason) 
	{
		$this->db->insert("ip_block", array(
			"IP" => $ip,
			"reason" => $reason,
			"timestamp" => time()
			)
		);
	}

	public function get_ip_blocks() 
	{
		return $this->db->get("ip_block");
	}

	public function get_ip_block($id) 
	{
		return $this->db->where("ID", $id)->get("ip_block");
	}

	public function delete_ipblock($id) {
		$this->db->where("ID", $id)->delete("ip_block");
	}

	public function get_email_templates() 
	{
		return $this->db->get("email_templates");
	}

	public function get_email_template($id) 
	{
		return $this->db->where("ID", $id)->get("email_templates");
	}

	public function update_email_template($id, $title, $message) 
	{
		$this->db->where("ID", $id)->update("email_templates", array(
			"title" => $title,
			"message" => $message
			)
		);
	}
	
	public function get_user_groups() 
	{
		return $this->db->get("user_groups");
	}

	public function get_user_groups_creator($userid) 
	{
		return $this->db->where("userid",$userid)->get("user_groups");
	}
	
	
	
	public function add_group($name, $default, $userid) 
	{
		$this->db->insert("user_groups", array(
			"name" => $name, 
			"default" => $default,
			"userid" => $userid
			)
		);
	}
	
	public function get_group_polls($id) 
	{
		return $this->db->where("user_groups.ID", $id)
			->select("user_polls.ID as pollid, user_polls.groupid")
			->join("user_groups", "user_groups.ID = user_polls.groupid")
			->limit(20, $page)
			->get("user_groups");	
	}
	
	public function get_user_group($id) 
	{
		return $this->db->where("ID", $id)->get("user_groups");
	}
	
	public function get_user_group_name($id) 
	{
		return $this->db->select("name")->where("ID", $id)->get("user_groups");
	}
	

	
	public function delete_group($id) {
		$this->db->where("ID", $id)->delete("user_groups");
	}

	public function delete_users_from_group($id) 
	{
		$this->db->where("groupid", $id)->delete("user_group_users");
	}

	public function update_group($id, $name, $default) 
	{
		$this->db->where("ID", $id)->update("user_groups", array(
			"name" => $name,
			"default" => $default
			)
		);
	}

	public function get_users_from_groups($id, $page) 
	{
		return $this->db->where("user_group_users.groupid", $id)
			->select("users.ID as userid, users.first_name, users.last_name, users.username, users.email, user_groups.name, 
				user_groups.ID as groupid, user_groups.default")
			->join("users", "users.ID = user_group_users.userid")
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->limit(20, $page)
			->get("user_group_users");
	}
			
		public function get_last_poll() 
	{
		return $this->db
			->select("MAX(user_polls.ID)")
			->get("user_polls");
	}
	
	public function get_all_group_users($id) 
	{
		return $this->db->where("user_group_users.groupid", $id)
			->select("users.ID as userid, users.email, users.username, 
				user_groups.name, user_groups.ID as groupid, 
				user_groups.default")
			->join("users", "users.ID = user_group_users.userid")
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->get("user_group_users");
	}

	public function get_total_user_group_members_count($groupid) 
	{
		$s= $this->db
		->where("groupid", $groupid)
		->select("COUNT(*) as num")
		->get("user_group_users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
		public function get_total_user_count() 
	{
		$s= $this->db->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_from_group($userid, $id) 
	{
		return $this->db->where("userid", $userid)
			->where("groupid", $id)->get("user_group_users");
	}

	public function delete_user_from_group($userid, $id) 
	{
		$this->db->where("userid", $userid)
			->where("groupid", $id)->delete("user_group_users");
	}

	public function add_user_to_group($userid, $id) 
	{
		$this->db->insert("user_group_users", 
			array(
			"userid" => $userid, 
			"groupid" => $id
			)
		);
	}

	public function get_all_users() 
	{
		return $this->db
			->get("users");
	}

	public function add_payment_plan($data) 
	{
		$this->db->insert("payment_plans", $data);
	}

	public function get_payment_plans() 
	{
		return $this->db->get("payment_plans");
	}

	public function get_payment_plan($id) 
	{
		return $this->db->where("ID", $id)->get("payment_plans");
	}

	public function delete_payment_plan($id) 
	{
		$this->db->where("ID", $id)->delete("payment_plans");
	}

	public function update_payment_plan($id, $data)
	{
		$this->db->where("ID", $id)->update("payment_plans", $data);
	}

	public function get_payment_logs($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"payment_logs.email"
			)
		);
		return $this->db->select("users.ID as userid, users.username, users.email,
			users.avatar, users.online_timestamp,
			payment_logs.email, payment_logs.amount, payment_logs.timestamp, 
			payment_logs.ID, payment_logs.processor")
			->join("users", "users.ID = payment_logs.userid")
			->limit($datatable->length, $datatable->start)
			->get("payment_logs");
	}

	public function get_total_payment_logs_count() 
	{
		$s= $this->db
			->select("COUNT(*) as num")->get("payment_logs");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_polls($page) 
	{
		return $this->db
			->select("user_polls.name, user_polls.ID, user_polls.hash, 
				users.ID as userid, users.username, user_polls.votes, 
				user_polls.status")
			->join("users", "users.ID = user_polls.userid")
			->limit($page, 20)
			->order_by("user_polls.ID", "DESC")
			->get("user_polls");
	}

	public function get_total_polls_count() 
	{
		$s= $this->db
			->select("COUNT(*) as num")->get("user_polls");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_polls($userid, $page) 
	{
		return $this->db
			->select("user_polls.name, user_polls.ID, user_polls.hash, 
				users.ID as userid, users.username, user_polls.votes, 
				user_polls.status")
			->join("users", "users.ID = user_polls.userid")
			->where("user_polls.userid", $userid)
			->limit($page, 20)
			->order_by("user_polls.ID", "DESC")
			->get("user_polls");
	}

	public function get_total_user_polls_count($userid) 
	{
		$s= $this->db
			->select("COUNT(*) as num")->where("userid", $userid)
			->get("user_polls");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_premium_users($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.first_name",
			"users.last_name",
			"payment_plans.name",
			"users.email"
			)
		);

		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, users.online_timestamp, users.avatar,
			user_roles.name as user_role_name,
			payment_plans.name, users.premium_time")
		->join("payment_plans", "payment_plans.ID = users.premium_planid")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->where("users.premium_time >", time())
		->limit($datatable->length, $datatable->start)
		->get("users");
	}

	public function get_total_premium_users_count() 
	{
		$s= $this->db
			->select("COUNT(*) as num")->where("premium_time >", time())
			->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_layouts() 
	{
		return $this->db->get("site_layouts");
	}

	public function get_layout($id) 
	{
		return $this->db->where("ID", $id)->get("site_layouts");
	}

	public function get_user_roles() 
	{
		return $this->db->get("user_roles");
	}

	public function add_user_role($data) 
	{
		$this->db->insert("user_roles", $data);
	}

	public function get_user_role($id) 
	{
		return $this->db->where("ID", $id)->get("user_roles");
	}

	public function update_user_role($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_roles", $data);
	}

	public function delete_user_role($id) 
	{
		$this->db->where("ID", $id)->delete("user_roles");
	}

	public function get_all_polls($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"user_polls.name"
			)
		);

		return $this->db
			->limit($datatable->length, $datatable->start)
			->get("user_polls");
	}

	public function get_total_polls() 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->get("user_polls");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}


}

?>