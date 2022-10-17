<?php

function is_ajax()
{
	$ci =& get_instance();

	return $ci->input->is_ajax_request();
}

function notif() 
{
	$ci =& get_instance();

	return $ci->notif->get();
}