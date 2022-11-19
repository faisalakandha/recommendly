<?php

function strip_post_content($string) { 
    // ----- remove HTML TAGs ----- 
    $string = wp_strip_all_tags($string);
    // ----- remove control characters ----- 
    $string = str_replace("\r", '', $string);
    $string = str_replace("\n", ' ', $string);
    $string = str_replace("\t", ' ', $string);
    // ----- remove multiple spaces ----- 
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
    // ----- remove special characters ----- 
    $string = preg_replace('/[^A-Za-z0-9 ]/','',$string);
    return $string; 

}
?>