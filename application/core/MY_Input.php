<?php 

class MY_Input extends CI_Input {

	public $inputData;

	public function __construct()
	{
		parent::__construct();

		if($this->method() == 'post')
			$this->inputData = $this->post();

		elseif($this->method() == 'get')
			$this->inputData = $this->get();
	}

	public function __get($name)
	{
		return $this->inputData[$name];
	}

	public function ifEmptyForbidden()
	{
		if(empty($this->post())) 

			show_error("You don't have permission to access / on this server", 403, "403 Forbidden");
	}
}
