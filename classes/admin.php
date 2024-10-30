<?php

class Meow_Contact_Form_Admin extends MeowCommon_Admin {

	public function __construct() {
		parent::__construct( MCFB_PREFIX, MCFB_ENTRY, MCFB_DOMAIN, false, false );
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'app_menu' ) );

			// Load the scripts only if they are needed by the current screen
			$page = isset( $_GET["page"] ) ? $_GET["page"] : null;
			$is_mcfb_screen = in_array( $page, [ 'mcfb_settings-menu' ] );
			$is_meowapps_dashboard = $page === 'meowapps-main-menu';
			if ( $is_meowapps_dashboard || $is_mcfb_screen ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			}
		}
	}

	function admin_enqueue_scripts() {

		// Load the scripts
		$physical_file = MCFB_PATH . '/app/index.js';
		$cache_buster = file_exists( $physical_file ) ? filemtime( $physical_file ) : MCFB_VERSION;
		wp_register_script( 'mcfb_contact_form_block-vendor', MCFB_URL . 'app/vendor.js',
			['wp-element', 'wp-i18n'], $cache_buster
		);
		wp_register_script( 'mcfb_contact_form_block', MCFB_URL . 'app/index.js',
			['mcfb_contact_form_block-vendor', 'wp-i18n'], $cache_buster
		);
		wp_set_script_translations( 'mcfb_contact_form_block', 'meow-plugin' );
		wp_enqueue_script('mcfb_contact_form_block' );

		// Load the fonts
		wp_register_style( 'meow-neko-ui-lato-font', '//fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap');
		wp_enqueue_style( 'meow-neko-ui-lato-font' );

		// Localize and options
		wp_localize_script( 'mcfb_contact_form_block', 'mcfb_contact_form_block', array_merge( [
			'api_url' => rest_url( 'contact-form-block/v1' ),
			'rest_url' => rest_url(),
			'plugin_url' => MCFB_URL,
			'prefix' => MCFB_PREFIX,
			'domain' => MCFB_DOMAIN,
			'is_pro' => class_exists( 'MeowPro_Contact_Form_Core' ),
			'is_registered' => !!$this->is_registered(),
			'rest_nonce' => wp_create_nonce( 'wp_rest' )
		], $this->get_all_options() ) );
	}

	function get_all_options() {
		return [
			'mcfb_captcha_key' => get_option( 'mcfb_captcha_key', '' ),
			'mcfb_captcha_secret_key' => get_option( 'mcfb_captcha_secret_key', '' ),
			'mcfb_redirect_url' => get_option( 'mcfb_redirect_url', '' ),
			'mcfb_phone_field' => get_option( 'mcfb_phone_field', false ),
		];
	}

	function app_menu() {
		// SUBMENU > Settings
		add_submenu_page( 'meowapps-main-menu', 'Contact Form', 'Contact Form', 'manage_options',
			'mcfb_settings-menu', array( $this, 'admin_settings' ) );
	}

	function admin_settings() {
		echo '<div id="mcfb-admin-settings"></div>';
	}
}

?>
