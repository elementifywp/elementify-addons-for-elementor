/* WordPress */
import { __ } from '@wordpress/i18n';
import { useContext } from '@wordpress/element';

/* Library */
import { isEmpty } from 'lodash';

/*Atrc*/
import {
    AtrcRoute,
    AtrcRoutes,
    AtrcNavigate,
    AtrcNav,
    AtrcWireFrameSidebarContent,
} from 'atrc';

/*Inbuilt*/
import { Widgets } from './pages';
import { AtrcReduxContextData } from '../../routes';
import { SaveSettings } from '../../components/atoms';

/*Local*/
const SettingsRouters = () => {
    return (
        <>
            <AtrcRoutes>
                <AtrcRoute
                    exact
                    path='widgets/*'
                    element={<Widgets />}
                />
                <AtrcRoute
                    path='/'
                    element={
                        <AtrcNavigate
                            to='widgets'
                            replace
                        />
                    }
                />
            </AtrcRoutes>
            <SaveSettings />
        </>
    );
};

const InitSettings = () => {
    const data = useContext(AtrcReduxContextData);
    const { dbSettings } = data;

    if (isEmpty(dbSettings)) {
        return null;
    }
    return (
        <AtrcWireFrameSidebarContent
            wrapProps={{
                tag: 'div',
                className: 'at-ctnr-fld',
            }}
            renderContent={<SettingsRouters />}
            contentProps={{
                tag: 'div',
                contentCol: 'at-col-12',
            }}
        />
    );
};

export default InitSettings;
