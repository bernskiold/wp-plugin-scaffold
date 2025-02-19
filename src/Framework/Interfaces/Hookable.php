<?php

namespace BernskioldMedia\WP\PluginScaffold\Framework\Interfaces;

/**
 * Interface Hookable
 *
 * @package BernskioldMedia\WP\PluginScaffold\Framework\Interfaces
 */
interface Hookable {

	/**
	 * Hookable classes must implement a standardized hooks function
	 * that can be called when booted.
	 */
	public static function hooks(): void;

}
