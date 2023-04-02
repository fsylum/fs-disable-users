=== FS Disable Users ===
Contributors: fsylum
Tags: user
Requires at least: 5.9
Tested up to: 6.2
Stable tag: 1.1.1
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Disable selected users from various site activities including preventing them from logging in, resetting passwords, and creating application passwords.

== Description ==

The plugin allows you to disable selected users from your site's user list. A disabled user will NOT be allowed to:

* Log in to the site
* Reset their associated password
* Create a new application password

In addition, they'll also automatically logged out from all sessions as soon as they are disabled.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/fs-disable-users` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to Users page and disable any users that you want.

== Screenshots ==

1. Quick link to disable user in the Users page.
2. Button to disable user when editing a user.
3. Error message shown to the disabled user.

== Changelog ==

* [FIXED] Simplify composer.json and package.json (unrelated to actual plugin)
* [FIXED] Compatibility with WordPress 5.8

**1.1.0**

* [ADDED] Integration with Stream

**1.0.1**

* [FIXED] Remove unrelated Docker files during build process from the final plugin files

**1.0.0**

* Initial release
