<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function _required_span($params){
    if(isset($params["required"]) && $params["required"] == "true")
    return '<span class="text-danger">*</span>';
    else
    return '';
}
if ( ! function_exists('_input_text'))
{
    function _input_text($parms)
    {
        $field = '<div class="form-group">';

        if(key_exists("title",$parms))
            $field .='<label class="">'.$this->lang->line("Title").' : '._required_span($parms).'</label>';
    }   
}
