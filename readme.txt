=== Auto Future Date ===
Contributors: RyanNutt, Aelora
Tags: post, posts, future, date, future date
Requires at least: 3.0.0
Tested up to: 3.0.4
Stable tag: 0.5.2

Adds an "Auto" link to your post page that will automatically schedule your next
post based on the most recent post.

== Description ==

Make it easier on yourself to schedule future posts.

Auto Future Date allows you to quickly and easily schedule posts based on the
last post on your site.  A simple click on a link and your post will be scheduled
for a future date based on a set of simple rules that you set up. 

I use this on a blog of mine where I write several posts at a time and want to
spread them out rather than publishing them all at the same time.  Future posts
are great for this, but I found myself going back and forth to the calendar
to schedule.  

When activated the plugin adds an "Auto" link right next to the link that you
use to schedule a future post.  Clicking on the Auto link makes an Ajax call that
will set the date for your post at some point in the future, using rules that you 
set.

More information at [Nutt.net](http://www.nutt.net/tag/auto-future-date/)

== Installation ==

1. Upload `auto-future-date` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

That's it.  Next time you add or edit a post the Edit link will be there.

= Options =
An options page is added that allows you to set a minimum and maximum amount of time
 to schedule in the future.  It also allows you to set the earliest and
latest times that you want your posts scheduled.

Posts are scheduled based on either the latest future scheduled post or the current
date, whichever is later.

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. Auto link added to the Publish section
2. Auto Future Date Settings page

== Changelog ==

= 0.1 - 1/19/2011 =
First release

= 0.5 - 4/2/2011 =
Upgraded to allow changing minimum and maximum times instead of a min / max
numbers of days. This way you can set future dates with hours and minutes.

= 0.5.1 =
Fixed a stupid bug...

= 0.5.2 =
No functional changes, just moving plugin to a different. No need to upgrade.

== Upgrade Notice ==

= 0.1 =
First version, nothing to upgrade

= 0.5/0.5.1 =
You may need to deactivate and reactivate the plugin