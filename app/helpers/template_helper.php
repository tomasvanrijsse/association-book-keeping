<?php

if(!function_exists('enqueue_script')){
    function enqueue_script($file){
        $CI =& get_instance();
        if(file_exists(FCPATH.$file) || substr($file,0,4)=='http'){
            $queue = $CI->config->item('script_queue');
            $queue[] = $file;
            $CI->config->set_item('script_queue', $queue);
        } else {
            $error =  'The queued script "'. $file.'" doesn\'t exist<br/>';
            $backtrace = debug_backtrace();
            if(array_key_exists('class', $backtrace[1])){
                $error .= 'Triggerd in '. $backtrace[1]['class'].'->'.$backtrace[1]['function'];
            }
            trigger_error($error);
        }
    }
}

if(!function_exists('enqueue_stylesheet')){
    function enqueue_stylesheet($file){
        $CI =& get_instance();
        if(file_exists(FCPATH.$file) || substr($file,0,4)=='http'){
            $queue = $CI->config->item('stylesheet_queue');
            $queue[] = $file;
            $CI->config->set_item('stylesheet_queue', $queue);
        } else {
            $error =  'The queued stylesheet "'. $file.'" doesn\'t exist<br/>';
            $backtrace = debug_backtrace();
            if(array_key_exists('class', $backtrace[1])){
                $error .= 'Triggerd in '. $backtrace[1]['class'].'->'.$backtrace[1]['function'];
            }
            trigger_error($error);
        }
    }
}

if(!function_exists('enqueue_customscript')){
    function enqueue_customscript($script){
        $CI =& get_instance();
        $queue = $CI->config->item('custom_scripts');
        $queue[] = $script;
        $CI->config->set_item('custom_scripts', $queue);
    }
}

if(!function_exists('print_scripts')){
    function print_scripts(){
        $CI =& get_instance();
        $queue = $CI->config->item('script_queue');
        foreach($queue as $script){
            echo '<script src="'.$script.'"></script>'.PHP_EOL;
        }
        $queue = $CI->config->item('custom_scripts');
        foreach($queue as $script){
            echo '<script>'.$script.'</script>'.PHP_EOL;
        }
    }
}

if(!function_exists('print_stylesheets')){
    function print_stylesheets(){
        $CI =& get_instance();
        $queue = $CI->config->item('stylesheet_queue');
        foreach($queue as $stylesheet){
            echo '<link rel="stylesheet" href="'.$stylesheet.'">'.PHP_EOL;
        }
    }
}

if(!function_exists('set_title')){
    function set_title($title){
        $CI =& get_instance();
	  $current = ' | '.$CI->config->item('meta_title');
        $CI->config->set_item('meta_title', $title);
    }
}

if(!function_exists('print_title')){
    function print_title(){
        $CI =& get_instance();
        if($CI->config->item('meta_title')){
            echo $CI->config->item('meta_title');
        }
    }
}

if(!function_exists('set_description')){
    function set_description(){
        $CI =& get_instance();
        if($CI->config->item('meta_description')!=""){
            trigger_error("Description has been set more then once");
        } else {
            $CI->config->set_item('meta_description', $title);
        }
    }
}

if(!function_exists('print_description')){
    function print_description(){
        $CI =& get_instance();
        $desc = $CI->config->item('meta_description');
        if(strlen($desc)>0){
            echo '<meta name="description" content="'.$desc.'">';
        }
    }
}

if(!function_exists('print_account_switch')){
    function print_account_switch($location=false){
        enqueue_customscript("$('#switch select').on('change', function (){
            $.post('/home/switchType',{account_id:$(this).val()},function(){".($location?'document.location.pathname="'.$location.'"':'document.location.reload()')."});
        });");
        $CI =& get_instance();
        $current_id = ACCOUNT_ID;
        echo '<div id="switch"><select>';
        foreach($CI->account->readAll() as $account){
            echo '<option '.($current_id==$account->id?'selected="selected"':'').' value="'.$account->id.'">'.$account->naam.'</option>';
        }
        echo '</select></div>';
    }
}