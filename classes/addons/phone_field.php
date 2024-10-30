<?php

class Meow_Addons_Contact_Form_Phone_Field {

	public $core = null;

	public function __construct( $core ) {
    $this->core = $core;
    add_filter( 'mcfb_form_after_email', array( $this, 'after_email' ), 10, 5 );
    add_filter( 'mcfb_form_read_data', array( $this, 'form' ), 10, 1 );
    add_filter( 'mcfb_email_content', array( $this, 'email_content' ), 10, 2 );
		add_filter( 'mcfb_fill_template', array( $this, 'fill_template' ), 10, 2 );
	}

  // Add the phone field after the e-mail field
	function after_email( $html, $atts, $css, $reply, $formId ) {
    $disabled = ( $reply && $reply->success ) ? 'disabled' : '';
    $phone_label = isset( $atts['phone_label'] ) ? esc_html( $atts['phone_label'] ) : __( 'Phone', 'contact-form-block' );
		$html .= "<div class='{$css['group']}'>";
		$html .= "<label class='{$css['label']}' for='{$formId}__phone'>$phone_label</label>";
		$html .= "<input class='{$css['input']}' type='text' name='mcfb_phone' id='{$formId}__phone' value='" . 
			( isset( $reply->form->phone ) ? esc_html( $reply->form->phone ) : '' ) . "' $disabled></input>";
    $html .= "</div>";
    return $html;
  }
  
  // Get the value from the form
  function form( $form ) {
    $form['phone'] = sanitize_text_field( $_POST['mcfb_phone'] );
    return $form;
  }

  // Add the phone in the template right after the e-mail
  function email_content( $content, $form ) {
    return str_replace( "E-mail: *email*", "E-mail: *email*\r\nPhone: *phone*", $content );
  }

  // Add the phone in the template right after the e-mail
  function fill_template( $content, $form ) {
    return str_replace( '*phone*', $form['phone'], $content );
  }

}

?>
