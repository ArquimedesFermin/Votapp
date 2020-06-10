<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rules extends CI_Controller {

	public function index()
	{
		$this->template->loadData("activeLink", 
			array("rules" => array("active" => 1)));

		$page = "index";

		$this->template->loadContent("pages/rules.php", array(
			"page" => $page
			)
		);
	}
}
?>