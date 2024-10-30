const prefix = mcfb_contact_form_block.prefix;
const domain = mcfb_contact_form_block.domain;
const restUrl = mcfb_contact_form_block.rest_url.replace(/\/+$/, "");
const apiUrl = mcfb_contact_form_block.api_url.replace(/\/+$/, "");
const pluginUrl = mcfb_contact_form_block.plugin_url.replace(/\/+$/, "");
const isPro = mcfb_contact_form_block.is_pro === '1';
const isRegistered = isPro && mcfb_contact_form_block.is_registered === '1';
const restNonce = mcfb_contact_form_block.rest_nonce;

export { prefix, domain, apiUrl, restUrl, pluginUrl, isPro, isRegistered, restNonce };
