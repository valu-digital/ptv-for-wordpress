<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PTV In module
 */
class PTV_In_Module {

	/**
	 * Class instance.
	 *
	 * @var PTV_In_Module
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return PTV_In_Module
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * PTV_In_Module constructor.
	 */
	private function __construct() {

		$this->includes();

		add_action( 'save_post', array( $this, 'save_post' ), PHP_INT_MAX, 2 );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

	}

	/**
	 * Load module specific files.
	 */
	public function includes() {
		if ( is_admin() ) {
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-service-in-controller.php' );
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-echannel-in-controller.php' );
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-phone-channel-in-controller.php' );
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-printable-form-channel-in-controller.php' );
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-service-location-channel-in-controller.php' );
			require_once( PTV_FOR_WORDPRESS_DIR . '/lib/in/class-ptv-web-page-channel-in-controller.php' );
		}
	}

	/**
	 * Call post type specific controller on post save.
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function save_post( $post_id, $post ) {

		$post_type = get_post_type( $post_id );

		switch ( $post_type ) {
			case 'ptv-service':
				$controller = new PTV_Service_In_Controller();
				break;
			case 'ptv-echannel':
				$controller = new PTV_EChannel_In_Controller();
				break;
			case 'ptv-phone':
				$controller = new PTV_Phone_Channel_In_Controller();
				break;
			case 'ptv-printable-form':
				$controller = new PTV_Printable_Form_Channel_In_Controller();
				break;
			case 'ptv-service-location':
				$controller = new PTV_Service_Location_Channel_In_Controller();
				break;
			case 'ptv-web-page':
				$controller = new PTV_Web_Page_Channel_In_Controller();
				break;
			default:
				return;
		}

		$controller->save( $post->ID );

	}


	/**
	 * Show admin notices.
	 */
	public function admin_notices() {

		global $post;

		if ( ! isset( $post->ID ) ) {
			return;
		}

		$transient_key = sprintf( 'ptv_error_messages_%d_%d', get_current_user_id(), $post->ID );

		$errors = get_transient( $transient_key );

		if ( $errors ) : ?>

			<div class="error">
			<?php foreach ( $errors->get_error_codes() as $code ) : ?>
				<p>
					<?php
					echo esc_html( $errors->get_error_message( $code ) );
					?>
				</p>
				<?php

				$error_data = $errors->get_error_data( $code );
				if ( $error_data ) :
					if ( isset( $error_data['data'] ) && ! empty( $error_data['data'] ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Error details:', 'ptv-for-wordpress' ); ?></strong>
							<small>
								<ul>
									<?php foreach ( $error_data['data'] as $key => $message ) :
										$message = is_array( $message ) ? $message[0] : $message;
										?>
										<li><?php printf( '%s: %s', esc_html( $key ), esc_html( $message ) ); ?></li>
									<?php endforeach; ?>
								</ul>
							</small>
						</p>
					<?php endif; ?>

				<?php endif; ?>
			<?php endforeach; ?>
			</div><?php

			delete_transient( $transient_key );
		endif;

	}

}


PTV_In_Module::get_instance();
