<?php

if ( ! function_exists( 'plugin_log' ) ) {
  function plugin_log( $entry, $mode = 'a', $file = 'WPRecommendly' ) { 
    // Get WordPress uploads directory.
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    // If the entry is array, json_encode.
    if ( is_array( $entry ) ) { 
      $entry = json_encode( $entry ); 
    } 
    // Write the log file.
    $file  = $upload_dir . '/' . $file . '.log';
    $fileName = $file;
    
    if (is_file($fileName) && filesize($fileName) > 3000000) 
    {
   	unlink($fileName);
    }
	  
    $file  = fopen( $file, $mode );
    $bytes = fwrite( $file, current_time( 'mysql' ) . "::" . $entry . "\n" ); 
    fclose( $file ); 
    return $bytes;
  }
}

?>
