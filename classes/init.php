<?php

spl_autoload_register(function ( $class ) {
  $necessary = true;
  $file = null;
  if ( strpos( $class, 'Meow_Contact_Form' ) !== false ) {
    $file = MCFB_PATH . '/classes/' . str_replace( 'meow_contact_form_', '', strtolower( $class ) ) . '.php';
  }
  else if ( strpos( $class, 'Meow_Addons_Contact_Form' ) !== false ) {
    $file = MCFB_PATH . '/classes/addons/' . str_replace( 'meow_addons_contact_form_', '', strtolower( $class ) ) . '.php';
  }
  else if ( strpos( $class, 'MeowCommon_' ) !== false ) {
    $file = MCFB_PATH . '/common/' . str_replace( 'meowcommon_', '', strtolower( $class ) ) . '.php';
  }
  else if ( strpos( $class, 'MeowPro_Meow_Contact_Form' ) !== false ) {
    $necessary = false;
    $file = MCFB_PATH . '/premium/' . str_replace( 'meowpro_meow_contact_form_', '', strtolower( $class ) ) . '.php';
  }
  if ( $file ) {
    if ( !$necessary && !file_exists( $file ) ) {
      return;
    }
    require( $file );
  }
});

//require_once( MCFB_PATH . '/classes/api.php');
require_once( MCFB_PATH . '/common/helpers.php');

// In admin or Rest API request (REQUEST URI begins with '/wp-json/')
new Meow_Contact_Form_Core();

?>