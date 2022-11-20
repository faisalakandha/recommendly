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

function get_bigger_number($A, $B)
{
    $AC = floor($A / 630);
    $BC = floor($B / 630);

    if($AC <= $BC)
    {
        return $AC;
    }
    else
    {
        return $BC;
    }
}

function get_smaller_string($A,$B)
{
    if(strlen($A) <= strlen($B))
    {
        return $A;
    }
    else 
    {
        return $B;
    }
}
