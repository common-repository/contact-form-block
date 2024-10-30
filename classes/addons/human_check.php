<?php

add_filter( 'mcfb_form_after_email', 'my_human_check_after_email', 10, 4 );
add_filter( 'mcfb_validate', 'my_human_check_validate', 10, 2 );

// Add the human check field after the e-mail field
function my_human_check_after_email( $html, $atts, $css, $reply ) {
	if ( $reply && $reply->success )
		return $html;
	$html .= "<div class='{$css['group']}'>";
	$html .= "<label class='{$css['label']}' for='mcfb_are_you_human'>What color was the white horse of Henry IV?</label>";
	$html .= "<input class='{$css['input']}' type='text' name='mcfb_are_you_human' value=''></input>";
	$html .= "</div>";
	return $html;
}

// Check if the field was properly filled in
function my_human_check_validate( $error, $form ) {
	$mcfb_are_you_human = trim( strtolower( sanitize_text_field( $_POST['mcfb_are_you_human'] ) ) );
	if ( $mcfb_are_you_human === 'white' )
		return null;
	return __( 'You failed to reply to the question. Are you really human?', 'contact-form-block' );
}

?>
