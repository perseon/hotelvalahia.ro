=== Hotel Booking ===
Contributors: MotoPress
Donate link: https://motopress.com/
Tags: hotel booking, reservation, hotel, booking engine, booking, booking calendar, booking system
Requires at least: 4.7
Tested up to: 6.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The #1 Hotel Booking and Vacation Rental Plugin for WordPress. Online payments, seasons, rates, free or paid extras, coupons, taxes & fees.

== Description ==
Manage your hotel booking services. Perfect for hotels, villas, guest houses, hostels, and apartments of all sizes.

== Installation ==

1. Upload the MotoPress plugin to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Copyright ==

Hotel Booking plugin, Copyright (C) 2016, MotoPress https://motopress.com/
Hotel Booking plugin is distributed under the terms of the GNU GPL.

== Changelog ==

= 4.7.3, Jun 2 2023 =
* Updated translation files (Danish, Lithuanian, Norwegian).

= 4.7.2, May 17 2023 =
* Bug fix: Fixed an issue that could cause error messages to appear in the direct booking form on certain websites.

= 4.7.1, May 15 2023 =
* Improvement: The plugin now retains past bookings imported from external sources instead of deleting them.
* Bug fix: Fixed an issue that could cause warning messages to appear in .ics files on certain websites.
* Bug fix: The plugin now takes the property capacity into account when guests choose the number of adults and children while making bookings directly from the accommodation page. This fix applies only if the property capacity is set in the settings.

= 4.7.0, Mar 13 2023 =
* Significant revamp of the Direct Bank Transfer method has been made. Bookings made using this method now have a default status of "Pending Payment". You can set a time frame for customers to pay their bookings, and if payment is not received within that timeframe, the booking will automatically become "Abandoned". You can create an email template with instructions for this payment method; please do so in the payment method settings.
* Added the ability to change a default display range on the admin bookings calendar.
* Added year information to the check-in and check-out dates in the Bookings table.
* Added customer name information to the booking details in the admin bookings calendar.
* Fixed an error with displaying a user account when switched to a non-default website language.
* Minor interface improvements.

= 4.6.0, Feb 6 2023 =
* Added the possibility for clients to select the check-in and check-out date directly in the availability calendar.
* Added an error message to the booking form to notify clients when an accommodation ID is not found.
* Removed the maximum limit for the adults and children capacity in the variable pricing rate settings.
* Added the possibility to view the information about imported bookings in the admin calendar after clicking on a particular booking.
* Limited the number of adults and children selectable in the form to match an accommodation’s capacity for bookings directly from the accommodation page.
* Added coupon settings for early bird and last minute discounts.
* Added the price display in the availability calendar for non check-in dates.
* Fixed a database bug related to customer and user data selection.
* Fixed an issue with assigning an accommodation type upon the accommodation creation.
* Improved the plugin compatibility with PHP 8.2

--------

[See the previous changelogs here](https://motopress.com/products/hotel-booking/#release-notes).
