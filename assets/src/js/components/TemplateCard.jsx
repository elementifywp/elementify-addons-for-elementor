/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import {
  Card,
  CardBody,
  CardMedia,
  Button,
  Flex,
  FlexItem,
  Icon,
} from "@wordpress/components";
import classNames from "classnames";
import { FiZoomIn, FiDownload } from "react-icons/fi";

const TemplateCard = ({
  template,
  onImport,
  onPreview,
  importingId,
  isImporting,
}) => {
  const {
    id,
    thumbnail,
    title,
    tags = [],
    is_pro: isPro,
    url: previewUrl,
  } = template;

  const isCurrentImporting = importingId === id;
  const isDisabled = isImporting && !isCurrentImporting;

  return (
    <Card
      className={classNames("eae-template-card", {
        "eae-template-disabled": isDisabled,
      })}
    >
      <CardMedia>
        <div className="eae-template-thumbnail">
          {thumbnail ? (
            <img
              src={thumbnail}
              alt={title || __("Template", "elementify-addons-for-elementor")}
            />
          ) : (
            <div className="eae-no-image">
              {__("No Image", "elementify-addons-for-elementor")}
            </div>
          )}
          {isPro && (
            <span className="eae-premium-badge">
              <Icon icon="star-filled" />
              {__("Premium", "elementify-addons-for-elementor")}
            </span>
          )}
        </div>
        <div className="eae-template-actions">
          <Flex justify="space-between" align="center">
            <FlexItem>
              <Button
                variant="secondary"
                icon={<FiZoomIn />}
                size="small"
                onClick={() => onPreview(template)}
                disabled={isDisabled}
              >
                {__("Preview", "elementify-addons-for-elementor")}
              </Button>
            </FlexItem>
            <FlexItem>
              <Button
                variant="primary"
                size="small"
                icon={<FiDownload />}
                onClick={() => onImport(template)}
                isBusy={isCurrentImporting}
                disabled={isDisabled}
              >
                {isCurrentImporting
                  ? __("Importing...", "elementify-addons-for-elementor")
                  : __("Import", "elementify-addons-for-elementor")}
              </Button>
            </FlexItem>
          </Flex>
        </div>
      </CardMedia>
      <CardBody>
        <div className="eae-template-header">
          <h3 className="eae-template-title">
            {title ||
              __("Untitled Template", "elementify-addons-for-elementor")}
          </h3>
          <div>
            {tags.map((tag, index) => (
              <span key={index} className="eae-template-tag">
                {tag}
              </span>
            ))}
          </div>
        </div>
      </CardBody>
    </Card>
  );
};

export default TemplateCard;