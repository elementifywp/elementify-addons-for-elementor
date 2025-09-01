/* WordPress */
import { __ } from '@wordpress/i18n';
import { useContext } from '@wordpress/element';

/*Library*/
import classNames from 'classnames';
import { BsBell, BsList, BsX } from 'react-icons/bs';

/* Inbuilt */
import { AtrcReduxContextData } from '../../routes';

/*Atrc*/
import {
    AtrcHeader,
    AtrcTooltip,
    AtrcWrap,
    AtrcLogo,
    AtrcIcon,
    AtrcNav,
    AtrcIconSvg,
    AtrcSpan,
    AtrcText,
    AtrcImg,
    AtrcHr,
    AtrcButton,
    AtrcNotice,
    AtrcButtonGroup,
    AtrcPrefix,
    AtrcPreTemplate1,
    AtrcList,
    AtrcLink,
    AtrcFloatingSidebar,
    AtrcPanelRow,
    AtrcControlToggle,
} from 'atrc';

const AdminHeader = (props) => {
    const data = useContext(AtrcReduxContextData);
    const { lsSettings, lsSaveSettings } = data;
    const {
        className = '',
        variant = '',
        logo,
        title,
        header = {},
        content,
        sidebar,
        ...defaultProps
    } = props;

    console.log('AdminHeader props:', props);

    const primaryNav = [
        {
            to: '/',
            children: (
                <>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 11.5C5 11.1022 5.15804 10.7206 5.43934 10.4393C5.72064 10.158 6.10218 10 6.5 10H17.5C17.8978 10 18.2794 10.158 18.5607 10.4393C18.842 10.7206 19 11.1022 19 11.5C19 11.8978 18.842 12.2794 18.5607 12.5607C18.2794 12.842 17.8978 13 17.5 13H6.5C6.10218 13 5.72064 12.842 5.43934 12.5607C5.15804 12.2794 5 11.8978 5 11.5ZM6.5 18H13.5C13.8978 18 14.2794 17.842 14.5607 17.5607C14.842 17.2794 15 16.8978 15 16.5C15 16.1022 14.842 15.7206 14.5607 15.4393C14.2794 15.158 13.8978 15 13.5 15H6.5C6.10218 15 5.72064 15.158 5.43934 15.4393C5.15804 15.7206 5 16.1022 5 16.5C5 16.8978 5.15804 17.2794 5.43934 17.5607C5.72064 17.842 6.10218 18 6.5 18ZM24 6.5V17.5C23.9984 18.9582 23.4184 20.3562 22.3873 21.3873C21.3562 22.4184 19.9582 22.9984 18.5 23H5.5C4.0418 22.9984 2.64377 22.4184 1.61267 21.3873C0.581561 20.3562 0.00158817 18.9582 0 17.5L0 6.5C0.00158817 5.0418 0.581561 3.64377 1.61267 2.61267C2.64377 1.58156 4.0418 1.00159 5.5 1H18.5C19.9582 1.00159 21.3562 1.58156 22.3873 2.61267C23.4184 3.64377 23.9984 5.0418 24 6.5ZM8 4.5C8 4.79667 8.08797 5.08668 8.2528 5.33336C8.41762 5.58003 8.65189 5.77229 8.92597 5.88582C9.20006 5.99935 9.50166 6.02906 9.79264 5.97118C10.0836 5.9133 10.3509 5.77044 10.5607 5.56066C10.7704 5.35088 10.9133 5.08361 10.9712 4.79264C11.0291 4.50166 10.9994 4.20006 10.8858 3.92597C10.7723 3.65189 10.58 3.41762 10.3334 3.2528C10.0867 3.08797 9.79667 3 9.5 3C9.10218 3 8.72064 3.15804 8.43934 3.43934C8.15804 3.72064 8 4.10218 8 4.5ZM3 4.5C3 4.79667 3.08797 5.08668 3.2528 5.33336C3.41762 5.58003 3.65189 5.77229 3.92597 5.88582C4.20006 5.99935 4.50166 6.02906 4.79264 5.97118C5.08361 5.9133 5.35088 5.77044 5.56066 5.56066C5.77044 5.35088 5.9133 5.08361 5.97118 4.79264C6.02906 4.50166 5.99935 4.20006 5.88582 3.92597C5.77229 3.65189 5.58003 3.41762 5.33336 3.2528C5.08668 3.08797 4.79667 3 4.5 3C4.10218 3 3.72064 3.15804 3.43934 3.43934C3.15804 3.72064 3 4.10218 3 4.5ZM21 8H3V17.5C3 18.163 3.26339 18.7989 3.73223 19.2678C4.20107 19.7366 4.83696 20 5.5 20H18.5C19.163 20 19.7989 19.7366 20.2678 19.2678C20.7366 18.7989 21 18.163 21 17.5V8Z" fill="#374957" />
                    </svg>
                    {__('Dashboard', 'elementify-addons-for-elementor')}
                </>
            ),
            end: true,
        },
        {
            to: '/settings',
            children: (
                <>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_405_1598)">
                            <path d="M10.7 2.62535C10.4188 2.02749 9.9733 1.522 9.41551 1.16794C8.85771 0.813871 8.21068 0.625854 7.55 0.625854C6.88932 0.625854 6.24229 0.813871 5.68449 1.16794C5.1267 1.522 4.68118 2.02749 4.4 2.62535H1.5C1.10218 2.62535 0.720644 2.78338 0.43934 3.06469C0.158035 3.34599 0 3.72752 0 4.12535H0C0 4.52317 0.158035 4.9047 0.43934 5.18601C0.720644 5.46731 1.10218 5.62535 1.5 5.62535H4.395C4.67618 6.2232 5.1217 6.7287 5.67949 7.08276C6.23729 7.43683 6.88432 7.62484 7.545 7.62484C8.20568 7.62484 8.85271 7.43683 9.41051 7.08276C9.9683 6.7287 10.4138 6.2232 10.695 5.62535H22.5C22.8978 5.62535 23.2794 5.46731 23.5607 5.18601C23.842 4.9047 24 4.52317 24 4.12535C24 3.72752 23.842 3.34599 23.5607 3.06469C23.2794 2.78338 22.8978 2.62535 22.5 2.62535H10.7Z" fill="#374957" />
                            <path d="M16.455 8.49939C15.7944 8.50057 15.1477 8.68911 14.5899 9.04312C14.0321 9.39714 13.5863 9.90211 13.304 10.4994H1.5C1.10218 10.4994 0.720644 10.6574 0.43934 10.9387C0.158035 11.22 0 11.6016 0 11.9994H0C0 12.3972 0.158035 12.7787 0.43934 13.0601C0.720644 13.3414 1.10218 13.4994 1.5 13.4994H13.3C13.5812 14.0972 14.0267 14.6027 14.5845 14.9568C15.1423 15.3109 15.7893 15.4989 16.45 15.4989C17.1107 15.4989 17.7577 15.3109 18.3155 14.9568C18.8733 14.6027 19.3188 14.0972 19.6 13.4994H22.5C22.8978 13.4994 23.2794 13.3414 23.5607 13.0601C23.842 12.7787 24 12.3972 24 11.9994C24 11.6016 23.842 11.22 23.5607 10.9387C23.2794 10.6574 22.8978 10.4994 22.5 10.4994H19.605C19.3228 9.90226 18.8771 9.3974 18.3195 9.04339C17.762 8.68939 17.1154 8.50075 16.455 8.49939Z" fill="#374957" />
                            <path d="M7.545 16.3744C6.88455 16.3758 6.23804 16.5644 5.68048 16.9184C5.12292 17.2724 4.67718 17.7773 4.395 18.3744H1.5C1.10218 18.3744 0.720644 18.5324 0.43934 18.8137C0.158035 19.095 0 19.4766 0 19.8744H0C0 20.2722 0.158035 20.6537 0.43934 20.935C0.720644 21.2163 1.10218 21.3744 1.5 21.3744H4.395C4.67618 21.9722 5.1217 22.4777 5.67949 22.8318C6.23729 23.1859 6.88432 23.3739 7.545 23.3739C8.20568 23.3739 8.85271 23.1859 9.41051 22.8318C9.9683 22.4777 10.4138 21.9722 10.695 21.3744H22.5C22.8978 21.3744 23.2794 21.2163 23.5607 20.935C23.842 20.6537 24 20.2722 24 19.8744C24 19.4766 23.842 19.095 23.5607 18.8137C23.2794 18.5324 22.8978 18.3744 22.5 18.3744H10.7C10.4175 17.7765 9.97094 17.2711 9.41241 16.9171C8.85388 16.563 8.2063 16.3748 7.545 16.3744Z" fill="#374957" />
                        </g>
                        <defs>
                            <clipPath id="clip0_405_1598">
                                <rect width="24" height="24" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    {__('Settings', 'elementify-addons-for-elementor')}
                </>
            ),
        },
    ];

    const renderFloatingSidebar = () => {
        if (!ElementifyAddonsLocalize) {
            console.error('ElementifyAddonsLocalize is not defined');
            return null;
        }

        return (
            <AtrcFloatingSidebar
                direction='right'
                variant='over'
                renderToggle={({ isOpen, openPortal }) => (
                    <AtrcButton
                        variant='devices'
                        aria-expanded={isOpen}
                        onClick={openPortal}
                        className={classNames(
                            lsSettings.gs1 ? 'eae-notice-inactive' : 'eae-notice-active',
                        )}
                    >
                        <AtrcTooltip
                            text={isOpen
                                ? __('Close sidebar', 'elementify-addons-for-elementor')
                                : __('Open sidebar', 'elementify-addons-for-elementor')
                            }
                        >
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.552 13.21L20.8 6.91604C20.2348 4.88799 19.0081 3.10697 17.3148 1.85593C15.6215 0.604898 13.5587 -0.0443874 11.4541 0.0112324C9.34952 0.0668523 7.32387 0.824187 5.699 2.16291C4.07413 3.50164 2.94323 5.34497 2.48598 7.40004L1.13198 13.489C0.953356 14.2929 0.957533 15.1267 1.1442 15.9288C1.33088 16.7308 1.69528 17.4808 2.21052 18.1232C2.72575 18.7656 3.37867 19.2841 4.12109 19.6405C4.86351 19.9968 5.67647 20.1819 6.49998 20.182H6.89998C7.21821 21.2826 7.88542 22.2499 8.80111 22.9383C9.7168 23.6267 10.8314 23.9989 11.977 23.9989C13.1226 23.9989 14.2372 23.6267 15.1529 22.9383C16.0685 22.2499 16.7358 21.2826 17.054 20.182H17.247C18.0949 20.1821 18.9314 19.9862 19.691 19.6095C20.4507 19.2328 21.113 18.6855 21.6261 18.0105C22.1393 17.3355 22.4894 16.5509 22.6491 15.7182C22.8089 14.8854 22.7739 14.027 22.547 13.21H22.552ZM19.243 16.194C19.0109 16.5023 18.71 16.7521 18.3644 16.9236C18.0187 17.0951 17.6378 17.1836 17.252 17.182H6.49998C6.12569 17.182 5.75619 17.0978 5.41875 16.9359C5.08131 16.7739 4.78455 16.5383 4.55035 16.2463C4.31616 15.9543 4.15052 15.6135 4.06564 15.2489C3.98077 14.8844 3.97884 14.5054 4.05998 14.14L5.41398 8.04004C5.7249 6.63611 6.49653 5.37654 7.60603 4.46183C8.71553 3.54712 10.0991 3.02985 11.5366 2.99236C12.974 2.95487 14.3827 3.39932 15.5384 4.25495C16.6941 5.11059 17.5303 6.32823 17.914 7.71404L19.662 14.008C19.7675 14.3798 19.7845 14.7712 19.7117 15.1507C19.639 15.5303 19.4785 15.8876 19.243 16.194Z" fill="#374957" />
                            </svg>
                        </AtrcTooltip>
                    </AtrcButton>
                )}
                renderContent={({ onClose }) => (
                    <AtrcWrap>
                        <AtrcWrap className={classNames('at-flx', 'at-jfy-cont-end')}>
                            <AtrcText
                                tag='h2'
                                className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                                {__(
                                    'Notifications',
                                    'elementify-addons-for-elementor'
                                )}
                            </AtrcText>
                            <AtrcButton
                                variant='close'
                                onClick={onClose}
                                className={classNames(
                                    'at-p',
                                    'at-m',
                                    'at-w',
                                    'at-h',
                                    'at-flx',
                                    'at-al-itm-ctr',
                                    'at-jfy-cont-ctr'
                                )}
                            >
                                <AtrcTooltip text={__('Close notification', 'elementify-addons-for-elementor')}>
                                    <AtrcIcon type='bootstrap' icon={BsX} />
                                </AtrcTooltip>
                            </AtrcButton>
                        </AtrcWrap>
                        <AtrcWrap>
                            <AtrcPanelRow>
                                <AtrcControlToggle
                                    label={__('Show all notifications', 'elementify-addons-for-elementor')}
                                    checked={lsSettings.gs1}
                                    onChange={(value) => lsSaveSettings({ gs1: value })}
                                />
                            </AtrcPanelRow>
                            {sidebar ? (
                                <AtrcWrap
                                    className={classNames(
                                        'eae-sidebar-items',
                                        'at-m'
                                    )}>
                                    {sidebar.map((item, iDx) => (
                                        <AtrcWrap
                                            key={`eae-sidebar-items__item-${iDx}`}
                                            className={classNames(
                                                'eae-sidebar-items__item',
                                            )}>
                                            <AtrcImg
                                                src={item.svg}
                                                alt={item.title}
                                            />
                                        </AtrcWrap>
                                    ))}
                                </AtrcWrap>
                            ) : null}

                        </AtrcWrap>
                    </AtrcWrap>
                )}
            />
        );
    };

    const renderHeaderContent = () => (
        <AtrcWrap className='at-flx at-jfy-cont-btw'>
            <AtrcWrap className={classNames(
                'eae-header-logo-wrap',
                'at-flx',
                'at-jfy-cont-st',
                'at-al-itm-ctr',
                logo && title ? 'at-gap' : ''
            )}>
                {logo && <AtrcLogo src={logo} />}
                {primaryNav && (
                    <AtrcNav
                        className='at-p'
                        variant='primary'
                        navs={primaryNav}
                    />
                )}
            </AtrcWrap>
            {renderFloatingSidebar()}
        </AtrcWrap>
    );

    return (
        <AtrcWrap
            className={classNames(
                'eae-header-wrap',
                className,
                variant && `${AtrcPrefix('landing')}-${variant}`
            )}
            {...defaultProps}
        >
            <AtrcWrap className='at-ctnr-fld'>
                {renderHeaderContent()}
            </AtrcWrap>
        </AtrcWrap>
    );
};

export default AdminHeader;