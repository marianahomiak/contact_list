<?php

function get_data($name){

    if(empty($_REQUEST[$name])&&(!empty($_SESSION[$name]))) {
        return $_SESSION[$name];
    } else if (empty($_SESSION[$name]) && (empty($_REQUEST[$name]))) {

        switch ($name) {
            case 'start_date':
                return date("Y-m-1",strtotime("-1 month "));
                break;
            case 'end_date':
                return date("Y-m-t",strtotime("-1 month "));
                break;
            case 'start_time':
                return date("H:m:s",'21:00:00');
                break;
            case 'end_time':
                return date("H:m:s",'03:00:00');
                break;
        }

    } else if(!empty($_REQUEST[$name])){
        return $_REQUEST[$name];
    }
}
