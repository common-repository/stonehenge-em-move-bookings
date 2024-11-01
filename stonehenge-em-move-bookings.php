<?php
/**
 * Events Manager - Move Bookings
 *
 * @package           Events Manager - Move Bookings
 * @author            Stonehenge Creations <support@stonehengecreation.nl>
 * @copyright         2022 Stonehenge Creations
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Events Manager - Move Bookings
 * Plugin URI:        https://wordpress.org/plugins/stonehenge-em-move-bookings/
 * Description:       Moves an upcoming Booking to different upcoming Event in Events Manager with a simple select dropdown.
 * Text Domain:       stonehenge-em-move-bookings
 * Version:           2.0.2
 * Requires at least: 5.5
 * Tested up to:      5.9
 * Requires PHP:      7.3
 * Tested up to PHP:  8.0.15
 * Author:            Stonehenge Creations
 * Author URI:        https://www.stonehengecreations.nl/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Donate Link:       https://paymentlink.mollie.com/payment/x7dNYfFAWy6rN6G42PFkv/
 */

// Exit if accessed directly.
if( !defined( 'WPINC' ) ) exit;

class Stonehenge_EM_Move_Bookings {

	/**
	 * @var string $file The plugin file
	 */
	var $file;

	/**
	 * @var string $slug The plugin slug
	 */
	var $slug;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->file = plugin_basename( __FILE__ );
		$this->slug = dirname( $this->file );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10 , 2 );

		if( is_plugin_active( 'events-manager/events-manager.php' ) ) {
			add_action( 'em_bookings_admin_booking_event', array( $this, 'render_move_booking_form' ), 1, 1 );
			add_action( 'admin_init', array( $this, 'process_move_booking_form' ) );
		}
	}

	/**
	 * Load the plugin translations.
	 */
	public function load_plugin_textdomain() {
		$locale = determine_locale();

		load_default_textdomain( $locale );

		if( !load_textdomain( $this->slug, sprintf( '%1$s/%2$s/languages/%2$s-%3$s.mo', WP_PLUGIN_DIR, $this->slug, $locale ) ) )
			load_plugin_textdomain( $this->slug, false, '/languages' );
	}

	/**
	 * Adds addtional links in the WordPress Plugins Page.
	 *
	 * @param  array  $links Plugin links
	 * @param  string $file  Plugin file
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if( $this->file === $file ) {
			$links['reviews'] = sprintf( '<a href="https://wordpress.org/support/plugin/%s/reviews/#new-post" target="_blank">%s</a>', $this->slug, esc_html( __wp( 'Reviews' ) ) );
			$links['support'] = sprintf( '<a href="https://wordpress.org/support/plugins/%s" target="_blank">Plugin %s</a>', $this->slug, esc_html( __wp( 'Support' ) ) );
			$links['donate']  = sprintf( '<a href="https://paymentlink.mollie.com/payment/x7dNYfFAWy6rN6G42PFkv/" target="_blank">%s</a>', esc_html( __wp( 'Donate to this plugin &#187;' ) ) );
		}

		return $links;
	}

	/**
	 * Renders the Move Bookings From.
	 *
	 * @param mixed $EM_Event EM_Event Object.
	 */
	public function render_move_booking_form( $EM_Event ) {
		if( !isset( $_GET['booking_id'] ) )
			return;

		if( isset( $_GET['booking_moved'] ) ) {
			$result  = (bool) sanitize_text_field( $_GET['booking_moved'] );
			$message = $result ? sprintf( '%s<br>%s', esc_html__( 'The booking has been moved successfully.', $this->slug ), esc_html__( 'Remember to notify to your customer accordingly.', $this->slug ) ) : __wp( 'An error occurred. Please try again.' );

			echo sprintf( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $result ? 'success' : 'error', $message );
		}

		global $EM_Booking;

		$current_id  = (int) $EM_Event->event_id;
		$booking_id  = (int) $EM_Booking->booking_id;
		$EM_Events 	 = EM_Events::get( array( 'scope' => 'future', 'bookings' => true, 'orderby' => 'event_start_date,event_start_time' ) );
		$count 		 = count( (array) $EM_Events );
 		$overbooking = get_option( 'dbem_bookings_approval_overbooking' );

		?>
			</div>
		</div>
		<div class="stuffbox">
			<h3><?php esc_html_e( 'Move Booking', $this->slug ); ?></h3>
			<div class="inside" style="padding:10px;">
				<form action="" method="post" id="em_move_booking">
					<?php wp_nonce_field( 'em_move_booking', 'em_move_booking_nonce' ); ?>
					<input type="hidden" name="em_move_booking_id" value="<?php echo esc_attr( $booking_id ); ?>">
					<input type="hidden" name="em_move_booking_from" value="<?php echo esc_attr( $current_id ); ?>">
					<?php $overbooking ? esc_html_e( 'Because manual overbooking is allowed, the current ticket availability for upcoming events will not be checked.', $this->slug ) : esc_html_e( 'Because manual overbooking is not allowed, sold-out events will not be shown.', $this->slug ); ?>
					<table>
						<tr>
							<th scope="row"><?php esc_html_e( 'Move to:' , $this->slug ); ?></th>
							<td>
								<select name="em_move_booking_to" <?php echo $count < 2 ? 'disabled="disabled"' : null; ?> required>

									<?php if( $count > 1 ) {
										echo sprintf( '<option value="" disabled="disabled" selected="selected">- %s -</option>', esc_html( __wp( 'Select' ) ) );

										// Hide the current event to prevent use confusion and only show available events with enough spaces or when manual overbooking is allowed.
										foreach( $EM_Events as $EM_Event ) {
											if( $current_id !== (int) $EM_Event->event_id && ( $EM_Event->get_bookings()->get_available_spaces() >= $EM_Booking->booking_spaces || $overbooking ) ) {
												$name = htmlentities( $EM_Event->output( "#_EVENTDATES @ #_EVENTTIMES | #_EVENTNAME" ), ENT_QUOTES );
												echo sprintf( '<option value="%d">%s</option>', (int) esc_attr( $EM_Event->event_id ), $name );
											}
										}
									} else {
										echo sprintf( '<option>%s</option>', esc_html( __em( 'No Events Found' ) ) );
									} ?>
								</select>
							</td>
							<td><?php if( $count > 1 ) submit_button( esc_attr__( 'Move Booking', $this->slug ), 'primary', 'submit_move', false ); ?> </td>

						</tr>
					</table>
				</form>
			</div>
		</div>
		<div id="post-body-content">
			<div class="stuffbox">
		<?php
	}

	/**
	 * Handles the Move Bookings Form submission.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public function process_move_booking_form() {
		if( isset( $_POST['em_move_booking_nonce'] ) && wp_verify_nonce( $_POST['em_move_booking_nonce'], 'em_move_booking' ) ) {

			global $wpdb;
			$table 	 	= EM_BOOKINGS_TABLE;
			$booking_id = (int) sanitize_key( $_POST['em_move_booking_id'] );
			$old_id 	= (int) sanitize_key( $_POST['em_move_booking_from'] );
			$event_id 	= (int) sanitize_key( $_POST['em_move_booking_to'] );
			$result 	= $wpdb->query( "UPDATE `{$table}` SET `event_id` = '{$event_id}' WHERE `booking_id` = '{$booking_id}'" ) ? true : false;
			$redirect 	= add_query_arg( array( 'post_type' => 'event', 'page' => 'events-manager-bookings', 'event_id' => $result ? $event_id : $old_id, 'booking_id' => $booking_id, 'booking_moved' => $result ), admin_url( 'edit.php' ) );

			wp_safe_redirect( $redirect );
			exit();
		}
		return;
	}

}

/**
 * @ignore
 */
if( !function_exists( '__wp' ) ) {
	function __wp( $string ) {
		return translate( $string, 'default' );
	}
}

/**
 * @ignore
 */
if( !function_exists( '__em' ) ) {
	function __em( $string ) {
		return translate( $string, 'events-manager' );
	}
}

new Stonehenge_EM_Move_Bookings();
