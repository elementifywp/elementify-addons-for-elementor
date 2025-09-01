/*CSS*/
import './atomic.css';
import './index.scss';

/* WordPress */
import { createRoot, createContext, useContext } from '@wordpress/element';

/* Library */
import { map, isEmpty } from 'lodash';

/*Atrc*/
import {
    AtrcHashRouter,
    AtrcRoute,
    AtrcRoutes,
    AtrcWrap,
    AtrcNotice,
    AtrcWrapFloating,
    AtrcMain
} from 'atrc';

import { AtrcApplyWithSettings } from 'atrc/build/data';

/*Inbuilt*/
import { AdminTopBanner, AdminHeader } from './components/organisms';

import Initlanding from './pages/landing';
import InitSettings from './pages/settings/routes';

/* Local */

/* ==============Create Local Storage and Database Settings Context================== */
export const AtrcReduxContextData = createContext();

const AdminRoutes = () => {
    const data = useContext(AtrcReduxContextData);
    const { dbNotices, dbRemoveNotice } = data;

    const { white_label: dynamicWhitelabel } = ElementifyAddonsLocalize;
    const { dashboard = {} } = dynamicWhitelabel;

    const whiteLabel = {};

    // Dashboard
    if (dashboard) {
        if (dashboard.background) {
            /*background */
            whiteLabel.background = dashboard.background;
        }
        if (dashboard.title) {
            /*title */
            whiteLabel.title = dashboard.title;
        }
        if (dashboard.logo) {
            /*logo */
            whiteLabel.logo = dashboard.logo;
        }

        /* sidebar */
        if (dashboard?.sidebar) {
            whiteLabel.sidebar = dashboard.sidebar;
        }
    }

    return (
        <AtrcWrap className='eae-dashboard-wrap'>
            <>
                <AdminTopBanner {...whiteLabel} />
                <AdminHeader {...whiteLabel} />
            </>
            <AtrcRoutes>
                <AtrcRoute
                    index
                    element={<Initlanding />}
                />
                <AtrcRoute
                    exact
                    path='/settings/*'
                    element={<InitSettings />}
                />
            </AtrcRoutes>
            {/*Notice is common for settings*/}
            {!isEmpty(dbNotices) ? (
                <AtrcWrapFloating>
                    {map(dbNotices, (value, key) => (
                        <AtrcNotice
                            key={key}
                            autoDismiss={5000}
                            onAutoRemove={() => dbRemoveNotice(key)}
                            onRemove={() => dbRemoveNotice(key)}>
                            {value.message}
                        </AtrcNotice>
                    ))}
                </AtrcWrapFloating>
            ) : null}
        </AtrcWrap>
    );
};

/* Init actual WordPress settings */
const InitDatabaseSettings = (props) => {
    const {
        isLoading,
        canSave,
        settings,
        updateSetting,
        saveSettings,
        notices,
        removeNotice,
        lsSettings,
        lsUpdateSetting,
        lsSaveSettings,
    } = props;

    const dbProps = {
        dbIsLoading: isLoading,
        dbCanSave: canSave,
        dbSettings: settings,
        dbUpdateSetting: updateSetting,
        dbSaveSettings: saveSettings,
        dbNotices: notices,
        dbRemoveNotice: removeNotice,
        lsSettings: lsSettings,
        lsUpdateSetting: lsUpdateSetting,
        lsSaveSettings: lsSaveSettings,
    };
    return (
        <AtrcReduxContextData.Provider value={{ ...dbProps }}>
            <AtrcHashRouter basename='/'>
                <AdminRoutes />
            </AtrcHashRouter>
        </AtrcReduxContextData.Provider>
    );
};
const InitDataBaseSettingsWithHoc = AtrcApplyWithSettings(InitDatabaseSettings);

/* Init local storage settings */
const InitLocalStorageSettings = (props) => {
    const { settings, updateSetting, saveSettings } = props;
    const defaultSettings = {
        gs1: true /* getting started 1 */,
    };
    return (
        <InitDataBaseSettingsWithHoc
            atrcStore={ElementifyAddonsLocalize.store}//store from AtrcRegisterStore
            atrcStoreKey='settings'//key from admin.js
            lsSettings={settings || defaultSettings}
            lsUpdateSetting={updateSetting}
            lsSaveSettings={saveSettings}
        />
    );
};
const InitLocalStorageSettingsWithHoc = AtrcApplyWithSettings(
    InitLocalStorageSettings
);

document.addEventListener('DOMContentLoaded', () => {
    // Check if the root element exists in the DOM
    const rootElement = document.getElementById(ElementifyAddonsLocalize.root_id);

    if (rootElement) {
        // Render the component into the root element
        const root = createRoot(rootElement);
        root.render(
            <InitLocalStorageSettingsWithHoc
                atrcStore={ElementifyAddonsLocalize.store} //store from AtrcRegisterStore
                atrcStoreKey='ElementifyAddonsLocal'//key from admin.js
            />
        );
    }
});