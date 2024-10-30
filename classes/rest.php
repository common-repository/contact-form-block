<?php

class Meow_Contact_Form_Rest
{
	private $core = null;
	private $namespace = 'contact-form-block/v1';
	private $admin = null;

	public function __construct( $core, $admin ) {
		if ( !current_user_can( 'administrator' ) ) {
			return;
		} 
		$this->core = $core;
		$this->admin = $admin;
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	function rest_api_init() {
		try {
			// SETTINGS
			register_rest_route( $this->namespace, '/update_option', array(
				'methods' => 'POST',
				'callback' => array( $this, 'rest_update_option' )
			) );
			register_rest_route( $this->namespace, '/all_settings', array(
				'methods' => 'GET',
				'callback' => array( $this, 'rest_all_settings' ),
			) );
		}
		catch (Exception $e) {
			var_dump($e);
		}
	}

	function rest_all_settings() {
		return new WP_REST_Response( [
			'success' => true,
			'data' => $this->admin->get_all_options()
		], 200 );
	}

	function rest_update_option( $request ) {
		$params = $request->get_json_params();
		try {
			$name = $params['name'];
			$value = is_bool( $params['value'] ) ? ( $params['value'] ? '1' : '' ) : $params['value'];
			$success = update_option( $name, $value );
			if ( $success ) {
				$res = $this->validate_updated_option( $name );
				$result = $res['result'];
				$message = $res['message'];
				return new WP_REST_Response([ 'success' => $result, 'message' => $message ], 200 );
			}
			return new WP_REST_Response([ 'success' => false, 'message' => "Could not update option." ], 200 );
		} 
		catch (Exception $e) {
			return new WP_REST_Response([
				'success' => false,
				'message' => $e->getMessage(),
			], 500 );
		}
	}

	function validate_updated_option( $option_name ) {
		$mcfb_phone_field = get_option( 'mcfb_phone_field', false );
		if ( $mcfb_phone_field === '' )
			update_option( 'mcfb_phone_field', false );
		return $this->createValidationResult();
	}

	function createValidationResult( $result = true, $message = null) {
		$message = $message ? $message : __( 'OK', 'meow-plugin' );
		return ['result' => $result, 'message' => $message];
	}
}
