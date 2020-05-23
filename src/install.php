<?php
/**
 * Installer
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

namespace BernskioldMedia\WP\PluginScaffold;

use BernskioldMedia\WP\PluginScaffold\Roles\User_Roles;

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Install {

	/**
	 * Main Install Process
	 */
	public static function install(): void {

		self::scheduled_tasks();
		User_Roles::install();

		do_action( 'wp_plugin_scaffold_install' );

		flush_rewrite_rules();

	}

	/**
	 * Scheduled Tasks
	 */
	public static function scheduled_tasks(): void {

	}

}
