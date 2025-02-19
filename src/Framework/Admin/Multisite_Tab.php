<?php

namespace BernskioldMedia\WP\PluginScaffold\Framework\Admin;

use BernskioldMedia\WP\PluginScaffold\Framework\Interfaces\Hookable;

/**
 * Class Multisite_Tab
 *
 * @package BernskioldMedia\WP\PluginScaffold\Framework\Admin
 */
abstract class Multisite_Tab implements Hookable {

	protected static string $slug;
	protected static string $nonce;
	protected static string $capability = 'manage_sites';

	public static function hooks(): void {
		add_filter( 'network_edit_site_nav_links', [ static::class, 'add_tab' ] );
		add_action( 'network_admin_menu', [ static::class, 'add_page' ] );
		add_action( 'network_admin_edit_' . static::$slug, [ static::class, 'handle_save' ] );
		add_action( 'network_admin_notices', [ static::class, 'notice' ] );
	}

	abstract protected static function get_title(): string;

	abstract public static function notice(): void;

	abstract public static function save( \WP_Site $site, $request_data ): void;

	abstract public static function render(): void;

	/**
	 * Handle the saving.
	 */
	public static function handle_save(): void {
		$site_id = (int) $_POST['id'];

		if ( ! $site_id ) {
			return;
		}

		$site = get_site( $site_id );

		if ( ! $site ) {
			return;
		}

		check_admin_referer( static::$nonce . '-' . $site_id );

		static::save( $site, $_POST );

		wp_redirect( add_query_arg( [
			'page'    => static::$slug,
			'id'      => $site_id,
			'updated' => true,
		], esc_url( network_admin_url( 'sites.php' ) ) ) );

		exit;
	}

	/**
	 * Add Menu Page
	 */
	public static function add_page(): void {
		add_submenu_page( null, static::get_title(), static::get_title(), static::$capability, static::$slug, [ static::class, 'render' ] );
	}

	/**
	 * Add the Tab
	 *
	 * @param  array  $tabs
	 *
	 * @return mixed
	 */
	public static function add_tab( array $tabs ) {
		$tabs[ static::$slug ] = [
			'label' => static::get_title(),
			'url'   => 'sites.php?page=' . static::$slug,
			'cap'   => static::$capability,
		];

		return $tabs;
	}

	/**
	 * Get Site Object from REQUEST.
	 *
	 * @return \WP_Site|null
	 */
	protected static function get_site_from_request(): ?\WP_Site {
		$id = (int) $_REQUEST['id'];

		if ( ! $id ) {
			return null;
		}

		$site = get_site( $id );

		if ( $site ) {
			return $site;
		}

		return null;
	}

}
