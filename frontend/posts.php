<?php

function ti_custom_javascript() {
  if (is_singular( 'post' )) { 
    ?>
        <script type="text/javascript">
          console.log("This is a post !");
        </script>
    <?php
  }
}
add_action('wp_head', 'ti_custom_javascript');

?>