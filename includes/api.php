<?php
require_once(plugin_dir_path(__FILE__) . '/logging.php');
require_once(plugin_dir_path(__FILE__) . '/updates.php');

global $wpdb;
$GLOBALS['table_name'] = $table_name = $wpdb->prefix . "recommendly";

function GetSimilarTextFromAPI($textA, $textB)
{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.nlpcloud.io/v1/paraphrase-multilingual-mpnet-base-v2/semantic-similarity');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"sentences\":[\"{$textA}\",\"{$textB}\"]}");
        $apikey = get_option('nlpcloud_apikey');
        $headers = array();
        $headers[] = "Authorization: Token {$apikey}";
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            plugin_log("NlpCloud API Call Failed: {$error}");
        }

        $res_json = json_decode($result);

        curl_close($ch);
        plugin_log("NlpCloud API Call Successful.");
        return $res_json->score;
}

add_action('rest_api_init', function () {

    register_rest_route('recommendly/v1', 'createinternallinksforallposts', array(
        'methods' => 'POST',
        'callback' => 'CreateInternalLinksForAllPosts',
        'args' => array(),
        'permission_callback' => 'IsUserAdmin'
    ));
});

add_action('rest_api_init', function () {

    register_rest_route('recommendly/v1', 'saveapikey', array(
        'methods' => 'POST',
        'callback' => 'SaveApiKey',
        'args' => array(),
        'permission_callback' => 'IsUserAdmin'
    ));
});

add_action('rest_api_init', function () {

    register_rest_route('recommendly/v1', 'removeinternallinks', array(
        'methods' => 'POST',
        'callback' => 'RemoveInternalLinks',
        'args' => array(),
        'permission_callback' => 'IsUserAdmin'
    ));
});

add_action('rest_api_init', function () {

    register_rest_route('recommendly/v1', 'updates', array(
        'methods' => 'POST',
        'callback' => 'CheckForUpdates',
        'args' => array(),
        'permission_callback' => 'IsUserAdmin'
    ));
});

add_action('rest_api_init', function () {

    register_rest_route('recommendly/v1', 'cronoption', array(
        'methods' => 'POST',
        'callback' => 'CronOptions',
        'args' => array(),
        'permission_callback' => 'IsUserAdmin'
    ));
});


function SaveApiKey($req)
{
    $parameters = $req->get_params();

    $apiKey = $parameters['key'];
    
    if(empty(get_option('nlpcloud_apikey')))
    {
        add_option('nlpcloud_apikey', "{$apiKey}");
        plugin_log("API Key Successfully Added");

    } else {

        update_option('nlpcloud_apikey', "{$apiKey}");
        plugin_log("API Key Successfully Updated");
    }
    
    return "API Key Saved !";
}

function CreateInternalLinksForAllPosts($req)
{
    $parameters = $req->get_params();
    plugin_log("Creating Internal Links for All Existing Posts Started....");
    CheckForNewUpdates();

    return "Successfully Created Internal Links for All Posts !";

}

function RemoveInternalLinks($req)
{
    $parameters = $req->get_params();
    plugin_log("Removing Internal Links for All Existing Posts Started....");

    global $wpdb;

    $sql = "DELETE FROM {$GLOBALS['table_name']}";
    $result = $wpdb->get_results($sql);
    if ($wpdb->last_error) {
        echo 'wpdb error: ' . $wpdb->last_error;
    }

    return "Successfully Removed Internal Links for All Posts !";
}

function CheckForUpdates($req)
{
    $parameters = $req->get_params();
    plugin_log("Checking for new post updates !");
    CheckForNewUpdates();
    return "Posts are successfully updated !";
}

function CronOptions($req)
{
    $parameters = $req->get_params();
    $option = $parameters['option'];
    plugin_log("Cron option $option is selected");
    update_option('cron_links',$option);
    return "Automatic Update Option Successfully Selected !";
}

function IsUserAdmin($request) 
{ 
    return current_user_can('manage_options');
}


?>