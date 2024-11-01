=== Web Manifest ===

Contributors: fro1d
Donate link: https://www.paypal.me/fro1d
Tags: web manifest, mobile, mobile web, android, progressive web apps
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows to create and configure a web-app manifest file (manifest.json).

== Description ==

This plugin allows to create and configure a web-app manifest file (manifest.json). Web app manifests are part of a collection of web technologies called progressive web apps. Its provides information about an application (such as name, author, icon, and description) in a JSON text file. The purpose of the manifest is to install web applications to the homescreen of a device, providing users with quicker access and a richer experience. [More info](https://developer.mozilla.org/en-US/docs/Web/Manifest) about web manifests.

= Features =

* Set manifest.json settings based on the site general settings.
* Live preview of how your manifest's settings will affect to your site's appearance.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/web-manifest` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Web Manifest screen to configure the plugin

== Frequently Asked Questions ==

= Why I can't manually change *Language* and *Text Direction* parameters? =

This parameters automatically updates, based on the general site settings.

= What about extra fields *Query* and *Fragment* in *Start URL* setting? =

This fields allows you to add more parameters for choosen URL. *Query* is a query string (after the question mark), can be used for set some identification params like utm_source. *Fragment* (after the hashmark) is an anchor to a specific page block.

== Screenshots ==

1. Options page.
2. Live-preview.

== Changelog ==

= 1.1.0 =
* Fix bug with manifest file update.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.1.0 =
* Bug with manifest file update fixed.