/* WordPress */
import { __ } from '@wordpress/i18n';
import { useContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';
import { cloneDeep } from 'lodash';

/* Atrc */
import {
    AtrcText,
    AtrcControlToggle,
    AtrcWireFrameContentSidebar,
    AtrcWireFrameHeaderContentFooter,
    AtrcPrefix,
    AtrcPanelBody,
    AtrcPanelRow,
    AtrcContent,
    AtrcTitleTemplate1,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import { DocsTitle } from '../../../components/molecules';

/* Local */
const MainContent = () => {
    const data = useContext(AtrcReduxContextData);

    // Fallback if context is not available
    if (!data) {
        return (
            <AtrcText tag="p">
                {__('Error: Settings context not available.', 'elementify-addons-for-elementor')}
            </AtrcText>
        );
    }

    const { dbSettings, dbUpdateSetting } = data;

    // Ensure the value is a boolean
    const isAnimationTextEnabled = !!dbSettings?.animated_text;
    const isFormStylerEnabled = !!dbSettings?.form_styler;
    const isLogosEnabled = !!dbSettings?.logos;
    const isTabsEnabled = !!dbSettings?.tabs;
    const isTestimonialsEnabled = !!dbSettings?.testimonials;
    const isPortfolioEnabled = !!dbSettings?.portfolio;

    return (
        <AtrcContent>
            <AtrcPanelRow>
                <AtrcControlToggle
                    label={__('Animated Text', 'elementify-addons-for-elementor')}
                    checked={isAnimationTextEnabled}
                    onChange={() => dbUpdateSetting('animated_text', !isAnimationTextEnabled)}
                />
            </AtrcPanelRow>
            <AtrcPanelRow>
                <AtrcControlToggle
                    label={__('Form Styler', 'elementify-addons-for-elementor')}
                    checked={isFormStylerEnabled}
                    onChange={() => dbUpdateSetting('form_styler', !isFormStylerEnabled)}
                />
            </AtrcPanelRow>
            <AtrcPanelRow>
                <AtrcControlToggle
                    label={__('Logos', 'elementify-addons-for-elementor')}
                    checked={isLogosEnabled}
                    onChange={() => dbUpdateSetting('logos', !isLogosEnabled)}
                />
            </AtrcPanelRow>
            <AtrcPanelRow>
                <AtrcControlToggle
                    label={__('Tabs', 'elementify-addons-for-elementor')}
                    checked={isTabsEnabled}
                    onChange={() => dbUpdateSetting('tabs', !isTabsEnabled)}
                />
            </AtrcPanelRow>
            <AtrcPanelRow>
                <AtrcControlToggle
                    label={__('Testimonials', 'elementify-addons-for-elementor')}
                    checked={isTestimonialsEnabled}
                    onChange={() => dbUpdateSetting('testimonials', !isTestimonialsEnabled)}
                />
            </AtrcPanelRow>
            <AtrcPanelRow>
                <AtrcControlToggle
                    label={__('Portfolio', 'elementify-addons-for-elementor')}
                    checked={isPortfolioEnabled}
                    onChange={() => dbUpdateSetting('portfolio', !isPortfolioEnabled)}
                />
            </AtrcPanelRow>
        </AtrcContent>
    );
};

const Documentation = () => {
    const data = useContext(AtrcReduxContextData);

    // Fallback if context is not available
    if (!data) {
        return (
            <AtrcText tag="p">
                {__('Error: Settings context not available.', 'elementify-addons-for-elementor')}
            </AtrcText>
        );
    }

    const { lsSettings, lsSaveSettings } = data;

    return (
        <AtrcWireFrameHeaderContentFooter
            headerRowProps={{
                className: classNames(AtrcPrefix('header-docs'), 'at-m'),
            }}
            renderHeader={
                <DocsTitle
                    onClick={() => {
                        const localStorageClone = cloneDeep(lsSettings || {});
                        localStorageClone.bmSaDocs1 = !localStorageClone.bmSaDocs1;
                        lsSaveSettings(localStorageClone);
                    }}
                />
            }
            renderContent={
                <>
                    <AtrcPanelBody
                        className={classNames(AtrcPrefix('m-0'))}
                        title={__(
                            'Accessing Elementor Widgets?',
                            'elementify-addons-for-elementor'
                        )}
                        initialOpen={true}>
                        <AtrcText
                            tag='h6'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '1. Open the Elementor Editor:',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Edit a page/post with Elementor.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Click "Edit with Elementor" from your WordPress dashboard.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='h6'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '2. Find the Widget Panel:',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- On the left side of the editor, you`ll see the Elementor panel.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Click the "Widgets" icon (grid-like icon) at the top.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                    </AtrcPanelBody>
                    <AtrcPanelBody
                        className={classNames(AtrcPrefix('m-0'))}
                        title={__(
                            'Adding Widgets to Your Page?',
                            'elementify-addons-for-elementor'
                        )}
                        initialOpen={false}>
                        <AtrcText
                            tag='h6'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '1. Drag and Drop Method:',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Locate the widget in the panel.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Drag it to your desired section/column.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='h6'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '2. Click to Add Method:',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Hover over a section or column.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Click the "+" icon to add a new widget.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Select your widget from the popup.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                    </AtrcPanelBody>
                    <AtrcPanelBody
                        className={classNames(AtrcPrefix('m-0'))}
                        title={__(
                            'Customizing Widgets?',
                            'elementify-addons-for-elementor'
                        )}
                        initialOpen={false}>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                'Once added, you can customize each widget.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='h6'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '1. Content Tab:',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Configure the main content of the widget.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Example: For a button widget, set the text and link.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='h6'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '2. Style Tab:',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Customize colors, typography, spacing, and more.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                'Add borders, shadows, and other visual effects.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='h6'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '3. Advanced Tab:',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Add custom CSS, motion effects, and responsive settings.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Configure margins and padding for precise layout control.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                '- Add custom attributes or IDs for advanced functionality.',
                                'elementify-addons-for-elementor'
                            )}
                        </AtrcText>
                    </AtrcPanelBody>
                </>
            }
        />
    );
};

const Settings = () => {
    const data = useContext(AtrcReduxContextData);

    // Fallback if context is not available
    if (!data) {
        return (
            <AtrcText tag="p">
                {__('Error: Settings context not available.', 'elementify-addons-for-elementor')}
            </AtrcText>
        );
    }

    const { lsSettings } = data;
    const bmSaDocs1 = lsSettings?.bmSaDocs1 ?? false;

    return (
        <AtrcWireFrameHeaderContentFooter
            wrapProps={{
                className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
            }}
            renderHeader={<AtrcTitleTemplate1 title={__('Widgets', 'elementify-addons-for-elementor')} />}
            renderContent={
                <AtrcWireFrameContentSidebar
                    wrapProps={{
                        allowContainer: true,
                        type: 'fluid',
                        tag: 'section',
                        className: 'at-p',
                    }}
                    renderContent={<MainContent />}
                    renderSidebar={bmSaDocs1 ? null : <Documentation />}
                    contentProps={{
                        contentCol: bmSaDocs1 ? 'at-col-12' : 'at-col-7',
                    }}
                    sidebarProps={{
                        sidebarCol: 'at-col-5',
                    }}
                />
            }
        />
    );
};

export default Settings;