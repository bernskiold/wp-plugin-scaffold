<?php
/**
 * Data Store Interface
 *
 * Our data stores should behave in the same way, regardless
 * of whether they are CPTs, Taxonomies or Custom Tables.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold\Framework
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Framework\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Interface DataStoreInterface
 *
 * @package BernskioldMedia\WP\PluginScaffold\Framework
 */
interface DataStoreInterface {

	/**
	 * Get the object key.
	 *
	 * @return mixed
	 */
	public static function get_key();

	/**
	 * Create an item.
	 */
	public static function create( string $name, array $args = [] ): int;

	/**
	 * Update an item with new values.
	 *
	 * @return mixed
	 */
	public static function update( int $object_id, array $args = [] );

	/**
	 * Delete an item.
	 */
	public static function delete( int $object_id, bool $skip_trash = false ): bool;

}
