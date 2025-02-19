<?php
/**
 * REST Endpoint Interface
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold\Framework
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Framework\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Interface RestEndpointInterface
 *
 * @package BernskioldMedia\WP\PluginScaffold\Framework
 */
interface RestEndpointInterface {

	/**
	 * Register Routes
	 */
	public function register_routes(): void;

}
