<?php 

function has_error($field)
{
	$ci =& get_instance();
	$error = $ci->form_validation->error($field);
	
	if($error) return 'has-error';
	
	else return '';
}

function help_block($field)
{
	return form_error($field, '<span class="help-block">', '</span>');
}

function file_hasError()
{
	$ci =& get_instance();

	if($ci->upload->display_errors()) return 'has-error';

	else return '';
}

function file_helpBlock()
{
	$ci =& get_instance();

	if($ci->upload->display_errors())
		return '<span class="help-block">' . $ci->upload->display_errors() . '</span>';

	else return '';
}

function validationErrors() 
{
	if(validation_errors()) 
        return 
    		'<div class="alert alert-danger alert-dismissible">
    			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    			<ul>'.validation_errors('<li>', '</li>').'</ul>
    		</div>';

    else return '';
}