/**
 * WordPress dependencies
 */
import { createRoot } from '@wordpress/element';
import {
	useEffect,
	useState,
	useCallback,
	useMemo,
	useRef,
} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import classNames from 'classnames';
import {
	Modal,
	Button,
	Spinner,
	ButtonGroup,
	SearchControl,
	TabPanel,
	Icon,
	Flex,
	FlexBlock,
	FlexItem,
	Notice,
} from '@wordpress/components';
/**
 * External dependencies
 */

import {
	ResponsiveIframeViewer,
	ViewportSize,
} from 'react-responsive-iframe-viewer';
import InfiniteScroll from 'react-infinite-scroll-component';

import {
	FiChevronLeft,
	FiDownload,
	FiX,
	FiRefreshCcw,
	FiLayout,
	FiBox,
} from 'react-icons/fi';

import { FaDesktop, FaTabletAlt, FaMobileAlt } from 'react-icons/fa';

/**
 * Internal Components
 */
import HoverDropdown from '../components/HoverDropdown';
import TemplateCard from '../components/TemplateCard';
import logo from '../../images/logo.png';

let modalRoot = null;

const TemplateImporter = ({ isOpen, onClose }) => {
	const ITEMS_PER_LOAD = 12;
	const [templates, setTemplates] = useState([]);
	const [loading, setLoading] = useState(false);
	const [searchTerm, setSearchTerm] = useState('');
	const [selectedCategory, setSelectedCategory] = useState('all');
	const [categories, setCategories] = useState([]);
	const [importingId, setImportingId] = useState(null);
	const [error, setError] = useState(null);
	const [selectedType, setSelectedType] = useState('section');
	const [displayedTemplates, setDisplayedTemplates] = useState([]);
	const [hasMore, setHasMore] = useState(true);
	const [page, setPage] = useState(1);

	const [previewTemplate, setPreviewTemplate] = useState(null);
	const [previewLoading, setPreviewLoading] = useState(false);
	const [viewport, setViewport] = useState(ViewportSize.desktop);

	const scrollContainerRef = useRef(null);
	const filteredTemplatesRef = useRef([]);

	// Filtered templates based on type, category, and search
	const filteredTemplates = useMemo(() => {
		let filtered = templates.filter(
			(template) => template.type === selectedType
		);

		if (selectedCategory !== 'all') {
			filtered = filtered.filter((template) =>
				template.tags?.includes(selectedCategory)
			);
		}

		if (searchTerm.trim()) {
			const term = searchTerm.toLowerCase().trim();
			filtered = filtered.filter(
				(template) =>
					template.title?.toLowerCase().includes(term) ||
					template.description?.toLowerCase().includes(term) ||
					template.tags?.some((tag) =>
						tag.toLowerCase().includes(term)
					) ||
					template.keywords?.some((keyword) =>
						keyword.toLowerCase().includes(term)
					)
			);
		}

		filteredTemplatesRef.current = filtered;
		return filtered;
	}, [templates, selectedType, selectedCategory, searchTerm]);

	// Initialize displayed templates when filtered templates change
	useEffect(() => {
		if (filteredTemplates.length > 0) {
			const initialDisplay = filteredTemplates.slice(0, ITEMS_PER_LOAD);
			setDisplayedTemplates(initialDisplay);
			setHasMore(filteredTemplates.length > ITEMS_PER_LOAD);
			setPage(1);
		} else {
			setDisplayedTemplates([]);
			setHasMore(false);
			setPage(1);
		}
	}, [filteredTemplates]);

	// Load more templates function
	const loadMoreTemplates = useCallback(() => {
		const nextPage = page + 1;
		const startIndex = nextPage * ITEMS_PER_LOAD - ITEMS_PER_LOAD;
		const endIndex = startIndex + ITEMS_PER_LOAD;

		const moreTemplates = filteredTemplatesRef.current.slice(
			startIndex,
			endIndex
		);

		if (moreTemplates.length > 0) {
			setDisplayedTemplates((prev) => [...prev, ...moreTemplates]);
			setPage(nextPage);
			setHasMore(endIndex < filteredTemplatesRef.current.length);
		} else {
			setHasMore(false);
		}
	}, [page]);

	// Fetch templates and categories
	useEffect(() => {
		if (isOpen) {
			fetchData();
		}
	}, [isOpen, selectedType]);

	const fetchData = useCallback(
		async (forceUpdate = false) => {
			setLoading(true);
			setError(null);

			try {
				const response = await apiFetch({
					path: `/elementify-addons-for-elementor/v1/templates${forceUpdate ? '?force_update=true' : ''}`,
					method: 'GET',
				});

				if (!response?.success || !Array.isArray(response.data)) {
					throw new Error(
						__(
							'Failed to load templates',
							'elementify-addons-for-elementor'
						)
					);
				}

				setTemplates(response.data);

				// Extract categories for current type
				const typeTags = response.type_tags?.[selectedType] || [];
				const typeCategories = typeTags.map((tag) => ({
					slug: tag,
					name: tag
						.split('-')
						.map(
							(word) =>
								word.charAt(0).toUpperCase() + word.slice(1)
						)
						.join(' '),
				}));

				setCategories([
					{
						slug: 'all',
						name: __(
							'All Templates',
							'elementify-addons-for-elementor'
						),
					},
					...typeCategories,
				]);
			} catch (err) {
				console.error('Error fetching templates:', err);
				setError(
					err.message ||
						__(
							'Failed to load templates. Please try again.',
							'elementify-addons-for-elementor'
						)
				);
				setTemplates([]);
				setCategories([
					{
						slug: 'all',
						name: __(
							'All Templates',
							'elementify-addons-for-elementor'
						),
					},
				]);
			} finally {
				setLoading(false);
			}
		},
		[selectedType]
	);

	const handleTypeChange = useCallback((type) => {
		setSelectedType(type);
		setSelectedCategory('all');
	}, []);

	const importTemplate = useCallback(
		async (template) => {
			if (!template?.id) {
				setError(
					__(
						'Invalid template selected.',
						'elementify-addons-for-elementor'
					)
				);
				return;
			}

			setImportingId(template.id);
			setError(null);

			try {
				if (!window.elementor) {
					throw new Error(
						__(
							'Elementor editor is not available.',
							'elementify-addons-for-elementor'
						)
					);
				}

				// Get current post ID
				let postId = 0;
				const currentDocument = window.elementor.documents.getCurrent();
				if (currentDocument?.config?.id) {
					postId = currentDocument.config.id;
				} else if (window.elementor.config?.document?.id) {
					postId = window.elementor.config.document.id;
				}

				const response = await fetch(
					window.etiSettings?.ajaxUrl || window.ajaxurl,
					{
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: new URLSearchParams({
							action: 'eae_insert_template',
							nonce: window.etiSettings?.nonce || '',
							template_id: template.id,
							options: JSON.stringify({
								editor_post_id: postId,
								data: {
									edit_mode: true,
									display: true,
									template_id: template.id,
								},
							}),
						}),
					}
				);

				const data = await response.json();

				if (!data?.success) {
					throw new Error(data?.message || 'Template import failed');
				}

				if (data.data?.elements && window.elementor) {
					const content =
						typeof data.data.elements === 'string'
							? JSON.parse(data.data.elements)
							: data.data.elements;

					const container = elementor.getPreviewContainer();

					if (container && Array.isArray(content)) {
						content.forEach((elementData) => {
							$e.run('document/elements/create', {
								container,
								model: elementData,
								options: { at: container.children.length },
							});
						});
					}

					elementor.notifications.showToast({
						message:
							data.data.message ||
							__(
								'Template imported successfully!',
								'elementify-addons-for-elementor'
							),
						type: 'success',
					});
				} else {
					elementor.notifications.showToast({
						message:
							data.data?.message ||
							__(
								'Template imported.',
								'elementify-addons-for-elementor'
							),
						type: 'success',
					});
				}

				onClose();
			} catch (err) {
				console.error('Error importing template:', err);
				setError(
					err.message ||
						__(
							'Error importing template. Please try again.',
							'elementify-addons-for-elementor'
						)
				);
			} finally {
				setImportingId(null);
			}
		},
		[onClose]
	);

	const handlePreview = useCallback((template) => {
		if (!template?.url) {
			if (window.elementor?.notifications?.showToast) {
				window.elementor.notifications.showToast({
					message: __(
						'Preview not available.',
						'elementify-addons-for-elementor'
					),
					type: 'warning',
				});
			} else {
				alert(
					__(
						'Preview not available for this template.',
						'elementify-addons-for-elementor'
					)
				);
			}
			return;
		}

		setPreviewLoading(true);
		setPreviewTemplate(template);
	}, []);

	const closePreview = useCallback(() => {
		setPreviewTemplate(null);
		setPreviewLoading(false);
	}, []);

	const handleClose = useCallback(() => {
		onClose?.();
	}, [onClose]);

	const handleSearchChange = useCallback((value) => {
		setSearchTerm(value || '');
	}, []);

	const handleCategoryChange = useCallback((categorySlug) => {
		setSelectedCategory(categorySlug);
	}, []);

	const handleClearFilters = useCallback(() => {
		setSearchTerm('');
		setSelectedCategory('all');
	}, []);

	const categoryTabs = useMemo(
		() => categories.map((cat) => ({ name: cat.slug, title: cat.name })),
		[categories]
	);

	const renderCategoryTab = useCallback((tab) => {
		return <span style={{ display: 'none' }}>{tab.title}</span>;
	}, []);

	if (!isOpen) {
		return null;
	}

	return (
		<>
			<Modal
				title={__(
					'Import Templates',
					'elementify-addons-for-elementor'
				)}
				onRequestClose={handleClose}
				className="eae-template-modal"
				isDismissible
				shouldCloseOnClickOutside={!previewTemplate}
				shouldCloseOnEsc={!previewTemplate}
			>
				<div className="eae-modal-header">
					<Flex align="center" justify="space-between">
						<FlexBlock>
							{previewTemplate ? (
								<div className="eae-preview-actions">
									<Button
										icon={<FiChevronLeft />}
										onClick={closePreview}
										variant="secondary"
										className="inline-flex items-center cursor-pointer"
									>
										{__(
											'Back to Library',
											'elementify-addons-for-elementor'
										)}
									</Button>
								</div>
							) : (
								// Your else condition content here
								<div className="eae-logo-wrap">
									<img
										src={logo}
										alt="Elementify"
										className="eae-modal-logo"
									/>
									<h2 className="eae-modal-title">
										{__(
											'Library',
											'elementify-addons-for-elementor'
										)}
									</h2>
								</div>
							)}
						</FlexBlock>
						<FlexBlock>
							{!previewTemplate && (
								<ButtonGroup>
									<Button
										isPrimary={selectedType === 'section'}
										onClick={() =>
											handleTypeChange('section')
										}
										icon={<FiBox />}
									>
										{__(
											'Blocks',
											'elementify-addons-for-elementor'
										)}
									</Button>
									<Button
										isPrimary={selectedType === 'page'}
										onClick={() => handleTypeChange('page')}
										icon={<FiLayout />}
									>
										{__(
											'Pages',
											'elementify-addons-for-elementor'
										)}
									</Button>
								</ButtonGroup>
							)}
						</FlexBlock>

						<FlexItem>
							<div className="eae-modal-actions">
								{previewTemplate ? (
									<Button
										isPrimary
										icon={<FiDownload />}
										onClick={() => {
											importTemplate(previewTemplate);
										}}
										disabled={!!importingId}
									>
										{importingId === previewTemplate.id ? (
											<>
												<Spinner />
												{__(
													'Importing…',
													'elementify-addons-for-elementor'
												)}
											</>
										) : (
											<>
												{__(
													'Import',
													'elementify-addons-for-elementor'
												)}
											</>
										)}
									</Button>
								) : (
									// Your else condition content here
									<Button
										icon={<FiRefreshCcw />}
										label={__(
											'Refresh',
											'elementify-addons-for-elementor'
										)}
										onClick={() => fetchData(true)}
										disabled={loading}
										variant="secondary"
									/>
								)}

								<Button
									icon={<FiX />}
									label={__(
										'Close',
										'elementify-addons-for-elementor'
									)}
									onClick={handleClose}
									variant="secondary"
								/>
							</div>
						</FlexItem>
					</Flex>
				</div>

				<div className="eae-modal-body">
					{previewTemplate ? (
						<div className="eae-preview-container responsive-device-wrapper">
							{previewLoading && (
								<div className="eae-preview-loading">
									<Spinner />
									<p>
										{__(
											'Loading preview…',
											'elementify-addons-for-elementor'
										)}
									</p>
								</div>
							)}

							<div className="eae-viewport-buttons">
								<button
									type="button"
									className={classNames('eae-viewport-btn', {
										active:
											viewport === ViewportSize.desktop,
									})}
									onClick={() =>
										setViewport(ViewportSize.desktop)
									}
									aria-pressed={
										viewport === ViewportSize.desktop
									}
								>
									<FaDesktop />
								</button>
								<button
									type="button"
									className={classNames('eae-viewport-btn', {
										active:
											viewport === ViewportSize.tablet,
									})}
									onClick={() =>
										setViewport(ViewportSize.tablet)
									}
									aria-pressed={
										viewport === ViewportSize.tablet
									}
								>
									<FaTabletAlt />
								</button>

								<button
									type="button"
									className={classNames('eae-viewport-btn', {
										active:
											viewport === ViewportSize.mobile,
									})}
									onClick={() =>
										setViewport(ViewportSize.mobile)
									}
									aria-pressed={
										viewport === ViewportSize.mobile
									}
								>
									<FaMobileAlt />
								</button>
							</div>

							{/* Iframe viewer */}
							<div
								className={classNames('eae-iframe-wrapper', {
									'is-mobile':
										viewport === ViewportSize.mobile,
									'is-tablet':
										viewport === ViewportSize.tablet,
									'is-desktop':
										viewport === ViewportSize.desktop,
								})}
							>
								<ResponsiveIframeViewer
									src={
										previewTemplate.url ||
										'https://demo.elementifywp.com/'
									}
									title={
										previewTemplate.title ||
										'Template Preview'
									}
									onIframeLoad={() =>
										setPreviewLoading(false)
									}
									// Start in desktop mode with your custom size
									size={viewport}
									// Your custom dimensions for each toggle button
									overrideViewportSizes={{
										[ViewportSize.desktop]: {
											width: '100%',
										}, // initial + when clicking desktop button
										[ViewportSize.tablet]: {
											width: '768px',
										},
										[ViewportSize.mobile]: {
											width: '375px',
										},
									}}
									enabledControls={[]} // Empty array hides all internal controls
									loading="lazy"
								/>
							</div>
						</div>
					) : (
						<>
							<div className="eae-search-section">
								<Flex
									align="center"
									justify="space-between"
									gap={4}
								>
									<FlexBlock className="eae-search-wrapper">
										<SearchControl
											value={searchTerm}
											onChange={handleSearchChange}
											placeholder={__(
												'Search templates…',
												'elementify-addons-for-elementor'
											)}
											className="eae-search-control"
											disabled={loading}
										/>
										<span className="eae-template-count">
											{loading
												? __(
														'Loading…',
														'elementify-addons-for-elementor'
													)
												: `${filteredTemplates.length} ${__('templates found', 'elementify-addons-for-elementor')}`}
										</span>
									</FlexBlock>

									<FlexItem>
										{categories.length > 1 && !loading && (
											<HoverDropdown>
												<div className="eae-category-tabs">
													<TabPanel
														tabs={categoryTabs}
														onSelect={
															handleCategoryChange
														}
														initialTabName={
															selectedCategory
														}
														orientation="horizontal"
													>
														{renderCategoryTab}
													</TabPanel>
												</div>
											</HoverDropdown>
										)}
									</FlexItem>
								</Flex>
							</div>

							{error && (
								<Notice
									status="error"
									isDismissible
									onRemove={() => setError(null)}
								>
									{error}
								</Notice>
							)}
							<div
								className={`eae-template-grid-container eae-type-${selectedType}`}
								id="eae-scrollable-container"
								ref={scrollContainerRef}
								style={{
									height: 'calc(100vh - 200px)',
									overflow: 'auto',
								}}
							>
								{loading ? (
									<div className="eae-loading-state">
										<Spinner />
										<p>
											{__(
												'Loading templates…',
												'elementify-addons-for-elementor'
											)}
										</p>
									</div>
								) : displayedTemplates.length === 0 ? (
									<div className="eae-no-results">
										<Icon
											icon="warning"
											size={48}
											className="eae-no-results-icon"
										/>
										<p className="eae-no-results-text">
											{searchTerm ||
											selectedCategory !== 'all'
												? __(
														'No templates found. Try a different search.',
														'elementify-addons-for-elementor'
													)
												: __(
														'No templates available.',
														'elementify-addons-for-elementor'
													)}
										</p>
										{(searchTerm ||
											selectedCategory !== 'all') && (
											<Button
												variant="secondary"
												onClick={handleClearFilters}
											>
												{__(
													'Clear filters',
													'elementify-addons-for-elementor'
												)}
											</Button>
										)}
									</div>
								) : (
									<InfiniteScroll
										dataLength={displayedTemplates.length}
										next={loadMoreTemplates}
										hasMore={hasMore}
										loader={
											<div className="eae-infinite-loader">
												<Spinner />
											</div>
										}
										scrollableTarget="eae-scrollable-container"
									>
										<div className="eae-template-grid">
											{displayedTemplates.map(
												(template) => (
													<TemplateCard
														key={template.id}
														template={template}
														onImport={
															importTemplate
														}
														onPreview={
															handlePreview
														}
														importingId={
															importingId
														}
														isImporting={
															!!importingId
														} // Change this line
													/>
												)
											)}
										</div>
										{/* Add bottom padding for scrollable space */}
										<div
											style={{
												height: '200px',
												width: '100%',
											}}
										></div>
									</InfiniteScroll>
								)}
							</div>
						</>
					)}
				</div>
			</Modal>
		</>
	);
};

// Modal management functions
export function openTemplateImporter() {
	// Close existing modal if open
	closeTemplateImporter();

	const container = document.createElement('div');
	container.id = 'eae-modal-root';
	document.body.appendChild(container);

	modalRoot = createRoot(container);

	modalRoot.render(
		<TemplateImporter
			isOpen={true}
			onClose={() => {
				closeTemplateImporter();
			}}
		/>
	);
}

export function closeTemplateImporter() {
	if (modalRoot) {
		modalRoot.unmount();
		modalRoot = null;
	}

	const container = document.getElementById('eae-modal-root');
	if (container && container.parentNode) {
		container.parentNode.removeChild(container);
	}
}

// Expose globally
if (typeof window !== 'undefined') {
	window.etiOpenModal = openTemplateImporter;
	window.etiCloseModal = closeTemplateImporter;
}

export default TemplateImporter;
