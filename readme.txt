=== MEMEdex Polls ===
Contributors: stromdotcom
Tags: memedex, polls
Requires at least: 2.0
Tested up to: 2.3.3

This plugin allows simple embedding of MEMEdex flash polls into your Wordpress posts, or into your sidebar. 

== Description ==

To embed a poll in a post:

Just add a tag to your post in the form:

[memedex: PARAMS]

Where PARAMS is a # delimited list of paramters specifying how the poll should be rendered.  You can have 0, 2, 3 or 4 paramters, and they must be in the correct order.

1. The first paramter must be the REQUEST TYPE, specifying how MEMEdex should find a poll for you.  Options are username, pollid, category, keyword, or random.  If the tag is used with no parameters, random is the default.
2. The second paramter narrows down the first paramter.  So if you entered username for paramter 1, you would enter the username you want to retrieve a poll from.  For pollid, enter the id of the poll you want to display.  Similarly for category or keyword.
3. Specify the width of the flash object (best left as the default)
4. Specify the height of the flash object (best left as the default)

For example, if you created a poll at memedex.com with the id 476777, you would embed it in your post using the following tag:

[memedex: pollid#476777]

If you wanted to retrieve a random poll that user stromdotcom created, use the following tag:

[memedex: username#stromdotcom]

If you just want to embed any old random poll, use the simplest form of the tag:

[memedex:]

== Installation ==

1. Upload `memedex.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the [memedex: p1#p2#p3#p4] tag in your posts, or use the get_memedex_embed_poll template tag in your template.

== Frequently Asked Questions ==

= What is MEMEdex? =

MEMEdex is a polling and survey site.  You will probably want to create a free MEMEdex account before using this plugin, so you can create polls which are relevant to your posts.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt, so in this case, `/tags/4.3/screenshot-1.png` (or jpg, jpeg, gif)

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.