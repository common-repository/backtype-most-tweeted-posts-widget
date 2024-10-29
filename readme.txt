=== BackType Most Tweeted Posts Widget ===
Contributors: Lee Everson
Donate link: http://example.com/
Tags: Tweet, backtype, posts, widget
Requires at least: 2.5
Tested up to: 3.01
Stable tag: 1.3

A widget that displays a list of most tweeted posts in your sidebar (must have BackType Connect installed and active).

== Description ==

This widget displays a list of most tweeted posts in your sidebar

*   The title of the widget [default="Most Tweeted Posts"]
*   The number of Tweeted posts displayed can be configured [default=5]
*	You can configure the widget to only list Tweeted posts from a specific category. [default=All]

== Installation ==

1. Upload `bttc-most-tweeted-posts-widget.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the widget to your sidebar

== Screenshots ==

1. Example listing most tweeted posts
2. Administration of the widget

== Frequently Asked Questions ==

For any questions or suggestions regarding the plugin please visit http://www.extreme-media.co.uk/2010/08/backtype-most-tweeted-posts-widget/

== Changelog ==

= 1.0 =
* First Release.
= 1.1 =
* Added sql to only select published posts.
* Added sql to only select posts with > 0 tweets.

= 1.2 =
* Altered sql to allow fo redit using plugin editor.

= 1.3 =
* Tidy Up.

== Upgrade Notice ==

= 1.1 =
In order to show only posts which are published and have at least 1 tweet

= 1.2 =
In order to be able to edit the plugin using plugin editor, for some reason using SUBSTRING broke the editor but using SUBSTR was OK

= 1.3 =
no real reason to upgrade just tidy code in this one, and to get it in to subversion