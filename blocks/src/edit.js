const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { IconButton, PanelBody, RangeControl, Button, CheckboxControl, 
		Toolbar, TextControl, SelectControl, withNotices } = wp.components;
const { BlockControls, PanelColorSettings, MediaUpload, InspectorControls } = wp.editor;
const { RichText } = window.wp.blockEditor;

const meowGalleryIcon = (<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect width="20" height="20" />
		<path d="M16.6667 3.33334V13.3333H6.66667V3.33334H16.6667ZM16.6667 1.66667H6.66667L5 3.33334V13.3333L6.66667 15H16.6667L18.3333 13.3333V3.33334L16.6667 1.66667Z" fill="#2D4B6D"/>
		<path d="M10 10L10.8333 11.6667L13.3333 9.16667L15.8333 12.5H7.5L10 10Z" fill="#1ABC9C"/>
		<path d="M1.66667 5V16.6667L3.33333 18.3333H15V16.6667H3.33333V5H1.66667Z" fill="#2D4B6D"/>
</svg>)

class ContactFormEdit extends Component {

	constructor() {
		super( ...arguments );
	}

	onSelectMedia( media ) {
		this.props.setAttributes({ header_image_id: media.id, header_image_url: media.url });
	}

	render() {
		const { attributes } = this.props;
		const { name_label, email_label, message_label, 
			header, header_image_id, header_image_url, header_image_size, header_image_align, header_title, header_text,
			button_text, button_color, button_text_color,
			theme, message_rows } = attributes;

		const bCSS = 'meow-contact-form';
		const bCSS_theme = theme ? ' meow-contact-form--' + theme : '';
		const bCSS_group = `${bCSS}__group`;
		const bCSS_label = `${bCSS_group}-label`;
		const bCSS_input = `${bCSS_group}-input`;
		const bCSS_textarea = `${bCSS_group}-textarea`;

		//console.log(attributes);

		// Icon from Dashicons:
		// https://github.com/WordPress/gutenberg/blob/master/packages/components/src/dashicon/index.js

		const controls = (
			<BlockControls>
				{ header && (
					<Toolbar>
						{header_image_align === 'right' ?
							<IconButton
								className="components-toolbar__control" label={ __( 'Show media on left' ) }
								icon='align-pull-left' onClick={() => this.props.setAttributes({ header_image_align: 'left' })}
							/> :
							<IconButton
								className="components-toolbar__control" label={ __( 'Show media on right' ) }
								icon='align-pull-right' onClick={() => this.props.setAttributes({ header_image_align: 'right' })}
							/>
						}
						<MediaUpload
							onSelect={x => this.onSelectMedia(x) } allowedTypes={'image'}
							render={({ open }) => (
								<IconButton
									className="components-toolbar__control" label={ __( 'Edit media' ) }
									icon='format-image' onClick={ open }
								/>
							)}
						/>
					</Toolbar>
				) }
			</BlockControls>
		);

		return (
			<Fragment>
				<form className={bCSS + ' ' + bCSS + '--editor' + bCSS_theme}>

					{header &&
						<div className={bCSS + '__header'}>
							{header_image_url && header_image_align === 'left' &&
								<img className={bCSS + '__header--image ' + bCSS + '__header--image--left'} 
									style={{ height: header_image_size, width: header_image_size }}
									src={header_image_url} />}
							<div className={bCSS + '__header__content'}>
								<RichText className={bCSS + '__header__content--title'} tagName='div' value={header_title}
									onChange={header_title => this.props.setAttributes({ header_title })} />
								<RichText className={bCSS + '__header__content--text'} tagName='div' value={header_text}
									onChange={header_text => this.props.setAttributes({ header_text })} />
							</div>
							{header_image_url && header_image_align === 'right' &&
								<img className={bCSS + '__header--image ' + bCSS + '__header--image--right'} 
									style={{ height: header_image_size, width: header_image_size }}
									src={header_image_url} />}
						</div>
					}

					<div className={bCSS_group}>
						<RichText className={bCSS_label} tagName='label' value={name_label} forHtml="name" 
							onChange={x => this.props.setAttributes({ name_label: x })} />
						<input className={bCSS_input} id='name' name='name' required disabled />
					</div>
					
					<div className={bCSS_group}>
						<RichText className={bCSS_label} tagName='label' value={email_label} forHtml="email" 
							onChange={x => this.props.setAttributes({ email_label: x })} />
						<input className={bCSS_input} id='email' name='email' required disabled />
					</div>
					
					<div className={bCSS_group}>
						<RichText className={bCSS_label} tagName='label' value={message_label} forHtml="message" 
							onChange={x => this.props.setAttributes({ message_label: x })} />
						<textarea className={bCSS_textarea} id='message' name='message' rows={message_rows} required disabled />
					</div>

					<input className={bCSS_input} style={{ background: button_color, color: button_text_color }} 
						type='submit' value={button_text} required disabled/>

				</form>

				{ controls }

				<InspectorControls>
					
					<PanelBody title={ __( 'Settings' ) } initialOpen={ false }>
						<SelectControl
							label={ __( 'Theme'  ) }
							value={ theme }
							onChange={x => this.props.setAttributes({ theme: x })}
							options={[ 
								{ label: 'Default', value: 'default' }, 
								{ label: 'Meow Apps', value: 'meowapps' }, 
								{ label: 'None', value: 'none' } 
							]}>
						</SelectControl>
						<TextControl label="Button Text" value={ button_text }
							onChange={x => this.props.setAttributes({ button_text: x })} />
						<CheckboxControl label="Show header" checked={header} 
							onChange={x => this.props.setAttributes({ header: x })} />
						<RangeControl label="Message Size" value={ message_rows } min={ 1 } max={ 50 }
							onChange={x => this.props.setAttributes({ message_rows: x })} />

					</PanelBody>

					<PanelColorSettings title={ __( 'Color Settings' ) } initialOpen={ false }
						colorSettings={ [
							{
								value: button_color,
								onChange: (x) => this.props.setAttributes({ button_color: x ? x : '#3d84f6' }),
								label: __( 'Button Color' ),
							},
							{
								value: button_text_color,
								onChange: (x) => this.props.setAttributes({ button_text_color: x ? x : 'white' }),
								label: __( 'Button Text Color' ),
							},
						] }
					/>

					{header && header_image_id && <PanelBody title={ __( 'Media Image' ) } initialOpen={ false }>
						{header_image_id &&
							<RangeControl label="Size" value={ header_image_size } min={ 40 } max={ 400 }
								onChange={x => this.props.setAttributes({ header_image_size: x })} />
						}
						{header_image_id &&
							<Button isPrimary onClick={x => this.props.setAttributes({ header_image_id: null, header_image_url: null })}>
								Remove</Button>
						}
					</PanelBody>}

				</InspectorControls>
			</Fragment>
		);
	}
}

export default withNotices( ContactFormEdit );