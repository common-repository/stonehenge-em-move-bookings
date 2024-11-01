=== Events Manager - Move Bookings ===
Plugin Name: 		Events Manager – Move Bookings
Contributors: 		DuisterDenHaag
Tags: 				Events Manager, Bookings, move, rebook, reschedule
Donate link: 		https://paymentlink.mollie.com/payment/x7dNYfFAWy6rN6G42PFkv/
Requires at least: 	5.5
Tested up to: 		5.9
Requires PHP: 		7.3
Tested up to PHP:   8.0.15
Stable tag: 		2.0.2
License: 			GPLv2 or later
License URI: 		http://www.gnu.org/licenses/gpl-2.0.txt

Moves an upcoming Booking to different upcoming Event in Events Manager with a simple select dropdown.

== Description ==
> Requires [Events Manager](https://wordpress.org/plugins/events-manager/) (free plugin) to be installed & activated.

**Easily move Events Manager Bookings from one Event to another.**
Do you ever need "rebook a booking" and find that deleting the existing booking and creating a new one manually is just cumbersome? If so, this little add-on for Events Manager makes that extremely easy!

Just go to the single booking page in your WordPress Admin of the booking you want to move. <em>(That is exact same page as where you can change the booking status and/or edit details.)</em>
There you will find a new section, just below the event details. Simply select new upcoming event from the dropdown list, click the "Move Booking" button and you are done.
Remember to notify your customer by using the "Resend Booking Email" button in the same page.

Since version 2.0 the dropdown list will be populated as:
`#_EVENTDATES @ #_EVENTTIMES | #_EVENTNAME`

If manual overbooking is not allowed in your Events Manager settings, the Move Booking dropdown list will not include them.

There are no settings to configure. Just activate the add-on. That's it.

**Important to know:**
1. This is a back-end only add-on. Moving a single booking can not be done from "My Bookings" in the front-end.

2. Different Ticket Types & Prices are not beging checked.
This add-on only checks the ticket availability of the new event. If bookings are open and there are enough spaces left, the booking will be moved.
If you are using (lots of) different ticket types and prices, this may not be the right add-on for you.


== Localisation ==
* US English (default)
* Dutch (always included in the download)

The plugin is ready to be translated, all texts are defined in the POT file. Any contributions to localise this plugin are very welcome!


== Feedback ==
I am open to your suggestions and feedback!
[Please also check out my other useful plugins, tutorials and useful snippets for Events Manager.](https://www.stonehengecreations.nl/)


== Frequently Asked Questions ==
= Are you part of the Events Manager team? =
No, I am not! am not associated with [Events Manager](https://wordpress.org/plugins/events-manager/) or its developer, [NetWebLogic](http://netweblogic.com/), in any way.

= Is this WordPress MultiSite compatible? =
Yes, it is.

= I love this plugin! Let me buy you a coffee. =
How kind! Please, consider a [donation](https://paymentlink.mollie.com/payment/x7dNYfFAWy6rN6G42PFkv/).


== Installation ==
1. First make sure the original Events Manager plugin is installed and activated.
2. Install and activate this plugin.
3. That's it! There are no settings to configure for this add-on.


== Screenshots ==
1. Meta Box in the Single Booking Page.


== Upgrade Notice ==


== Changelog ==
= 2.0.2=
- Bugfix in wp_verify_nonce.
- Updated the .pot file.

= 2.0.1 =
- Minor code correction.
- Better short plugin description.

= 2.0.0 =
- Complete code rewrite for better coding standards.
- New plugin banner and icon
- Confirmed compatibility with WordPress 5.9
- Confirmed compatibility with PHP 8.1.1
- Confirmed compatibility with Events Manager 5.12.1
- Confirmed compatibility with Events Manager Pro 2.7

= 1.2.2 =
- Confirmed compatibility with WordPress 5.5.

= 1.2.1 =
- Confirmed compatibility with WordPress 5.4.
- Confirmed compatibility with PHP 7.4.2.

= 1.2 =
- Some cosmetic enhancements.
- New screenshot

= 1.1 =
- Updated readme.txt
- New graphics for WP Repository

= 1.0 =
- First public release through the WordPress Repository.

