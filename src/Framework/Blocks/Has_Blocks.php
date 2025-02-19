<?php

namespace BernskioldMedia\WP\PluginScaffold\Framework\Blocks;

/**
 * Trait Has_Blocks
 *
 * Designed to extend a core plugin file that is a plugin
 * that provides blocks for output.
 *
 * @property array  $conditional_blocks An array of blocks that are only loaded if the corresponding class name exists. blockname => classname.
 * @property array  $dynamic_blocks     An array of blocks that are dynamic, meaning they use PHP to load on the frontend. blockname => classname.
 * @property string $block_prefix       The prefix for all the blocks in this plugin.
 *
 * @package BernskioldMedia\WP\Block_Plugin_Support\Traits
 */
trait Has_Blocks {

	/**
	 * An array of blocks in this plugin.
	 */
	protected array $blocks = [];

	/**
	 * Get all of the blocks in the blocks folder and register them.
	 */
	protected function load_blocks(): void {
		$blocks = glob( static::get_path() . 'blocks/*' );

		$this->blocks = apply_filters( static::$slug . '_blocks', $blocks );
	}

	public function register_blocks(): void {
		foreach ( $this->blocks as $directory ) {
			$parts = explode( '/', $directory );
			$name  = end( $parts );

			// If this is a conditional block and condition not met, then skip loading.
			if ( isset( static::$conditional_blocks[ $name ] ) && ! class_exists( static::$conditional_blocks[ $name ] ) ) {
				continue;
			}

			// Get the asset file data.
			$asset_meta = include static::get_path() . 'dist/blocks/' . $name . '.asset.php';

			// Register the script with WordPress.
			wp_register_script( static::get_block_prefix() . '-block-' . $name, static::get_url( 'dist/blocks/' . $name . '.js' ), $asset_meta['dependencies'],
				$asset_meta['version'], true );

			// Register the block. Dynamic blocks get their callback.
			if ( isset( static::$dynamic_blocks[ $name ] ) ) {
				// If we have additional metadata to load, load it.
				if ( method_exists( static::$dynamic_blocks[ $name ], 'add_metadata' ) ) {
					add_filter( 'block_type_metadata_settings', [ static::$dynamic_blocks[ $name ], 'add_metadata' ], 10, 2 );
				}

				register_block_type( $directory, [
					'render_callback' => [ static::$dynamic_blocks[ $name ], 'render' ],
				] );
			}
			else {
				register_block_type( $directory );
			}

			// Load translations.
			wp_set_script_translations( static::get_block_prefix() . '-block-' . $name, static::get_textdomain(), static::get_path( 'languages/' ) );
		}
	}

	/**
	 * Get the prefix that we use when registering the blocks. To set your
	 * own prefix, just define the protected static string $block_prefix property
	 * on your base plugin class.
	 *
	 * @return string
	 */
	protected static function get_block_prefix(): string {
		if ( property_exists( static::class, 'block_prefix' ) ) {
			return static::$block_prefix;
		}

		return 'bm';
	}
}
