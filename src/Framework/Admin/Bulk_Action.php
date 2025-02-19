<?php

namespace BernskioldMedia\WP\PluginScaffold\Framework\Admin;

use BernskioldMedia\WP\PluginScaffold\Framework\Interfaces\Hookable;

/**
 * Class Bulk_Action
 *
 * @package BernskioldMedia\WP\PluginScaffold\Framework\Admin
 */
abstract class Bulk_Action implements Hookable {

	protected static string $scope;
	protected static string $slug;

	public static function hooks(): void {
		add_filter( 'bulk_actions-' . static::$scope, [ static::class, 'register' ] );
		add_filter( 'handle_bulk_actions-' . static::$scope, [ static::class, 'run' ], 10, 3 );
		add_action( 'admin_notices', [ static::class, 'show_notice' ] );
	}

	abstract protected static function process( int $object_id ): void;

	public static function register( array $actions ): array {
		$actions[ static::$slug ] = static::get_name();

		return $actions;
	}

	public static function handle( string $redirect, string $doaction, array $object_ids ): string {
		// Only run for this action.
		if ( static::$slug !== $doaction ) {
			return $redirect;
		}

		foreach ( $object_ids as $object_id ) {
			static::process( $object_id );
		}

		$redirect = add_query_arg( static::$slug . '_finished', count( $object_ids ), $redirect );

		return $redirect;
	}

	public static function show_notice(): void {
		$query_var = static::$slug . '_finished';

		// Only add when query arg is present.
		if ( ! isset( $_REQUEST[ $query_var ] ) || empty( $_REQUEST[ $query_var ] ) ) {
			return;
		}

		?>
		<div id="message" class="updated notice is-dismissable">
			<p><?php echo static::get_success_message( (int) $_REQUEST[ $query_var ] ); // @codingStandardsIgnoreLine ?></p>
		</div>
		<?php
	}

	abstract protected static function get_success_message( int $count ): string;

	abstract protected static function get_name(): string;

}
