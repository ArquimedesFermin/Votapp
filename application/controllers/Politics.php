<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Politics extends CI_Controller {

	public function index()
	{
		$this->template->loadData("activeLink", 
			array("politics" => array("active" => 1)));

		$page = "index";

		$this->template->loadContent("pages/politics.php", array(
			"page" => $page
			)
		);
	}
}
?>