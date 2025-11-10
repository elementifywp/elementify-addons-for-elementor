/*Library*/
import classNames from 'classnames';

/*Atrc*/
import { AtrcWrap, AtrcImg, AtrcPrefix } from 'atrc';

const AdminTopBanner = (props) => {
	const { className = '', variant = '', background, ...defaultProps } = props;

	return (
		<AtrcWrap
			className={classNames(
				'eae-top-banner-wrap',
				className,
				variant ? AtrcPrefix('landing') + '-' + variant : ''
			)}
			{...defaultProps}
		>
			{background ? (
				<AtrcWrap className={classNames('at-ctnr-fld')}>
					<AtrcImg
						src={background}
						className={classNames('eae-hero-banner-content__bg')}
					/>
				</AtrcWrap>
			) : null}
		</AtrcWrap>
	);
};

export default AdminTopBanner;
