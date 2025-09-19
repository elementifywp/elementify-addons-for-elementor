/* WordPress */
import { useContext } from '@wordpress/element';

/* Library */
import { cloneDeep } from 'lodash';

/* Inbuilt */
import { AtrcReduxContextData } from '../../routes';
import { AdminContent } from '../../components/organisms';

const Landing = () => {
	const data = useContext(AtrcReduxContextData);
	const { lsSettings = {}, lsSaveSettings = () => { } } = data || {};

	// Safely access nested properties with optional chaining and nullish coalescing
	const dynamicWhitelabel = window.ElementifyAddonsLocalize?.white_label || {};
	const { dashboard = {} } = dynamicWhitelabel;

	const getWhiteLabelData = () => {
		const whiteLabel = {};

		/* Notice */
		if (dashboard?.notice) {
			whiteLabel.notice = {
				on: lsSettings?.gs1 || false,
				text: dashboard.notice,
				onRemove: () => {
					const updatedSettings = cloneDeep(lsSettings);
					updatedSettings.gs1 = !updatedSettings.gs1;
					lsSaveSettings(updatedSettings);
				},
			};
		}

		/* Content */
		if (dashboard?.content) {
			whiteLabel.content = dashboard.content;
		}

		/* sidebar */
		if (dashboard?.sidebar) {
			whiteLabel.sidebar = dashboard.sidebar;
		}

		return whiteLabel;
	};

	return <AdminContent {...getWhiteLabelData()} />;
};

export default Landing;