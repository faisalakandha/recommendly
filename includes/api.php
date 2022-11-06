<?php

function GetSimilarTextFromAPI($textA, $textB)
{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.nlpcloud.io/v1/paraphrase-multilingual-mpnet-base-v2/semantic-similarity');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"sentences\":[\"{$textA}\",\"{$textB}\"]}");

        $headers = array();
        $headers[] = 'Authorization: Token e25161be44e585f9eb980ac62253599235196064';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        $res_json = json_decode($result);

        curl_close($ch);
        return $res_json->score;
}

?>