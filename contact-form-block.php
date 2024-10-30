<?php
/*
Plugin Name: Contact Form Block
Plugin URI: https://meowapps.com
Description: An easy, ultra-simple contact form.
Version: 1.0.2
Author: Jordy Meow
Author URI: https://jordymeow.com
Text Domain: contact-form-block
Domain Path: /languages

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html

Originally developed for two of my websites:
- Jordy Meow (https://offbeatjapan.org)
- Haikyo (https://haikyo.org)
*/

define( 'MCFB_VERSION', '1.0.2' );
define( 'MCFB_PREFIX', 'mcfb' );
define( 'MCFB_DOMAIN', 'contact-form-block' );
define( 'MCFB_ENTRY', __FILE__ );
define( 'MCFB_PATH', dirname( __FILE__ ) );
define( 'MCFB_URL', plugin_dir_url( __FILE__ ) );

require_once( 'classes/init.php' );

?>
