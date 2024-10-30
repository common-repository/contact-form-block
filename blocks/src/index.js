const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { registerBlockType, createBlock } = wp.blocks;

import { default as edit } from './edit';

const meowGalleryIcon = (<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect width="20" height="20" fill="white"/>
		<path d="M16.6667 3.33334V13.3333H6.66667V3.33334H16.6667ZM16.6667 1.66667H6.66667L5 3.33334V13.3333L6.66667 15H16.6667L18.3333 13.3333V3.33334L16.6667 1.66667Z" fill="#2D4B6D"/>
		<path d="M10 10L10.8333 11.6667L13.3333 9.16667L15.8333 12.5H7.5L10 10Z" fill="#1ABC9C"/>
		<path d="M1.66667 5V16.6667L3.33333 18.3333H15V16.6667H3.33333V5H1.66667Z" fill="#2D4B6D"/>
</svg>);

const blockAttributes = {
	theme: {
		type: 'string',
		default: 'default'
	},
	header: {
		type: 'boolean',
		default: false
	},
	header_image_id: {
		type: 'number',
		default: null
	},
	header_image_align: {
		type: 'string',
		default: 'left'
	},
	header_image_size: {
		type: 'integer',
		default: 50
	},
	header_image_url: {
		type: 'string',
		default: null
	},
	header_title: {
		type: 'string',
		default: 'Get in Touch'
	},
	header_text: {
		type: 'string',
		default: 'Leave your message and we\'ll get back to you shortly.'
	},
	name_label: {
		type: 'string',
		default: 'Name'
	},
	email_label: {
		type: 'string',
		default: 'E-mail'
	},
	message_label: {
		type: 'string',
		default: 'Message'
	},
	message_rows: {
		type: 'integer',
		default: 5
	},
	button_text: {
		type: 'string',
		default: 'Send'
	},
	button_color: {
		type: 'string',
		default: '#3d84f6'
	},
	button_text_color: {
		type: 'string',
		default: 'white'
	}
};

const buildCoreAttributes = function(attributes) {
	const { name_label, email_label, message_label, 
		header, header_image_id, header_image_size, header_image_align, header_title, header_text,
		button_text, button_color, button_text_color,
		align, theme, message_rows } = attributes;

	let attrs = `
		name_label="${name_label}" 
		email_label="${email_label}" 
		message_label="${message_label}" 
		header="${header}" 
		header_image_id="${header_image_id}" 
		header_image_size="${header_image_size}" 
		header_image_align="${header_image_align}" 
		header_title="${header_title}" 
		header_text="${header_text}" 
		button_text="${button_text}" 
		button_color="${button_color}" 
		button_text_color="${button_text_color}" 
		align="${align}" 
		theme="${theme}" 
		message_rows="${message_rows}" 
	`;

	return attrs.trim();
};

const buildShortcode = function(attributes) {
	const attrs = buildCoreAttributes(attributes);
		return `[contact-form-block ${attrs}][/contact-form-block]`;
}

registerBlockType( 'contact-form-block/contact-form-block', {
	title: __( 'Contact Form Block' ),
	description: __( 'Display a contact form.' ),
	icon: meowGalleryIcon,
	category: 'layout',
	keywords: [ __( 'contact' ), __( 'form' ) ],
	attributes: blockAttributes,
	supports: {
		align: [ 'full', 'wide' ],
	},

	edit,

	save({ attributes }) {
		let str = buildShortcode(attributes);
		return (<Fragment>{str}</Fragment>);
	},

	deprecated: [
		{
			attributes: blockAttributes,
			save({ attributes }) {
				// From the previous one including phone_label, to the one without it
				let str = buildShortcode(attributes).replace('message_label=', 'phone_label="Phone" message_label=');
				return (<Fragment>{str}</Fragment>);
			}
		}
	]

});
