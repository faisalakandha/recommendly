<?php
require_once(plugin_dir_path(__FILE__) . '/logging.php');

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
            plugin_log("NlpCloud API Call Failed:");
            plugin_log($error);
        }

        $res_json = json_decode($result);

        curl_close($ch);
        plugin_log("NlpCloud API Call Successful.");
        return $res_json->score;
}

add_action('rest_api_init', function () {

    register_rest_route('recommendly/v1', 'saveapikey', array(
        'methods' => 'POST',
        'callback' => 'SaveApiKey',
        'args' => array(),
        'permission_callback' => '__return_true'
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
    
    return "OK:Recieved";
}


?>