<?php

class Meow_Contact_Form_Core {

	public $admin = null;
	public $is_rest = false;
	public $is_cli = false;
	public $site_url = null;
	public $use_admin_post = false;

	public function __construct() {
		$this->site_url = get_site_url();
		$this->is_rest = MeowCommon_Helpers::is_rest();
		$this->is_cli = defined( 'WP_CLI' ) && WP_CLI;

		// Support for Captcha
		if ( get_option( 'mcfb_captcha_secret_key', null ) ) {
			new Meow_Addons_Contact_Form_ReCAPTCHA_v3( $this );
		}

		// Support of the Phone field
		if ( get_option( 'mcfb_phone_field', false ) ) {
			new Meow_Addons_Contact_Form_Phone_Field( $this );
		}

		// Use the admin-post to receive forms (instead of having it managed manually by the init)
		if ( $this->use_admin_post ) {
			add_action( 'admin_post_nopriv_meow_sends_mail', array( $this, 'submit_form' ) );
			add_action( 'admin_post_meow_sends_mail', array( $this, 'submit_form' ) );
		}

		add_action( 'plugins_loaded', array( $this, 'loaded' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_mail_failed', array( $this, 'mail_failed' ), 10, 1 );

		// To load the CSS (instead of having it inline)
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	function loaded() {
		load_plugin_textdomain( 'contact-form-block', FALSE, basename( MCFB_PATH ) . '/languages/' );

		// Part of the core, settings and stuff
		$this->admin = new Meow_Contact_Form_Admin();

		// Only for REST
		if ( $this->is_rest ) {
			new Meow_Contact_Form_Rest( $this, $this->admin );
		}
	}

	function init() {

		// Do not use the admin-post to receive forms
		if ( !$this->use_admin_post && isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'meow_sends_mail' ) {
				$this->submit_form();
				exit;
			}
		}

		if ( is_admin() ) {
			// Load the Gutenberg block for the admin side
			new Meow_Contact_Form_Blocks( $this );
		}
		else if ( !( $this->is_rest() ) ) {
			// Load the front-end for visitors
			new Meow_Contact_Form_Run( $this );
		}
	}

	function is_rest() {
		if ( empty( $_SERVER[ 'REQUEST_URI' ] ) )
			return false;
		$rest_prefix = trailingslashit( rest_get_url_prefix() );
		return strpos( $_SERVER[ 'REQUEST_URI' ], $rest_prefix ) !== false ? true : false;
	}

	function atts( $atts ) {
		$atts = empty( $atts ) ? array() : $atts;
		return array_merge( array(
			'name_label' => 'Name',
			'email_label' => 'E-mail',
			'message_label' => 'Message',
			'header' => 'false',
			'header_image_id' => null,
			'header_image_size' => 50,
			'header_image_align' => 'left',
			'header_title' => 'Get in Touch',
			'header_text' => 'Leave your message and we\'ll get back to you shortly.',
			'button_text' => 'Send',
			'button_color' => '#0095ff',
			'button_text_color' => 'white',
			'align' => '',
			'theme' => 'default',
			'message_rows' => 5
		), $atts );
	}

	public function submit_form() {
		//wp_verify_nonce( $nonce_name, $nonce_action )
		$error = null;
		$success = false;

		// The complete form
		$form = array( 
			'to' => get_option( 'admin_email' ), 
			'from' => sanitize_email( $_POST['mcfb_email'] ), 
			'name' => sanitize_text_field( $_POST['mcfb_name'] ), 
			'message' => sanitize_textarea_field( stripslashes( $_POST['mcfb_message'] ) ) 
		);

		// Prepare form data
		$form['to'] = sanitize_email( apply_filters( 'mcfb_email_to', $form['to'], $form  ) );
		$form = apply_filters( 'mcfb_form_read_data', $form );
		
		// Validate form data
		if ( !filter_var( $form['from'], FILTER_VALIDATE_EMAIL ) ) {
			$error = __( "The e-mail (from) is not valid.", 'contact-form-block' );
		}
		else if ( !filter_var( $form['to'], FILTER_VALIDATE_EMAIL ) ) {
			$error = __( "The e-mail (to) is not valid.", 'contact-form-block' );
		}
		$error = apply_filters( 'mcfb_validate', $error, $form );
		// $error = "Wait for a few minutes before trying again. This security avoid spammers to use Meow Apps as a tool. This shows you also that security and custom validation is easy to implement.";

		// Prepare email
		if ( empty( $error ) ) {

			// Prepare templates
			$subject = "[*site*] Message from *name*";
			$content = "Name: *name*\r\nE-mail: *email*\r\n\r\n*message*";
			$subject = apply_filters( 'mcfb_email_subject', $subject, $form );
			$content = apply_filters( 'mcfb_email_content', $content, $form );

			// Fill the template
			$site_name = get_bloginfo( 'name' );
			$subject = str_replace( '*name*', $form['name'], $subject );
			$subject = str_replace( '*email*', $form['from'], $subject );
			$subject = str_replace( '*site*', $site_name, $subject );
			$content = str_replace( '*name*', $form['name'], $content );
			$content = str_replace( '*email*', $form['from'], $content );
			$content = str_replace( '*site*', $site_name, $content );
			$content = str_replace( '*message*', $form['message'], $content );
			$content = apply_filters( 'mcfb_fill_template', $content, $form );

			// Send the email
			$success = wp_mail( $form['to'], $subject, $content, 
				array( "Reply-To: {$form['name']} <{$form['from']}>" ) );
			do_action( 'mcfb_form_sent', $form );
			//$this->log( "From ${form['from']} to ${form['to']}: ${subject}." );
		}

		// Final data for this form
		$data = array( 'success' => $success, 'error' => $error, 'form' => $form );

		// If Success and URL Redirect, then redirect
		$redirect_url = apply_filters( 'mcfb_redirect_url', get_option( 'mcfb_redirect_url', '' ), $success, $error, $form );

		if ( $success && !empty( $redirect_url ) ) {
			wp_safe_redirect( $redirect_url );
			return;
		}

		// Prepare an ID associated with a transcient for the original page
		$id = uniqid();
		set_transient( "mcfb_{$id}", json_encode( $data ), 60 );
		$url = esc_url( add_query_arg( 'mcfb_redirect', $id, $_SERVER["HTTP_REFERER"] ) );
		wp_safe_redirect( $url );
	}

	function mail_failed( $wp_error ) {
		error_log( print_r( $wp_error, 1 ) );
	}

	function log( $data ) {
		try {
			if ( is_writable( MCFB_PATH ) ) {
				$fh = fopen( trailingslashit( MCFB_PATH ) . 'contact-form-block.log', 'a' );
				$date = date( "Y-m-d H:i:s" );
				if ( !is_string( $data ) )
					$data = print_r( $data, 1 );
				fwrite( $fh, "$date: {$data}\n" );
				fclose( $fh );
			}
			else {
				error_log( 'Cannot create or write the logs for Contact Form Block.' );
			}
		}
		catch ( Exception $e ) {
			error_log( 'Cannot create or write the logs for Contact Form Block.' );
		}
	}

	static function installed() {
		return true;
	}

}

?>
