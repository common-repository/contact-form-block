<?php

class Meow_Contact_Form_Run {

	public $admin = null;
	public $core = null;

	public function __construct( $core ) {
		$this->core = $core;
		add_shortcode( 'contact-form-block', array( $this, 'form' ) );
	}

	function display_css() {
		$options = array(
			"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => false,
			)
		);
		$cssfile = MCFB_PATH . '/css/contact-form-block.min.css';
		$css = "<style>";
		$css .= file_get_contents( $cssfile, false, stream_context_create( $options ) );
		$css .= "</style>";
		return $css;
	}

	function form( $atts ) {
		$reply = null;
		$atts = apply_filters( 'mcfb_atts', $this->core->atts( $atts ), null, $atts );
		
		$css = $this->display_css();

		// Get the reply
		if ( array_key_exists( 'mcfb_redirect', $_GET ) ) {
			$reply = json_decode( get_transient( "mcfb_{$_GET['mcfb_redirect']}" ) );
			delete_transient( "mcfb_{$_GET['mcfb_redirect']}" );
		}

		// Create the form
		$html = $css . $this->create_form( $atts, $reply );
		return $html;
	}

	function create_image( $header_image_id, $size, $class ) {
		return wp_get_attachment_image( $header_image_id, 'medium', '', array(
			'class' => $class,
			'style' => "width: ${size}px; height: ${size}px;"
		) );
	}

	function create_form( $atts, $reply = null ) {
		$html = "";
		$success = $reply && $reply->success;

		// Form ID
		$formId = uniqid('mcfb_');
		
		// Attributes
		$name_label = esc_html( $atts['name_label'] );
		$email_label = esc_html( $atts['email_label'] );
		$message_label = esc_html( $atts['message_label'] );
		$header = esc_attr( $atts['header'] );
		$header_image_id = esc_attr( $atts['header_image_id'] );
		$header_image_size = esc_attr( $atts['header_image_size'] );
		$header_image_align = esc_attr( $atts['header_image_align'] );
		$header_title = esc_html( $atts['header_title'] );
		$header_text = esc_html( $atts['header_text'] );
		$button_text = esc_html( $atts['button_text'] );
		$button_color = esc_attr( $atts['button_color'] );
		$button_text_color = esc_attr( $atts['button_text_color'] );
		$align = esc_html( $atts['align'] );
		$theme = esc_html( $atts['theme'] );
		$message_rows = esc_html( $atts['message_rows'] );

		// CSS classes
		$bCSS = "meow-contact-form";
		$css = array(
			'root' => $bCSS,
			'theme' => "meow-contact-form--${theme}",
			'group' => "${bCSS}__group",
			'info' => "${bCSS}__info",
			'label' => "${bCSS}__group-label",
			'input' => "${bCSS}__group-input",
			'textarea' => "${bCSS}__group-textarea",
			'button' => "${bCSS}__group-button"
		);

		// Form
		$action = $this->core->use_admin_post ? esc_url( admin_url( 'admin-post.php' ) ) : '';
		$html .= "<form id='{$formId}' method='post' action='{$action}' class='{$css['root']} {$css['theme']}'>";

		// Header
		if ( $header === 'true' ) {
			$html .= "<div class='${bCSS}__header'>";
			if ( $header_image_align === 'left' ) {
				$html .= $this->create_image( $header_image_id, $header_image_size,
					$bCSS . '__header--image ' . $bCSS . '__header--image--left' );
			}
			$html .= "<div class='${bCSS}__header__content'>";
			$html .= "<div class='${bCSS}__header__content--title'>${header_title}</div>";
			$html .= "<div class='${bCSS}__header__content--text'>${header_text}</div>";
			$html .= "</div>";
			if ( $header_image_align === 'right' ) {
				$html .= $this->create_image( $header_image_id, $header_image_size,
					$bCSS . '__header--image ' . $bCSS . '__header--image--right' );
			}
			$html .= "</div>";
		}

		// There is a reply from the server
		if ( $reply ) {
			if ( $reply->success ) {
				$html .= "<div class='meow-contact-form-message'>";
				$html .= __( "Your message was sent successfully. Thank you!", 'contact-form-block' );
				$html .= "</div>";
			}
			else {
				$html .= "<div class='meow-contact-form-message meow-contact-form-message--error'>";
				$html .= !empty( $reply->error ) ? $reply->error : __( 'An error occurred.', 'contact-form-block' );
				$html .= "</div>";
			}
		}

		$disabled = $success ? 'disabled' : '';

		// Name Group
		$html .= "<div class='{$css['group']}'>";
		$html .= "<label class='{$css['label']}' for='{$formId}__name'>$name_label</label>";
		$html .= "<input class='{$css['input']}' type='text' name='mcfb_name' id='{$formId}__name' value='" . 
			( isset( $reply->form->name ) ? esc_html( $reply->form->name ) : '' ) . "' required $disabled></input>";
		$html .= "</div>";

		$html .= apply_filters( 'mcfb_form_after_name', '', $atts, $css, $reply );

		// Email Group
		$html .= "<div class='{$css['group']}'>";
		$html .= "<label class='{$css['label']}' for='{$formId}__email'>$email_label</label>";
		$html .= "<input class='{$css['input']}' type='email' name='mcfb_email' id='{$formId}__email' value='" . 
			( isset( $reply->form->from ) ? esc_html( $reply->form->from ) : '' ) . "' required $disabled></input>";
		$html .= "</div>";

		$html .= apply_filters( 'mcfb_form_after_email', '', $atts, $css, $reply, $formId );

		// Message Group
		$html .= "<div class='{$css['group']}'>";
		$html .= "<label class='{$css['label']}' for='{$formId}__message'>$message_label</label>";
		$html .= "<textarea class='{$css['textarea']}' name='mcfb_message' id='{$formId}__message' 
			rows='$message_rows' required $disabled>" . 
			( isset( $reply->form->message ) ? esc_textarea( $reply->form->message ) : '' ) . "</textarea>";
		$html .= "</div>";

		$html .= apply_filters( 'mcfb_form_after_message', '', $atts, $css, $reply );

		// Message Before Submit
		$info = apply_filters( 'mcfb_form_info', '', $atts, $formId, $success );
		if ( !empty( $info ) ) {
			$html .= "<p class='{$css['info']}'>";
			$html .= $info;
			$html .= "</p>";
		}

		// Submit Button
		if ( !$success ) {
			$html .= "<input type='hidden' name='action' value='meow_sends_mail'>";
			$html .= "<input type='hidden' name='mcfb_token' value='*need_token*'>";
			$html .= "<button id='{$formId}__submit' class='{$css['input']} {$css['button']}' type='submit' style='background: $button_color; color: $button_text_color;' value='$button_text' $disabled>$button_text</button>";
		}

		// End: Form
		$html .= "</form>";
		$html = apply_filters( 'mcfb_form_html', $html, $atts, $formId, $success );
		
		// Clean HTML and JS
		// $html = preg_replace( "/\r|\n/", "", $html );
		// $html = preg_replace( "/\s+/", " ", $html );
		
		return $html;
	}

}

?>
