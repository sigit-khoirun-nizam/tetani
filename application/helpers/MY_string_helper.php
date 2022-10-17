<?php

function get_uniq($table, $len = 10)
{
	$ci =& get_instance();
	$res = '';

	for(;;)
	{
		$uniq = random_string('alnum', $len);
		if($ci->db->get_where($table, ['uniq' => $uniq])->num_rows() == 0)
		{
			$res = $uniq;

			break;
		}
	}

	return $res;
}

function json_output(array $data)
{
	$ci =& get_instance();

	$ci->output
		->set_content_type('json')
		->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function rupiah($num) 
{
	return number_format($num, NULL, NULL, ".");
}