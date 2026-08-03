<?php
/**
 * Self-hosted theme updates.
 *
 * Lets the SDi theme update itself in place (like a WordPress.org theme):
 * WordPress shows « Mettre à jour » on the theme card and replaces the current
 * version with the new one — no deactivate / delete / reinstall dance.
 *
 * How it works: a small JSON "manifest" is hosted at a URL you control. It
 * declares the latest version + the URL of the theme .zip. When that version is
 * higher than the installed one, WordPress offers the update natively.
 *
 * Manifest example (sdi-theme.json) :
 *   { "version": "1.0.11",
 *     "download_url": "https://sdi-connect.com/updates/sdi.zip",
 *     "details_url": "https://sdi-connect.com" }
 *
 * The manifest URL can be overridden with the SDI_UPDATE_MANIFEST_URL constant
 * (in wp-config.php) or the 'sdi_update_manifest_url' filter. If the manifest is
 * unreachable, nothing happens (no errors, no update shown).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Manifest URL (constant > filter > default on the SDi domain).
 *
 * @return string
 */
function sdi_update_manifest_url() {
	$url = defined( 'SDI_UPDATE_MANIFEST_URL' ) ? SDI_UPDATE_MANIFEST_URL : 'https://sdi-connect.com/updates/sdi-theme.json';
	return apply_filters( 'sdi_update_manifest_url', $url );
}

/**
 * Fetch + cache the update manifest (6h). Returns array() on any problem.
 *
 * @return array
 */
function sdi_update_manifest() {
	$cached = get_transient( 'sdi_update_manifest' );
	if ( false !== $cached ) {
		return is_array( $cached ) ? $cached : array();
	}

	$res = wp_remote_get(
		sdi_update_manifest_url(),
		array(
			'timeout' => 8,
			'headers' => array( 'Accept' => 'application/json' ),
		)
	);

	$data = array();
	if ( ! is_wp_error( $res ) && 200 === (int) wp_remote_retrieve_response_code( $res ) ) {
		$decoded = json_decode( wp_remote_retrieve_body( $res ), true );
		if ( is_array( $decoded ) ) {
			$data = $decoded;
		}
	}

	// Cache success for 6h; cache "nothing" for 30 min so a 404 isn't hammered.
	set_transient( 'sdi_update_manifest', $data, $data ? 6 * HOUR_IN_SECONDS : 30 * MINUTE_IN_SECONDS );
	return $data;
}

/**
 * Inject our theme into the native theme-update check.
 *
 * @param object $transient update_themes transient.
 * @return object
 */
function sdi_inject_theme_update( $transient ) {
	if ( empty( $transient ) || ! is_object( $transient ) ) {
		return $transient;
	}

	$manifest = sdi_update_manifest();
	if ( empty( $manifest['version'] ) || empty( $manifest['download_url'] ) ) {
		return $transient;
	}

	$slug    = get_template(); // 'sdi'.
	$current = wp_get_theme( $slug )->get( 'Version' );

	if ( version_compare( $manifest['version'], (string) $current, '>' ) ) {
		$transient->response[ $slug ] = array(
			'theme'       => $slug,
			'new_version' => (string) $manifest['version'],
			'url'         => isset( $manifest['details_url'] ) ? esc_url_raw( $manifest['details_url'] ) : '',
			'package'     => esc_url_raw( $manifest['download_url'] ),
		);
	} else {
		// Up to date: make sure we don't linger in the "no_update" bucket oddly.
		if ( isset( $transient->response[ $slug ] ) ) {
			unset( $transient->response[ $slug ] );
		}
	}

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'sdi_inject_theme_update' );

/**
 * Honour the manual « Vérifier à nouveau » button: drop our manifest cache when
 * WordPress runs a forced update check.
 */
function sdi_update_force_check() {
	if ( isset( $_GET['force-check'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		delete_transient( 'sdi_update_manifest' );
	}
}
add_action( 'load-update-core.php', 'sdi_update_force_check' );
add_action( 'load-themes.php', 'sdi_update_force_check' );
