<?php
/**
 * Plugin Name:       SDi Mail Log
 * Plugin URI:        https://sdi-connect.com
 * Description:        Journalise tous les e-mails envoyés par le site (formulaires, agent IA, notifications WordPress), même en cas d'échec d'envoi (API/SMTP). Consultation, recherche, détail et renvoi depuis Outils → Suivi des e-mails.
 * Version:           1.0.0
 * Author:            SDi — Solutions Digitales Intégrées
 * Author URI:        https://sdi-connect.com
 * License:           GPL-2.0-or-later
 * Text Domain:       sdi-mail-log
 *
 * @package SDi_Mail_Log
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SDI_ML_VERSION', '1.0.0' );
define( 'SDI_ML_DB_VERSION', '1' );
define( 'SDI_ML_TABLE', 'sdi_mail_log' );

/**
 * Fully-qualified log table name.
 *
 * @return string
 */
function sdi_ml_table() {
	global $wpdb;
	return $wpdb->prefix . SDI_ML_TABLE;
}

/**
 * Holds the id of the row inserted by the current wp_mail() call, so the
 * matching wp_mail_failed action can downgrade it. wp_mail() is synchronous,
 * so one value is enough.
 *
 * @var int
 */
$GLOBALS['sdi_ml_last_id'] = 0;

/* ============================================================
   Install / schema
   ============================================================ */

/**
 * Create (or upgrade) the log table.
 */
function sdi_ml_install() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$charset = $wpdb->get_charset_collate();
	$table   = sdi_ml_table();

	$sql = "CREATE TABLE {$table} (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
		recipient TEXT NULL,
		subject TEXT NULL,
		body LONGTEXT NULL,
		headers TEXT NULL,
		attachments TEXT NULL,
		status VARCHAR(20) NOT NULL DEFAULT 'sent',
		error TEXT NULL,
		source VARCHAR(120) NULL,
		PRIMARY KEY  (id),
		KEY created_at (created_at),
		KEY status (status)
	) {$charset};";

	dbDelta( $sql );
	update_option( 'sdi_ml_db_version', SDI_ML_DB_VERSION );
}
register_activation_hook( __FILE__, 'sdi_ml_install' );

/**
 * Make sure the table exists even if the plugin files were updated in place
 * (without a fresh activation), and schedule the prune cron.
 */
function sdi_ml_maybe_install() {
	if ( get_option( 'sdi_ml_db_version' ) !== SDI_ML_DB_VERSION ) {
		sdi_ml_install();
	}
	if ( ! wp_next_scheduled( 'sdi_ml_prune' ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'sdi_ml_prune' );
	}
}
add_action( 'admin_init', 'sdi_ml_maybe_install' );

/**
 * Remove the scheduled prune on deactivation.
 */
function sdi_ml_deactivate() {
	$ts = wp_next_scheduled( 'sdi_ml_prune' );
	if ( $ts ) {
		wp_unschedule_event( $ts, 'sdi_ml_prune' );
	}
}
register_deactivation_hook( __FILE__, 'sdi_ml_deactivate' );

/**
 * Delete entries older than the retention window (0 = keep forever).
 */
function sdi_ml_prune() {
	$days = (int) get_option( 'sdi_ml_retention', 30 );
	if ( $days <= 0 ) {
		return;
	}
	global $wpdb;
	$table = sdi_ml_table();
	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$table} WHERE created_at < %s", gmdate( 'Y-m-d H:i:s', time() - $days * DAY_IN_SECONDS ) ) );
}
add_action( 'sdi_ml_prune', 'sdi_ml_prune' );

/* ============================================================
   Capture every outgoing e-mail
   ============================================================ */

/**
 * Log the e-mail as it enters wp_mail(). Runs last so it sees the final args.
 * Optimistically stored as 'sent'; wp_mail_failed downgrades it on error.
 *
 * @param array $atts wp_mail arguments.
 * @return array Unchanged.
 */
function sdi_ml_capture( $atts ) {
	global $wpdb;

	$to = isset( $atts['to'] ) ? $atts['to'] : '';
	if ( is_array( $to ) ) {
		$to = implode( ', ', $to );
	}
	$subject = isset( $atts['subject'] ) ? (string) $atts['subject'] : '';
	$message = isset( $atts['message'] ) ? $atts['message'] : '';
	if ( is_array( $message ) ) {
		$message = implode( "\n", $message );
	}
	$headers = isset( $atts['headers'] ) ? $atts['headers'] : '';
	if ( is_array( $headers ) ) {
		$headers = implode( "\n", $headers );
	}
	$attach = isset( $atts['attachments'] ) ? $atts['attachments'] : '';
	if ( is_array( $attach ) ) {
		$attach = implode( "\n", $attach );
	}

	$log_body = get_option( 'sdi_ml_log_body', 1 );

	$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		sdi_ml_table(),
		array(
			'created_at'  => current_time( 'mysql' ),
			'recipient'   => $to,
			'subject'     => $subject,
			'body'        => $log_body ? (string) $message : '(corps non journalisé — voir réglages)',
			'headers'     => (string) $headers,
			'attachments' => (string) $attach,
			'status'      => 'sent',
			'error'       => '',
			'source'      => sdi_ml_guess_source(),
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
	);
	$GLOBALS['sdi_ml_last_id'] = (int) $wpdb->insert_id;

	return $atts;
}
add_filter( 'wp_mail', 'sdi_ml_capture', PHP_INT_MAX );

/**
 * Downgrade the current row to 'failed' and record the error.
 *
 * @param WP_Error $error Failure details.
 */
function sdi_ml_failed( $error ) {
	global $wpdb;
	if ( empty( $GLOBALS['sdi_ml_last_id'] ) ) {
		return;
	}
	$msg = is_wp_error( $error ) ? $error->get_error_message() : __( 'Échec d\'envoi (raison inconnue).', 'sdi-mail-log' );
	$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		sdi_ml_table(),
		array( 'status' => 'failed', 'error' => $msg ),
		array( 'id' => (int) $GLOBALS['sdi_ml_last_id'] ),
		array( '%s', '%s' ),
		array( '%d' )
	);
}
add_action( 'wp_mail_failed', 'sdi_ml_failed' );

/**
 * Best-effort guess of what triggered the e-mail (for the log's "Source" column).
 *
 * @return string
 */
function sdi_ml_guess_source() {
	if ( isset( $_POST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$action = sanitize_text_field( wp_unslash( $_POST['action'] ) );
		if ( 'sdi_contact' === $action ) {
			return 'Formulaire de contact';
		}
		if ( 'sdi_ai_transcript' === $action ) {
			return 'Agent IA (conversation)';
		}
		if ( $action ) {
			return 'Action : ' . $action;
		}
	}
	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		return 'Tâche planifiée (cron)';
	}
	if ( is_admin() ) {
		return 'Administration';
	}
	return 'Site';
}

/* ============================================================
   Admin screen
   ============================================================ */

/**
 * Register the "Suivi des e-mails" page under Tools.
 */
function sdi_ml_menu() {
	$hook = add_management_page(
		__( 'Suivi des e-mails', 'sdi-mail-log' ),
		__( 'Suivi des e-mails', 'sdi-mail-log' ),
		'manage_options',
		'sdi-mail-log',
		'sdi_ml_render_page'
	);
	add_action( "load-{$hook}", 'sdi_ml_handle_actions' );
}
add_action( 'admin_menu', 'sdi_ml_menu' );

/**
 * A small "failed e-mails" bubble next to the menu label.
 */
function sdi_ml_failed_count() {
	global $wpdb;
	$table = sdi_ml_table();
	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE status = 'failed'" );
}

/**
 * Handle resend / delete / clear / settings before rendering.
 */
function sdi_ml_handle_actions() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	global $wpdb;
	$table = sdi_ml_table();

	// Save settings.
	if ( isset( $_POST['sdi_ml_save_settings'] ) ) {
		check_admin_referer( 'sdi_ml_settings' );
		update_option( 'sdi_ml_retention', max( 0, (int) ( $_POST['sdi_ml_retention'] ?? 30 ) ) );
		update_option( 'sdi_ml_log_body', empty( $_POST['sdi_ml_log_body'] ) ? 0 : 1 );
		wp_safe_redirect( add_query_arg( 'sdi_msg', 'saved', sdi_ml_base_url() ) );
		exit;
	}

	// Resend a logged e-mail.
	if ( isset( $_GET['sdi_action'] ) && 'resend' === $_GET['sdi_action'] ) {
		check_admin_referer( 'sdi_ml_resend' );
		$id  = absint( $_GET['id'] ?? 0 );
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id ) );
		$msg = 'resend_fail';
		if ( $row ) {
			$headers = $row->headers ? explode( "\n", $row->headers ) : array();
			$attach  = $row->attachments ? array_filter( explode( "\n", $row->attachments ) ) : array();
			$ok      = wp_mail( $row->recipient, $row->subject, $row->body, $headers, $attach );
			$msg     = $ok ? 'resend_ok' : 'resend_fail';
		}
		wp_safe_redirect( add_query_arg( 'sdi_msg', $msg, sdi_ml_base_url() ) );
		exit;
	}

	// Delete one entry.
	if ( isset( $_GET['sdi_action'] ) && 'delete' === $_GET['sdi_action'] ) {
		check_admin_referer( 'sdi_ml_delete' );
		$id = absint( $_GET['id'] ?? 0 );
		$wpdb->delete( $table, array( 'id' => $id ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		wp_safe_redirect( add_query_arg( 'sdi_msg', 'deleted', sdi_ml_base_url() ) );
		exit;
	}

	// Clear the whole log.
	if ( isset( $_POST['sdi_ml_clear'] ) ) {
		check_admin_referer( 'sdi_ml_clear' );
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query( "DELETE FROM {$table}" );
		wp_safe_redirect( add_query_arg( 'sdi_msg', 'cleared', sdi_ml_base_url() ) );
		exit;
	}
}

/**
 * Base admin URL of the page (without transient query args).
 *
 * @return string
 */
function sdi_ml_base_url() {
	return admin_url( 'tools.php?page=sdi-mail-log' );
}

/**
 * Render the admin page (list + detail + settings).
 */
function sdi_ml_render_page() {
	global $wpdb;
	$table = sdi_ml_table();

	// Notices.
	$msg = isset( $_GET['sdi_msg'] ) ? sanitize_key( wp_unslash( $_GET['sdi_msg'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$notices = array(
		'saved'       => array( 'updated', __( 'Réglages enregistrés.', 'sdi-mail-log' ) ),
		'resend_ok'   => array( 'updated', __( 'E-mail renvoyé. Une nouvelle entrée apparaît ci-dessous.', 'sdi-mail-log' ) ),
		'resend_fail' => array( 'error', __( "Le renvoi a échoué (vérifiez votre configuration SMTP). L'échec est journalisé.", 'sdi-mail-log' ) ),
		'deleted'     => array( 'updated', __( 'Entrée supprimée.', 'sdi-mail-log' ) ),
		'cleared'     => array( 'updated', __( 'Journal vidé.', 'sdi-mail-log' ) ),
	);

	// Filters.
	$paged    = max( 1, (int) ( $_GET['paged'] ?? 1 ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$per_page = 25;
	$offset   = ( $paged - 1 ) * $per_page;
	$search   = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$fstatus  = isset( $_GET['status'] ) ? sanitize_key( wp_unslash( $_GET['status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$view_id  = isset( $_GET['view'] ) ? absint( $_GET['view'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification

	// Build WHERE.
	$where  = '1=1';
	$params = array();
	if ( 'sent' === $fstatus || 'failed' === $fstatus ) {
		$where   .= ' AND status = %s';
		$params[] = $fstatus;
	}
	if ( '' !== $search ) {
		$like    = '%' . $wpdb->esc_like( $search ) . '%';
		$where  .= ' AND ( recipient LIKE %s OR subject LIKE %s OR body LIKE %s )';
		$params[] = $like; $params[] = $like; $params[] = $like;
	}

	// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$total = (int) $wpdb->get_var( $params ? $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE {$where}", $params ) : "SELECT COUNT(*) FROM {$table} WHERE {$where}" );
	$q      = "SELECT * FROM {$table} WHERE {$where} ORDER BY id DESC LIMIT %d OFFSET %d";
	$rows   = $wpdb->get_results( $wpdb->prepare( $q, array_merge( $params, array( $per_page, $offset ) ) ) );
	$stat   = $wpdb->get_row( "SELECT COUNT(*) total, SUM(status='failed') failed FROM {$table}" );
	// phpcs:enable
	$view_row = $view_id ? $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $view_id ) ) : null; // phpcs:ignore

	$retention = (int) get_option( 'sdi_ml_retention', 30 );
	$log_body  = (int) get_option( 'sdi_ml_log_body', 1 );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Suivi des e-mails', 'sdi-mail-log' ); ?></h1>
		<hr class="wp-header-end">

		<?php if ( isset( $notices[ $msg ] ) ) : ?>
			<div class="notice notice-<?php echo esc_attr( $notices[ $msg ][0] ); ?> is-dismissible"><p><?php echo esc_html( $notices[ $msg ][1] ); ?></p></div>
		<?php endif; ?>

		<p style="margin:.5em 0 1em;color:#50575e;">
			<?php
			printf(
				/* translators: 1: total, 2: failed */
				esc_html__( '%1$s e-mails journalisés · %2$s en échec. Chaque e-mail sortant du site est enregistré ici, même si l\'envoi échoue.', 'sdi-mail-log' ),
				'<strong>' . esc_html( number_format_i18n( (int) $stat->total ) ) . '</strong>',
				'<strong style="color:' . ( $stat->failed ? '#b32d2e' : '#2271b1' ) . ';">' . esc_html( number_format_i18n( (int) $stat->failed ) ) . '</strong>'
			);
			?>
		</p>

		<?php if ( $view_row ) : ?>
			<?php sdi_ml_render_detail( $view_row ); ?>
		<?php endif; ?>

		<!-- Filters -->
		<form method="get" style="margin:12px 0;display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
			<input type="hidden" name="page" value="sdi-mail-log">
			<select name="status">
				<option value=""><?php esc_html_e( 'Tous les statuts', 'sdi-mail-log' ); ?></option>
				<option value="sent" <?php selected( $fstatus, 'sent' ); ?>><?php esc_html_e( 'Envoyés', 'sdi-mail-log' ); ?></option>
				<option value="failed" <?php selected( $fstatus, 'failed' ); ?>><?php esc_html_e( 'En échec', 'sdi-mail-log' ); ?></option>
			</select>
			<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Rechercher (destinataire, objet, contenu)…', 'sdi-mail-log' ); ?>" style="min-width:280px;">
			<?php submit_button( __( 'Filtrer', 'sdi-mail-log' ), 'secondary', '', false ); ?>
			<?php if ( $search || $fstatus ) : ?>
				<a class="button" href="<?php echo esc_url( sdi_ml_base_url() ); ?>"><?php esc_html_e( 'Réinitialiser', 'sdi-mail-log' ); ?></a>
			<?php endif; ?>
		</form>

		<!-- Log table -->
		<table class="widefat striped">
			<thead>
				<tr>
					<th style="width:150px;"><?php esc_html_e( 'Date', 'sdi-mail-log' ); ?></th>
					<th><?php esc_html_e( 'Destinataire', 'sdi-mail-log' ); ?></th>
					<th><?php esc_html_e( 'Objet', 'sdi-mail-log' ); ?></th>
					<th style="width:150px;"><?php esc_html_e( 'Source', 'sdi-mail-log' ); ?></th>
					<th style="width:90px;"><?php esc_html_e( 'Statut', 'sdi-mail-log' ); ?></th>
					<th style="width:150px;"><?php esc_html_e( 'Actions', 'sdi-mail-log' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if ( ! $rows ) : ?>
				<tr><td colspan="6"><?php esc_html_e( 'Aucun e-mail journalisé pour ces critères.', 'sdi-mail-log' ); ?></td></tr>
			<?php else : ?>
				<?php foreach ( $rows as $r ) : ?>
					<?php
					$view_url   = wp_nonce_url( add_query_arg( array( 'view' => $r->id ), sdi_ml_base_url() ), 'sdi_ml_view', '_wpnonce' );
					$resend_url = wp_nonce_url( add_query_arg( array( 'sdi_action' => 'resend', 'id' => $r->id ), sdi_ml_base_url() ), 'sdi_ml_resend' );
					$delete_url = wp_nonce_url( add_query_arg( array( 'sdi_action' => 'delete', 'id' => $r->id ), sdi_ml_base_url() ), 'sdi_ml_delete' );
					?>
					<tr>
						<td><?php echo esc_html( mysql2date( 'd/m/Y H:i', $r->created_at ) ); ?></td>
						<td><?php echo esc_html( $r->recipient ); ?></td>
						<td><a href="<?php echo esc_url( add_query_arg( 'view', $r->id, sdi_ml_base_url() ) ); ?>"><?php echo esc_html( $r->subject ? $r->subject : '(sans objet)' ); ?></a></td>
						<td><?php echo esc_html( $r->source ); ?></td>
						<td>
							<?php if ( 'failed' === $r->status ) : ?>
								<span style="display:inline-block;padding:2px 9px;border-radius:10px;background:#fcebea;color:#b32d2e;font-weight:600;font-size:12px;"><?php esc_html_e( 'Échec', 'sdi-mail-log' ); ?></span>
							<?php else : ?>
								<span style="display:inline-block;padding:2px 9px;border-radius:10px;background:#edfaef;color:#1e7e34;font-weight:600;font-size:12px;"><?php esc_html_e( 'Envoyé', 'sdi-mail-log' ); ?></span>
							<?php endif; ?>
						</td>
						<td>
							<a href="<?php echo esc_url( add_query_arg( 'view', $r->id, sdi_ml_base_url() ) ); ?>"><?php esc_html_e( 'Voir', 'sdi-mail-log' ); ?></a> ·
							<a href="<?php echo esc_url( $resend_url ); ?>"><?php esc_html_e( 'Renvoyer', 'sdi-mail-log' ); ?></a> ·
							<a href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('<?php echo esc_js( __( 'Supprimer cette entrée ?', 'sdi-mail-log' ) ); ?>');" style="color:#b32d2e;"><?php esc_html_e( 'Suppr.', 'sdi-mail-log' ); ?></a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>

		<?php
		// Pagination.
		$pages = (int) ceil( $total / $per_page );
		if ( $pages > 1 ) {
			$base = add_query_arg( array( 's' => $search, 'status' => $fstatus, 'paged' => '%#%' ), sdi_ml_base_url() );
			echo '<div class="tablenav"><div class="tablenav-pages" style="margin:12px 0;">';
			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => $base,
						'format'    => '',
						'current'   => $paged,
						'total'     => $pages,
						'prev_text' => '‹',
						'next_text' => '›',
					)
				)
			);
			echo '</div></div>';
		}
		?>

		<!-- Settings + maintenance -->
		<h2 style="margin-top:2em;"><?php esc_html_e( 'Réglages', 'sdi-mail-log' ); ?></h2>
		<form method="post" style="display:flex;gap:20px;flex-wrap:wrap;align-items:flex-end;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:16px 18px;max-width:760px;">
			<?php wp_nonce_field( 'sdi_ml_settings' ); ?>
			<label style="display:flex;flex-direction:column;gap:4px;font-weight:600;">
				<?php esc_html_e( 'Conserver les e-mails pendant (jours)', 'sdi-mail-log' ); ?>
				<input type="number" name="sdi_ml_retention" min="0" value="<?php echo esc_attr( $retention ); ?>" style="width:120px;">
				<span style="font-weight:400;color:#646970;font-size:12px;"><?php esc_html_e( '0 = conserver indéfiniment.', 'sdi-mail-log' ); ?></span>
			</label>
			<label style="display:flex;align-items:center;gap:8px;font-weight:600;">
				<input type="checkbox" name="sdi_ml_log_body" value="1" <?php checked( $log_body, 1 ); ?>>
				<?php esc_html_e( 'Journaliser le contenu (corps) des e-mails', 'sdi-mail-log' ); ?>
			</label>
			<?php submit_button( __( 'Enregistrer', 'sdi-mail-log' ), 'primary', 'sdi_ml_save_settings', false ); ?>
		</form>

		<form method="post" style="margin-top:14px;" onsubmit="return confirm('<?php echo esc_js( __( 'Vider tout le journal des e-mails ? Cette action est irréversible.', 'sdi-mail-log' ) ); ?>');">
			<?php wp_nonce_field( 'sdi_ml_clear' ); ?>
			<button type="submit" name="sdi_ml_clear" class="button button-link-delete"><?php esc_html_e( 'Vider tout le journal', 'sdi-mail-log' ); ?></button>
		</form>
	</div>
	<?php
}

/**
 * Render the detail panel for one logged e-mail.
 *
 * @param object $row Log row.
 */
function sdi_ml_render_detail( $row ) {
	$resend_url = wp_nonce_url( add_query_arg( array( 'sdi_action' => 'resend', 'id' => $row->id ), sdi_ml_base_url() ), 'sdi_ml_resend' );
	?>
	<div style="background:#fff;border:1px solid #c3c4c7;border-left:4px solid #2271b1;border-radius:6px;padding:16px 20px;margin:10px 0 20px;">
		<h2 style="margin:0 0 12px;"><?php echo esc_html( $row->subject ? $row->subject : '(sans objet)' ); ?></h2>
		<table class="form-table" style="margin:0;">
			<tr><th style="width:150px;padding:6px 0;"><?php esc_html_e( 'Date', 'sdi-mail-log' ); ?></th><td><?php echo esc_html( mysql2date( 'd/m/Y H:i:s', $row->created_at ) ); ?></td></tr>
			<tr><th style="padding:6px 0;"><?php esc_html_e( 'Destinataire', 'sdi-mail-log' ); ?></th><td><?php echo esc_html( $row->recipient ); ?></td></tr>
			<tr><th style="padding:6px 0;"><?php esc_html_e( 'Source', 'sdi-mail-log' ); ?></th><td><?php echo esc_html( $row->source ); ?></td></tr>
			<tr><th style="padding:6px 0;"><?php esc_html_e( 'Statut', 'sdi-mail-log' ); ?></th><td>
				<?php if ( 'failed' === $row->status ) : ?>
					<strong style="color:#b32d2e;"><?php esc_html_e( 'Échec', 'sdi-mail-log' ); ?></strong>
					<?php if ( $row->error ) : ?><br><span style="color:#b32d2e;"><?php echo esc_html( $row->error ); ?></span><?php endif; ?>
				<?php else : ?>
					<strong style="color:#1e7e34;"><?php esc_html_e( 'Envoyé', 'sdi-mail-log' ); ?></strong>
				<?php endif; ?>
			</td></tr>
			<?php if ( trim( (string) $row->headers ) !== '' ) : ?>
				<tr><th style="padding:6px 0;vertical-align:top;"><?php esc_html_e( 'En-têtes', 'sdi-mail-log' ); ?></th><td><code style="white-space:pre-wrap;"><?php echo esc_html( $row->headers ); ?></code></td></tr>
			<?php endif; ?>
		</table>
		<p style="margin:14px 0 6px;font-weight:600;"><?php esc_html_e( 'Contenu', 'sdi-mail-log' ); ?></p>
		<textarea readonly rows="12" style="width:100%;font-family:monospace;font-size:12px;background:#f6f7f7;"><?php echo esc_textarea( $row->body ); ?></textarea>
		<p style="margin-top:14px;">
			<a class="button button-primary" href="<?php echo esc_url( $resend_url ); ?>"><?php esc_html_e( 'Renvoyer cet e-mail', 'sdi-mail-log' ); ?></a>
			<a class="button" href="<?php echo esc_url( sdi_ml_base_url() ); ?>"><?php esc_html_e( 'Fermer', 'sdi-mail-log' ); ?></a>
		</p>
	</div>
	<?php
}
