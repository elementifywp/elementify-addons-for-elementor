/*Library*/
import classnames from 'classnames';

/*Atrc*/
import {
    AtrcWrap,
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
    AtrcLink
} from 'atrc';

const AdminContent = (props) => {

    const {
        className = '',
        variant = '',
        notice,
        banner,
        header,
        content,
        sidebar,
        ...defaultProps
    } = props;

    console.log('AdminContent props: ', props);

    return (
        <AtrcWrap
            className={classnames(
                'eae-content-wrap',
                className,
                variant ? AtrcPrefix('landing') + '-' + variant : ''
            )}
            {...defaultProps}>
            <AtrcWrap className='at-ctnr-fld'>
                {notice.on ? (
                    <AtrcNotice
                        autoDismiss={false}
                        onRemove={notice.onRemove}>
                        {notice.text}
                    </AtrcNotice>
                ) : null}
                {content ? (
                    <AtrcWrap
                        className={classnames(
                            'eae-content-items',
                            'at-m'
                        )}>
                        {content.map((item, iDx) => (
                            <AtrcWrap
                                key={`eae-content-items__item-${iDx}`}
                                className={classnames(
                                    'eae-content-items__item',
                                    iDx === content.length - 1 ? 'eae-full-width' : '' // Add full-width class to last item
                                )}>
                                <AtrcWrap
                                    className={classnames(
                                        'eae-content-items__item-header'
                                    )}>
                                    {item.icon && (
                                        <AtrcIconSvg svg={item.icon} />
                                    )}
                                    {item.title && (
                                        <AtrcText
                                            tag='h2'
                                            className={classnames('at-m')}>
                                            {item.title}
                                        </AtrcText>
                                    )}
                                </AtrcWrap>
                                <AtrcWrap
                                    className={classnames(
                                        'eae-content-items__item-body'
                                    )}>
                                    {item.text && (
                                        <AtrcText
                                            tag='p'
                                            className={classnames('at-m')}>
                                            {item.text}
                                        </AtrcText>
                                    )}
                                    {item.btn_text && (
                                        <AtrcButton
                                            isLink={true}
                                            className={classnames('at-p')}
                                            href={item.btn_link || '#'}
                                            target={item.target || '_blank'}>
                                            {item.btn_text}
                                        </AtrcButton>
                                    )}
                                    {item.links && (
                                        <ul className={classnames('at-m')}>
                                            {item.links.map((link, lDx) => (
                                                <li
                                                    key={`eae-content-items__item-link-${iDx}-${lDx}`} // Fixed key to include both indexes
                                                    className={classnames(
                                                        'eae-content-items__item-link'
                                                    )}>
                                                    {link.title && (
                                                        <AtrcLink
                                                            href={link.url || '#'}
                                                            target={link.target || '_blank'}>
                                                            {link.title}
                                                        </AtrcLink>
                                                    )}
                                                </li>
                                            ))}
                                        </ul>
                                    )}
                                </AtrcWrap>
                            </AtrcWrap>
                        ))}
                    </AtrcWrap>
                ) : null}
            </AtrcWrap>
        </AtrcWrap>
    );
};

export default AdminContent;
