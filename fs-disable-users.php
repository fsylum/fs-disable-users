<?php
/**
 * Plugin Name:         FS Disable Users
 * Plugin URI:          https://github.com/fsylum/fs-disable-users
 * Description:         Disable selected users from various site activities including preventing them from logging in, resetting passwords, and creating application passwords.
 * Author:              Firdaus Zahari
 * Author URI:          https://fsylum.net
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Version:             1.1.1
 * Requires at least:   5.6
 * Requires PHP:        7.3
 */

require __DIR__ . '/vendor/autoload.php';

define('FS_DISABLE_USERS_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('FS_DISABLE_USERS_PLUGIN_URL', untrailingslashit(plugin_dir_url(__FILE__)));
define('FS_DISABLE_USERS_PLUGIN_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('FS_DISABLE_USERS_VERSION', '1.1.1');

$plugin = new Fsylum\DisableUsers\Plugin;

$plugin->addService(new Fsylum\DisableUsers\WP\Auth);
$plugin->addService(new Fsylum\DisableUsers\WP\User);
$plugin->addService(new Fsylum\DisableUsers\WP\Asset);
$plugin->addService(new Fsylum\DisableUsers\WP\Stream\Stream);

$plugin->run();
