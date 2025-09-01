/*Library*/
import classnames from 'classnames';

/*Atrc*/
import {
    AtrcWrap,
    AtrcSpan,
    AtrcText,
    AtrcImg,
} from 'atrc';

const AdminTopBanner = (props) => {
    const {
        className = '',
        variant = '',
        background,
        ...defaultProps
    } = props;

    return (
        <AtrcWrap
            className={classnames(
                'eae-top-banner-wrap',
                className,
                variant ? AtrcPrefix('landing') + '-' + variant : ''
            )}
            {...defaultProps}>
            {background ? (
                <AtrcWrap
                    className={classnames(
                        'at-ctnr-fld'
                    )}
                >
                    <AtrcImg
                        src={background}
                        className={classnames('eae-hero-banner-content__bg')}
                    />
                </AtrcWrap>
            ) : null}
        </AtrcWrap>
    );
};

export default AdminTopBanner;