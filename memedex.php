<?php
/*
Plugin Name: MEMEdex Polls
Plugin URI: http://www.memedex.com/docs.php?doc=wordpress
Description: Allows embedding polls created at MEMEdex into your posts.
Version: 1.1
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

add_filter('the_content', 'filter_memedex_tag');
?>
