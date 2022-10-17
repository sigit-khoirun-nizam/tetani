<?php

class MY_Loader extends CI_Loader {

	public $ci;

	public function __construct()
	{
		parent::__construct();

		$this->ci =& get_instance();
	}

	public function renderView($view, $vars = array(), $return = FALSE)
	{
		$authlib = $this->ci->authlib;

		if($authlib->hasUserdata())
		{
			$admin = $this->ci->db->get_where('admin', ['uniq' => $authlib->userdata('uniq')])->row_array();

			foreach($admin as $key => $value)
			{
				$vars[$key] = $value;
			}
		}

		$this->view($view, $vars, $return);
	}
}