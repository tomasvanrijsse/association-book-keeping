<?php

/** Made by Tomas @ Pionect **/

if ( ! function_exists('nulstreepje')){
    function nulstreepje($var){
        if(substr($var, -3) == '.00' || substr($var, -3) == ',00'){
            return substr($var,0,-3).',-';
        } else {
            return $var;
        }
    }
}

if ( ! function_exists('metdecimals')){
    function metdecimals($var){
        return number_format($var,2,',','.');
    }
}

if( ! function_exists('prijsify')){
    function prijsify($double){
        return '&euro; '.nulstreepje(metdecimals($double));
    }
    
}