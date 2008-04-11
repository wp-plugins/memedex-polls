<?php
/*
 * Library functions for the advanced (PHP > 5.0) version of the MEMEdex Poll plugin
 */

function create_memedex_poll() {
    global $memedex_api_key;

    $toPrint = <<<EOT
<h2>Create New MEMEdex Poll</h2>
EOT;

    if ($_POST['memedex_poll_returning'] == "yes") {
	$error = false;

        $question = urldecode($_POST['memedex_poll_question']);
        $answers = $_POST['memedex_poll_answers'];
	$memedex_poll_mode = $_POST['memedex_poll_is_multiple'] == "yes"?"multiple":"single";
        $category = 8;

        if (count($answers) > 0) {
            foreach($answers AS $key=>$value) {
                $answers[$key] = urldecode($value);

                 if ($answers[$key] == "") unset($answers[$key]);
            }
        }

        // Check for errors
        if ($question == "") {
            $error = true;
            $message = "Missing or malformed question";
        } else if (count($answers) < 2) {
            $error = true;
            $message = "Too few answer options (2 option minimum)";
        } else if (!is_numeric($category)) {
            $error = true;
            $message = "Invalid category";
        }

        $memedex = new bcx_MemedexXMLParser($memedex_api_key);

	if (!$error) {
            $options = array();

            $memedex->getDynamicPoll($options, $question, $answers, $memedex_poll_mode, $category);

	    $memedex_poll_id = $memedex->poll_id;

            $message .= <<<EOT
<p><strong>Saved!</strong>  Your poll tag is:</p>
<p><strong>[memedex: pollid#{$memedex_poll_id}]</strong></p>
<p>Copy and paste the full poll tag into your post.</p>
EOT;
	}

	$toPrint .= <<<EOT
<div class="updated">{$message}</div>
EOT;
    }

    $form_action = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);

    $toPrint .= <<<EOT
<div class="wrap">
<p>Creating polls directly from <a href="http://www.memedex.com/my.php">your MEMEdex account</a> will allow you to manage and track your polls, but if you just want to create a poll quickly to use in a post, you can do that here.  Enter in a question, a couple options, and select whether you want a single choice or multiple choice poll.  When you submit your poll, you will receive the MEMEdex tag you need to copy and paste into your blog post.</p>
</div>

<div class="wrap">
<form name="form1" method="post" action="{$form_action}">
<input type="hidden" name="memedex_poll_returning" value="yes">

<p>Question:  
<input type="text" name="memedex_poll_question" value="" size="20">
</p>
<p>Mode:
<input type="radio" name="memedex_poll_is_multiple" value="no" checked/> Single <input type="radio" name="memedex_poll_is_multiple" value="yes" /> Multiple
</p>
<p>Option:
<input type="text" name="memedex_poll_answers[]" value="" size="20">
</p>
<p>Option:
<input type="text" name="memedex_poll_answers[]" value="" size="20">
</p>
<p>Option:
<input type="text" name="memedex_poll_answers[]" value="" size="20">
</p>
<p>Option:
<input type="text" name="memedex_poll_answers[]" value="" size="20">
</p>
<p>Option:
<input type="text" name="memedex_poll_answers[]" value="" size="20">
</p>
<hr />

<p class="submit">
<input type="submit" name="Submit" value="Create Poll" />
</p>

</form>
</div>
<div class="wrap">
<strong>Note:</strong> Your poll must have at least two options!
</div>
EOT;

    echo $toPrint;
}

function add_memedex_menus() {
    add_submenu_page('post-new.php', 'Create MEMEdex Poll', 'MEMEdex Poll', 0, __FILE__, 'create_memedex_poll');
}

add_action('admin_menu', 'add_memedex_menus');
?>
