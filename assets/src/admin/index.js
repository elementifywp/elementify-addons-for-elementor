/* *********===================== Setup store ======================********* */
import { AtrcApis, AtrcStore, AtrcRegisterStore } from 'atrc/build/data';

AtrcApis.baseUrl({
    //don't change atrc-global-api-base-url
    key: 'atrc-global-api-base-url',
    // eslint-disable-next-line no-undef
    url: ElementifyAddonsLocalize.rest_url,
});

/* Settings */
AtrcApis.register({
    key: 'settings',
    path: ElementifyAddonsLocalize.root_id + '/v1/settings',
    type: 'settings',
});

/* Settings Local for user preferance work with Window: localStorage property */
AtrcStore.register({
    key: 'ElementifyAddonsLocal',
    type: 'localStorage',
});

// eslint-disable-next-line no-undef
AtrcApis.xWpNonce(ElementifyAddonsLocalize.nonce);
AtrcRegisterStore(ElementifyAddonsLocalize.store);

import './routes';