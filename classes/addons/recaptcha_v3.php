<?php

class Meow_Addons_Contact_Form_ReCAPTCHA_v3 {

	public $core = null;

	public function __construct( $core ) {
		$this->core = $core;
		add_filter( 'mcfb_form_info', array( $this, 'info' ), 10, 4 );
		add_filter( 'mcfb_form_html', array( $this, 'inject' ), 10, 4 );
		add_filter( 'mcfb_validate', array( $this, 'validate' ), 10, 2 );
	}

	function info( $html, $atts, $formId, $success ) {
		if ( $success )
			return $html;
		$html = __( 'This site is protected by reCAPTCHA and the Google <a target="_blank" href="https://policies.google.com/privacy">Privacy Policy</a> and <a target="_blank" href="https://policies.google.com/terms">Terms of Service</a> apply.', 'contact-form-block' );
		return $html;
	}

	function inject( $html, $atts, $formId, $success ) {
		if ( $success )
			return $html;
		$key = get_option( 'mcfb_captcha_key', null );
		$js = "
			<script>
				const captcha_script = document.createElement('script');
				captcha_script.src = 'https://www.google.com/recaptcha/api.js?render=$key';

				captcha_script.addEventListener('load', () => {
					grecaptcha.ready(function() {
						grecaptcha.execute('$key', {action: 'login'}).then(function(token) {
							var tokenfield = document.getElementById('$formId').querySelectorAll('[name=mcfb_token]');
							if (!tokenfield) {
								console.log('The Contact Form Block will not be working, the token field could not be found.');
								return;
							}
							tokenfield[0].setAttribute('value', token);
						});
					});
				});

				document.body.appendChild(captcha_script);				
			</script>";
		$html = $js . $html;
		return $html;
	}

	function validate( $error, $form ) {
		$secret = get_option( 'mcfb_captcha_secret_key' );
		if ( empty( $secret ) ) {
			return __( 'ReCaptcha v3 is not properly enabled (secret is not set).', 'contact-form-block' );
		}
		$token = isset( $_POST['mcfb_token'] ) ? $_POST['mcfb_token'] : '';
		if ( empty( $token ) ) {
			return __( 'ReCaptcha v3 is not properly enabled (token is missing).', 'contact-form-block' );
		}
		// $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
		// $recaptcha = file_get_contents( $recaptcha_url . '?secret=' . $secret . '&response=' . $token );
    	// $recaptcha = json_decode( $recaptcha );
		// Replace with Wordpress HTTP API

		$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
		$response = wp_remote_post( $recaptcha_url, array(
			'method' => 'POST',
			'body' => array(
				'secret' => $secret,
				'response' => $token
			)
		) );
		if ( is_wp_error( $response ) ) {
			return __( 'ReCaptcha v3 is not properly enabled (token is missing).', 'contact-form-block' );
		}
		$recaptcha = json_decode( wp_remote_retrieve_body( $response ) );

		if ( property_exists( $recaptcha, 'score' ) ) {
			if ( $recaptcha->score >= 0.5 ) {
				return $error;
			}
		}
		else {
			error_log( "The 'score' property was not returned by Google ReCaptcha. This is what was received instead: " . print_r( $recaptcha, true ) );
		}

		return __( 'To avoid spam and abuse, your message was not sent. We are truly sorry if it is a mistake, and in that case, please try to submit this form again.', 'contact-form-block' );
	}

}

?>
