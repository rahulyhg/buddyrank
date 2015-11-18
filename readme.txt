=== BuddyRank ===
Contributors: aheadzen
Tags: buddypress, activity listings, voter plugin, activity rank, activity filtering
Requires at least : 4.0.0
Tested up to: 4.2.2
Stable tag: 2.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Buddypress activity main stream ranking and filtering by keywords


==Description==

Buddypress activity main stream listing display by ranking and keyword filter & hide blocked activity as per keyword added from wp-admin settings.


The plugin will filter and rank for main activity stream (all activity list) only. Not members activity or any other activity listings.


How Activity Ranking works?
--------------------------------
-- The "Voter Plugin" by aheadzen should be installed.

-- Get download "Voter plugin" from <a href="https://wordpress.org/plugins/voter-plugin/" target="_blank">https://wordpress.org/plugins/voter-plugin/</a>

-- Ranking is working the combination score.

		<b>Score = totalVotes +  timeFactor + contentWordCount</b>
				where 
				totalVotes = Total number of up votings
				timeFactor =  100/(0.01 + activity posted hours)  -- if hours more than 24 it will be 0
				contentWordCount = Activity content word count
						

						
How Activity Filter works?
--------------------------------
-- Go to wp-admin > Settings (left menu) > BuddyRank (plugin settings)

-- Enter keywords you want to block.

-- The activity will automatically hidden as per keywords added.




==Installation==
First you have to install the [Voter Plugin](https://wordpress.org/plugins/voter-plugin/).

To install BuddyRank just follow these steps:

* upload the plugin folder to your WordPress plugin folder (/wp-content/plugins)
* activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer
* see the "BuddyRank" plugin added in the list and active it.
* Plugin settings from wp-admin > Settings (left menu) > BuddyRank



You may like "BuddyPress Follow" plugin from url : https://wordpress.org/plugins/buddypress-followers/
* the plugin will added follower's activity rank factor by 100 so it will appear at top rather than other normal activity.




== Screenshots ==
1. Plugin Activation
2. Wp-admin Settings




==Frequently Asked Questions==

1) Where plugin affected ?

==> 
-- Plugin will affected on main activity stream page only
-- Plugin will not affected on members or any other activity stream.




==Changelog==

=1.0.0=

* Fresh Public setup


=1.0.1=

* Added buddypress rank with followers activity rank of multiplication of 100.
* you should include "BuddyPress Follow" plugin from url : https://wordpress.org/plugins/buddypress-followers/


=1.0.2=

* Added buddypress rank with activity type = new_avatar of multiplication of 100.


=1.0.3=

* Added buddypress rank with activity type = activity_photo of multiplication of 100.
	-- added shortcode by buddypress-activity-plus >> https://wordpress.org/plugins/buddypress-activity-plus/
	
=1.0.4=

* Added buddypress rank changed

=1.0.5=

* API followers related change - plugin not working for api user follower condition - SOLVED


=1.0.6=

* Optimization

=1.0.7=

* Buddyrank Weightage added from wp-admin > plugin settings to make it change as per user demand.
