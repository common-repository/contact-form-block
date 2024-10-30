// React & Vendor Libs
const { useState, useEffect } = wp.element;
import useSWR from 'swr';

// NekoUI
import { NekoInput, NekoTypo, NekoPage, NekoBlock, NekoHeader, NekoContainer, NekoSettings,
  NekoTabs, NekoTab, NekoCheckboxGroup, NekoCheckbox, NekoWrapper, NekoColumn } from '@neko-ui';
import { jsonFetcher, postFetch } from '@neko-ui';

import { apiUrl } from '@app/settings';
import i18n from '@app/i18n';

// TODO: To remove as soon as possible! No more SWR!
const useHandleSWR = (swrData = undefined, defaultData = null, defaultBusy = false) => {
  const [ data, setData ] = useState(defaultData);
  const [ error, setError ] = useState(null);
  const [ busy, setBusy ] = useState(defaultBusy);
  const [ total, setTotal ] = useState(0);

  useEffect(() => {
    if (swrData !== undefined) { 
      if (swrData.success) {
        setError(null);
        setData(swrData.data);
        setTotal(swrData.total ? swrData.total : 0);
      }
      else {
        setError(swrData.error);
      }
    }
    setBusy(swrData === undefined);
  }, [ swrData ]);

  return { busy, data, total, error };
}

const Settings = () => {

  const { data: swrSettings, mutate: mutateSwrSettings } = useSWR(`${apiUrl}/all_settings/`, jsonFetcher);
  const { busy: busySettings, data: settings, error: swrError } = useHandleSWR(swrSettings, {}, true);
  const [ busyAction, setBusyAction ] = useState(false);
  const busy = busySettings || busyAction;

  const captchaKey = settings?.mcfb_captcha_key || '';
  const captchaSecretKey = settings?.mcfb_captcha_secret_key || '';
  const redirectUrl = settings?.mcfb_redirect_url || '';
  const phoneField = settings?.mcfb_phone_field || false;

  const updateOption = async (value, name) => {
    let newSettingsData = { ...swrSettings.data };
    newSettingsData[name] = value;
    mutateSwrSettings({ ...swrSettings, data: newSettingsData }, false);
    setBusyAction(true);
    try {
      await postFetch(`${apiUrl}/update_option`, { json: { name, value } });
    }
    catch (err) {
      alert(err.message);
    }
    finally {
      setBusyAction(false);
      mutateSwrSettings();
    }
  }

  /**
   * Google Captcha v3
   */
  const jsxPublicKey =
    <NekoSettings title="Public Key">
      <NekoInput name="mcfb_captcha_key" value={captchaKey} onBlur={updateOption} />
    </NekoSettings>;

  const jsxSecretKey =
    <NekoSettings title="Secret Key">
      <NekoInput name="mcfb_captcha_secret_key" value={captchaSecretKey} onBlur={updateOption} />
    </NekoSettings>;

  /**
   * Settings
   */

  const jsxRedirectUrl =
  <NekoSettings title="Redirect URL">
    <NekoInput name="mcfb_redirect_url" value={redirectUrl} description={<i>The visitor will be redirect to this URL after the message was sent. If empty, the success message will be shown on the form itself.</i>} onBlur={updateOption} />
  </NekoSettings>;

  const jsxPhoneField =
    <NekoSettings title="Phone Field">
      <NekoCheckboxGroup max="1">
        <NekoCheckbox name="mcfb_phone_field" label={i18n.ENABLED} value="1" checked={phoneField} onChange={updateOption} />
      </NekoCheckboxGroup>
    </NekoSettings>;

  return (
    <NekoPage nekoErrors={[ swrError ]}>

      <NekoHeader title='Contact Form Block | Settings' subtitle='By Jordy Meow' />

      <NekoWrapper>

        <NekoColumn fullWidth>
          <NekoContainer>
              <NekoTypo p>The Contact Form Block has support for the latest version of reCAPTCHA (v3) to avoid spam and abuse. To enable it, please <a href="https://www.google.com/recaptcha/admin/create">create your keys with Google</a> and simply paste then below. That's all :) To know more about it, <a href="https://www.google.com/recaptcha/intro/v3.html">click here</a>. The official website of the Contact Form Block can be found <a href="https://meowapps.com/plugin/contact-form-block/">here</a>.</NekoTypo>
          </NekoContainer>

          <NekoTabs>

            <NekoTab title='Settings'>
              <NekoWrapper>

                <NekoColumn minimal>
                  <NekoBlock busy={busy} title="Google Captcha v3" className="primary">
                    {jsxPublicKey}
                    {jsxSecretKey}
                  </NekoBlock>
                </NekoColumn>

                <NekoColumn minimal>
                  <NekoBlock busy={busy} title="Settings" className="primary">
                    {jsxRedirectUrl}
                    {jsxPhoneField}
                  </NekoBlock>
                </NekoColumn>

              </NekoWrapper>
            </NekoTab>

          </NekoTabs>

        </NekoColumn>

      </NekoWrapper>

    </NekoPage>
  );
};

export default Settings;