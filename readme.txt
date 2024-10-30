=== Contact Form Block ===
Contributors: TigrouMeow
Tags: contact, form, gutenberg, block, mail, captcha, meowapps
Donate link: https://www.patreon.com/meowapps
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.2

Tired of those heavy and old contact forms? Try this one. Simple, yet modern, pretty and extremely optimized. No JS or CSS files are loaded.

== Description ==

Simple, yet pretty and perfect for most of us. You will love this contact form! Through its Gutenberg block (or shortcode), you can add it anywhere and make it yours in a few clicks. Lightweight, clean UI, no need to set up anything special or download more plugins. It doesn't use any JS or CSS files (except if you active ReCAPTCHA). You can find more information on this plugin on [Meow Apps: Contact Form Block](https://meowapps.com/contact-form-block/).

**Why another Contact Form?** I have tried many plugins in the past, and I found them all too heavy and complicated. Most of the time, I just wanted a simple **contact** form. Why would I need to create a new form in a list of forms, manually decide the fields and make sure everything is well set-up? And why do I need more plugins in order to add ReCAPTCHA support, or even pay for such a basic option? 

== Usage ==

The Contact Form Block has been designed to be used within the Gutenberg Editor. You can modify the labels of the main fields (Name, E-mail, and Message) and the overall design. A header can also be added, with an image and some text. The text and color of the submit button can also be customized. There are 3 basic ***themes*** available: None, Default and Meow Apps. None will add no styles to your contact form, Default will make it work on any theme, and Meow Apps will look a bit more solid.

The shortcode [contact-form-block] can also be used, just as it is! That shortcode can be used anywhere, in your widgets for example. Have a look at the [tutorial](https://meowapps.com/contact-form-block/tutorial/) to learn how to use it.

For the ReCAPTCHA and the other various settings, please visit the Contact Form page under the Meow Apps menu in your WordPress Admin.

== Notes ==

* The default behavior of this contact form is to send an e-mail to the admin of the WordPress install. That can be easily modified through the available WordPress filters.
* ReCAPTCHA v3 is currently used as an Anti-Spam. If you are interested, you can also develope your own 'human check'. Have a look at [this](https://meowapps.com/contact-form-block/tutorial/#Anti-Spam_Ask_a_question).

== Best Practices ==

This contact form follows the best practices, in order to guarantee a maximum conversion rate.

* Vertical layout (multi-columns result in a loss)
* Labels above the fields (instead of placeholders or tricks)
* Explicit and colorful submit button (labelled 'Send' instead of 'Submit', by default)
* A limited number of fields (after 3 fields, the conversion rate goes down)
* No captcha (don't worry about spams, this contact form includes ReCAPTCHA_v3, which is invisible)
* Use a header (with a small image), that will motivate the user to contact you

=== Limitations ===

This Contact Form's goal is to remain simple and fast. New fields and features can be added through its filters and actions, but I will not make the core more complex and heavy.

Languages: English.

== Installation ==

1. Upload `contact-form-block` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Upgrade Notice ==

Replace all the files. Nothing else to do.

== Changelog ==

= 1.0.2 (2024/10/16) =
* Update: Common libs.
* Info: There are many (complex) contact forms for WordPress. It is difficult for this one to get visibility. If you would like to help, and enjoy this plugin, remember to [write a little review for it](https://wordpress.org/support/plugin/contact-form-block/reviews/?rate=5#new-post). Thank you 🎵

= 1.0.1 (2024/07/07) =
* Update: Stable and mature plugin. It is now 1.0.0+ 🎉

= 1.0.0 (2024/06/02) =
* Update: Common libs.

= 0.3.4 (2023/11/15) =
* Add: Block fields now feature unique IDs for improved functionality and distinction.
* Update: Enhanced CAPTCHA integration using WordPress HTTP API for better reliability.
* Fix: Fixed settings redirection issue from the plugin page for a smoother user experience.
* Fix: Corrected the argument order in add_filter function for proper functionality.

= 0.3.3 (2023/10/15) =
* Fix: The calls to add_filters had swapped parameters.

= 0.3.2 (2023/09/22) =
* Update: Optimized bundles.
* Fix: Compatibility with PHP 8.4.

= 0.3.1 (2023/08/01) =
* Fix: The Captcha v3 was not working properly.

= 0.3.0 (2023/02/16) =
* Fix: Load the CSS locally instead of from its URL.
* Update: Async loading of Captcha.

= 0.2.9 (2022/11/12) =
* Added: Support for the new WordPress 6.1.
* Update: Enhanced the UI.

= 0.2.8 (2022/07/27) =
* Update: Compatibility with WP 6.
* Update: Admin and Neko UI.

= 0.2.7 (2022/02/01) =
* Update: Better CSS.
* Update: Fresh build of the scripts.
* Update: Compatibility with WP 6.9.

= 0.2.6 (2021/09/23) =
* Updated: Common 3.6.
* Updated: Admin and UI refreshed.
* Update: Changed the form button from an input to a button element.

= 0.2.2 =
* Update: New admin.
* Fix: Was showing a license issue.

= 0.2.1 =
* Update: Added the IDs on the fields.
* Update: New dynamic UI for settings.

= 0.1.7 =
* Fix: Accessibility issue with label 'for' not matching input 'name'.

= 0.1.6 =
* Fix: Avoid a potential warning.
* Fix: Open a new page when Google link is clicked.

= 0.1.5 =
* Fix: There was a little notice in the logs in come cases.

= 0.1.4 =
* Fix: Translation in French was not working for ReCAPTCHA_v3.
* Update: Added some code example for a human-check question addon (in the addons directory).

= 0.1.3 =
* Fix: The phone field was not returned properly to the form.

= 0.1.2 =
* Fix: A variable used for CSS was wrong.
* Fix: The text on the button can now be changed.
* Update: More i18n support was added.
* Update: It doesn't use the standard post handler anymore (admin-post.php) since it wasn't working with security plugins which modify the url of the wp-admin. Now using a custom handler. If you prefer the previous behavior, please change the $use_admin_post variable to true in the core.

= 0.0.9 =
* Fix: Warnings with the latest version of WordPress.

= 0.0.8 =
* Add: New phone field (which is also a good example if you would like to add your own fields).

= 0.0.7 =
* Fix: Remove the useless header_image_url variable.

= 0.0.6 =
* Add: Redirect URL.

= 0.0.4 =
* Add: Google ReCAPTCHA_v3.

= 0.0.3 =
* Add: Use the e-mail of the sender for the Reply-To.

= 0.0.2 =
* Fix: Issue when resetting the button colors.

= 0.0.1 =
* First release.

== Screenshots ==

1. Contact Form Block.
