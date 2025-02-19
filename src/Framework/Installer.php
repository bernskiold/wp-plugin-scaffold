<?php

namespace BernskioldMedia\WP\PluginScaffold\Framework;

defined( 'ABSPATH' ) || exit;

/**
 * Installer
 *
 * @package BernskioldMedia\WP\PluginScaffold\Framework
 */
abstract class Installer {

	public static function install(): void {

		if ( method_exists( static::class, 'scheduled_tasks' ) ) {
			static::scheduled_tasks();
		}

		flush_rewrite_rules();
	}

}
