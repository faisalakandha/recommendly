<!Doctype html>
<html>

<head>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <style>
        .flex-container {
            display: flex;
            justify-content: space-between;
        }

        #output-content {
            height: 300px;
            font-size: 12px;
            width: 99%;
            border: none;
            border-top-style: none;
            background-color: white;
            color: #000;
        }
    </style>

    <script>
        async function postData(data) {
            return fetch('http://127.0.0.1/wp-json/recommendly/v1/saveapikey', {
                    method: 'POST', // or 'PUT',
                    mode: 'cors', // no-cors, *cors, same-origin
                    headers: {
                        'Content-Type': 'application/json',

                    },
                    body: JSON.stringify(data),
                })
                .then((response) => response.text())
                .then(function(data) {
                    console.log(data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }

        $("document").ready(function() {
            $("#apiSubmit").click(function() {
                var apiKey = $('#apiField').val();

                var data = {
                    key: apiKey
                };

                postData(data);
            });

        });
    </script>
</head>

<body>

    <?php
    require_once(ABSPATH . 'wp-content/plugins/recommendly/includes/database-operations.php');

    /**
     * Register a custom menu admin page
     */

    function register_my_custom_menu_page()
    {

        add_menu_page(
            __('Recommendly Manager', 'textdomain'),
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

        <div class="flex-container">
            <div style="margin-right: 60px;">
                <div style="border-bottom: 1px solid black; padding-bottom: 30px;">
                    <h1 style="margin-bottom: 50px;">Recommendly Manager</h1>
                    <u>
                        <h3>NLPCloud API Setup:</h3>
                    </u>
                    <form action='' method='post'>
                        <label for="apiField"><b>Api Key:</b></label>
                        <input value="<?php $option = get_option('nlpcloud_apikey');
                                        echo $option; ?>" id="apiField" size="93" type="password">
                        <input id="apiSubmit" type="button" value="Save">
                    </form>
                    <p>Copy the API Key provided by NlpCloud and paste it on the above field and click "Save".</p>
                </div>
                <div style="padding-top: 20px; border-bottom: 1px solid black; padding-bottom: 30px;">
                    <u>
                        <h3>Create/Remove All Internal Links:</h3>
                    </u>
                    <p>Create internal links for all of the existing posts.
                        Also you can set a time when the plugin will check for new posts and create internal links for them.</p>
                    <button id="cilfap" type="button">Create Internal Links for All Posts</button> <br><br>
                    <button type="button">Remove Internal Links for All Posts</button>
                </div>
                <div style="padding-top: 20px;">
                    <u>
                        <h3>Check & Update Internal Links:</h3>
                    </u>
                    <p>This feature allows you to check for latest posts and create internal links for them.</p>
                    <button type="button">Check for new Updates</button>
                </div>
            </div>
            <div>
                <div style="margin-left:60px; margin-top: 70px;" class="status">
                    <u>
                        <h3>Posts & Links Status:</h3>
                    </u>
                    <p>This shows how many posts you already have and how many links can be built with them.</p>
                    <p>However, it will be not as much as all the links shown on your website.</p>
                    <p><b>Total Published Posts:</b> <?php echo wp_count_posts()->publish; ?></p>
                    <p><b>Total Links Created:</b> <?php echo GetLinksCount()[0]->links; ?></p>
                </div>
                <div style="margin-left:60px;">
                    <u>
                        <h3>Logs:</h3>
                    </u>
                    <p>This keeps reord for all of the activity for building/removing/managing internal links.</p>
                    <textarea style="padding-top: 16px;" 
                    id="output-content" disabled 
                    class="form-control"><?php $dir = wp_upload_dir()['basedir'] . '/' . 'WPRecommendly' . '.log';
                    $myfile = fopen($dir, "r") or die("Logs are empty or Unable to open log file !");
                    echo fread($myfile, filesize($dir));
                    fclose($myfile);
                    ?>              
                    </textarea>
                </div>
            </div>

        </div>

    <?php

        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }

    ?>

</body>

</html>