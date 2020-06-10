<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class About extends CI_Controller {

	public function index()
	{
		$this->template->loadData("activeLink", 
			array("about" => array("active" => 1)));

		$page = "index";

		$this->template->loadContent("pages/about.php", array(
			"page" => $page
			)
		);
	}
}
?>