=== MEMEdex Polls ===
Contributors: stromdotcom
Tags: memedex, poll, post, sidebar, plugin
Requires at least: 2.0
Tested up to: 2.3.3
Stable tag: 1.0

This plugin allows simple embedding of MEMEdex flash polls into your Wordpress posts, or into your sidebar, by using a short tag. 

== Description ==

The MEMEdex Wordpress plugin allows you to add MEMEdex polls to your Wordpress posts by adding a short tag to your post where you want the poll to appear.  Your users can vote on polls you create from within your site without ever leaving your Wordpress blog (in fact, they don't even have to leave the page!).  When users vote, their results get tallied by MEMEdex, and the results of the poll are displayed.  All of this uses version 1.0 of the MEMEdex API, but all you need to know is how to use the Wordpress tag.

# To embed a poll in a post:

Just add a tag to your post in the form:

\[memedex: PARAMS\]

Where PARAMS is a # delimited list of paramters specifying how the poll should be rendered.  You can have 0, 2, 3 or 4 paramters (but most people will use 2), and they must be in the correct order.

1. The first paramter must be the REQUEST TYPE, specifying how MEMEdex should find a poll for you.  See the list below for values that work here.  If the tag is used with no parameters, random is the default.
2. The second paramter narrows down the first paramter.  So if you entered username for paramter 1, you would enter the username you want to retrieve a poll from.  For pollid, enter the id of the poll you want to display.  Similarly for category or keyword.
3. Specify the width of the flash object (best left as the default)
4. Specify the height of the flash object (best left as the default)

For example, if you created a poll at memedex.com with the id 476777, you would embed it in your post using the following tag:

\[memedex: pollid#476777\]

If you wanted to retrieve a random poll that user stromdotcom created, use the following tag:

[memedex: username#stromdotcom]

If you just want to embed any old random poll, use the simplest form of the tag:

\[memedex:\]

Possible values for the request\_type field are:

* username
* pollid
* category
* keyword
* random

Note: the category request\_type expects request\_value to be an integer.  You can get a list of integer category codes at http://www.memedex.com/categories.php

The width and height parameters (params 3 and 4) are uncommon, but they allow you to specify a custom widht and height for the flash object.  They are not recommended, however, because the flash doesn't look that great in anything but the default size.

# To embed a poll in your theme:

You can also add a poll to your theme file, for example, in the sidebar.  To do this, use the template tag:

`<?php get_memedex_embed_poll(); ?>`

The template tag supports the same parameters as the post tag, in the same order.  So to grab a random poll from MEMEdex user stromdotcom, use the following tag:

`<?php get_memedex_embed_poll("username", "stromdotcom"); ?>`

== Installation ==

1. Upload `memedex.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the \[memedex: p1#p2#p3#p4\] tag in your posts, or use the get\_memedex\_embed\_poll() template tag in your template.

== Frequently Asked Questions ==

= What is MEMEdex? =

MEMEdex is a polling and survey site.  You will probably want to create a free MEMEdex account before using this plugin, so you can create polls which are relevant to your posts.

= What is involved in adding a MEMEdex poll to my post? =

What I usually do is go to the my page on MEMEdex, create a quick poll, and make a note of the poll id.  Then go back to my blog, make a post, and include the memedex tag where I want it.  

== Screenshots ==

1. A MEMEdex poll embedded in a post.
