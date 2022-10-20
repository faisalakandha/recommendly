<?php

/**
 * Register a custom menu admin page
 */

function register_my_custom_menu_page()
{

    add_menu_page(

        __('Recommendly Settings', 'textdomain'),
        'Recommendly',
        'manage_options',
        'recommendly.php',
        'show_table',
        'dashicons-code-standards',
        85
    );
}
add_action('admin_menu', 'register_my_custom_menu_page');

function show_table()
{
    ob_start();
?>
    <h1 style="margin-bottom: 50px;">Recommendly Dashboard</h1>

    <u><h3>API Setup:</h3></u>
    <form action='' method='post'>
        <label for="apiField">Api Key:</label>
        <input type="text">
        <input type="button" value="Save">
    </form>
<?php

    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}

?>