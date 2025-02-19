<?php

namespace BernskioldMedia\WP\PluginScaffold\Framework\Blocks;

/**
 * Class Block
 *
 * @package BernskioldMedia\WP\BlockLibrary
 */
abstract class Block {

	/**
	 * The name of the block with prefix, such a bm/block-name.
	 */
	protected static string $block_name;

	/**
	 * By entering the block name here, we can expose a few filters
	 * that makes customization easier.
	 */
	protected static string $name;

	/**
	 * This is a wrapper around get_block_wrapper_attributes()
	 * in core, to fix some of the bugs they've introduced.
	 *
	 * Hopefully we can sunset it in the future with a version check,
	 * when 5.7 lands...
	 */
	public static function get_block_wrapper_attributes( array $attributes, array $args ): string {
		$background_color = static::get_attr_value( $attributes, 'backgroundColor' );
		$gradient         = static::get_attr_value( $attributes, 'gradient' );
		$text_color       = static::get_attr_value( $attributes, 'textColor' );

		$classes = [];

		if ( $gradient ) {
			$classes[] = 'has-' . $gradient . '-gradient-background';
		} elseif ( $background_color ) {
			$classes[] = 'has-' . $background_color . '-background-color';
		}

		if ( $text_color ) {
			$classes[] = 'has-' . $text_color . '-color';
		}

		if ( $background_color || $gradient ) {
			$classes[] = 'has-background';
		}

		if ( ! empty( $classes ) && isset( $args['class'] ) ) {
			$args['class'] .= ' ' . implode( ' ', $classes );
		} elseif ( ! empty( $classes ) ) {
			$args['class'] = implode( ' ', $classes );
		}

		$args = apply_filters( 'bm_block_support_' . static::$name . '_wrapper_attributes', $args );

		return get_block_wrapper_attributes( $args );
	}

	/**
	 * Get Attribute Value
	 * Performs checks to see if the attribute is in the list
	 * of attributes given to the render function.
	 *
	 * If not, we default to its default if it exists,
	 * otherwise to null.
	 *
	 * @return mixed|null
	 */
	protected static function get_attr_value( array $attributes, string $name ) {
		return $attributes[ $name ] ?? null;
	}

	/**
	 * The main render function which is used as a callback
	 * in the class that's extending this, when registering the block.
	 *
	 * Automatically adds the blocks' content in the right place.
	 */
	abstract public static function render( array $attributes ): string;
}
