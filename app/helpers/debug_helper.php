<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! function_exists('dump')){
    function dump($var,$save=true){
        if($save
            || $_SERVER['REMOTE_ADDR'] == '195.240.225.6'   // marijkestr. hdnxvld
            || $_SERVER['REMOTE_ADDR'] == '217.77.159.247'  // CF
            || $_SERVER['REMOTE_ADDR'] == '83.137.149.45'   ) { //BOAZ

            $calledFrom = debug_backtrace();
            echo '<strong>' . substr(str_replace(SELF, '', $calledFrom[0]['file']), 1) . '</strong>';
            echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';

            echo '<pre>';
            print_r($var);
            echo "</pre>";
        }
    }
}

// ValidURL
if ( ! function_exists('isValidURL')){
    function isValidURL($url)
    {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }
}

if ( ! function_exists('selected')){
    function selected($a, $b){
        if($a == $b){
            echo "selected='selected'";
        }
    }
}

if ( ! function_exists('checked')){
    function checked($a, $b){
        if($a == $b){
            echo "checked='checked'";
        }
    }
}

if ( ! function_exists('isactive')){
    function isactive($a){
        $CI =& get_instance();
        $b = $CI->uri->segment(1); 
        if($a == $b){
            echo "class='active'";
        }
    }
}

if( !function_exists('isAdmin')){
    function isAdmin(){
        $CI =& get_instance();
        $bool = ($CI->session->userdata('isAdmin') == 'true');
        return $bool;
    }
}