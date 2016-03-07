<?php

function getSetting($setting_id){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->where('id',$setting_id);
    $query = $CI->db->get('setting');
    if ($query->num_rows() > 0)
    {
        $row = $query->row(); 
        return $row->value;
    } else {
        $error =  'The setting_id "'. $setting_id .'" doesn\'t exist<br/>';
        trigger_error($error);
    }
}

function saveSetting($setting_id,$value){
    $CI =& get_instance();
    $CI->db->where('id',$setting_id);
    $data = array('value' => $value);
    $CI->db->update('setting', $data);
}