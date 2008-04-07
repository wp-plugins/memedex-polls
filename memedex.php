<?php
/*
Plugin Name: MEMEdex Polls
Plugin URI: http://www.memedex.com/docs.php?doc=wordpress
Description: Allows embedding polls created at MEMEdex into your posts.
Version: 1.2
Author: Rick Strom
Author URI: http://www.memedex.com
*/

/*  Copyright 2008  Rick Strom  (email : rick@glowdot.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once(dirname(__FILE__)."/memedex.class.php");

$memedex_api_key = "mapi_d1d8c3e5d5cdd2fa41869c47317edc61";

function memedex_embed_poll($request_type = "", $request_value = "", $width = "300", $height = "250") {
    $out = <<<EOT
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="{$width}" height="{$height}" id="flashPoll" align="middle" style="float:left;padding:10px 10px 10px 0px;">
<param name=FlashVars VALUE="apiKey=&request_type={$request_type}&request_value={$request_value}"/>
<param name="allowScriptAccess" value="always" />
<param name="movie" value="http://www.memedex.com/flash/flashPoll/flashPoll.swf" />
<param name="quality" value="high" />
<param name="wmode" value="transparent" />
<embed src="http://www.memedex.com/flash/flashPoll/flashPoll.swf" quality="high" width="{$width}" height="{$height}" name="flashPoll" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashVars="apiKey=&request_type={$request_type}&request_value={$request_value}" wmode="transparent" style="float:left;padding:10px 10px 10px 0px;"/>
</object>
EOT;

    return $out;
}

function get_memedex_embed_poll($request_type = "", $request_value = "", $width = "300", $height = "250") {
    $out =  memedex_embed_poll($request_type, $request_value, $width, $height);

    echo $out;
}

function filter_memedex_tag($content) {

    while (($start = strpos($content, "[memedex:")) !== false) {
        // Find full tag
        $end = $start + strpos(substr($content, $start), "]") - 1;

        if (!$end) {
            $content = substr($content, 0, $start + 9)."]".substr($content, $start + 10);
        } else {
            $tag = substr($content, $start, $end - $start + 2);

	    $params = str_replace("[memedex:","",$tag);
	    $params = str_replace("]","",$params);
	    $params = rtrim(ltrim($params));
	    $params = explode("#",$params);

	    if ($params[3] == "") $params[3] = "250";
            if ($params[2] == "") $params[2] = "300";

	    $content = str_replace($tag, memedex_embed_poll($params[0], $params[1], $params[2], $params[3]), $content);

        }
    }

    return $content;
}

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

add_filter('the_content', 'filter_memedex_tag');
add_action('admin_menu', 'add_memedex_menus');
?>
