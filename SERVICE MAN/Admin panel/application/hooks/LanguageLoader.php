<?php
class LanguageLoader
{
   function initialize() {
       $ci =& get_instance();
       $ci->load->helper('language');
       $siteLang = $ci->session->userdata('site_lang');
       if ($siteLang) {
           $ci->lang->load('common_label',$siteLang);
       } else {
           $ci->lang->load('common_label','english');
       }
   }
}