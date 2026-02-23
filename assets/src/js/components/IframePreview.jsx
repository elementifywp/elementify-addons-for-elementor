import { useState } from "react";
import classNames from "classnames";
import {
  ResponsiveIframeViewer,
  ViewportSize,
} from "react-responsive-iframe-viewer";

const IframePreview = ({ data, setPreviewLoading }) => {
  const [viewport, setViewport] = useState(ViewportSize.desktop);

  // Use values from props with sensible fallbacks
  const previewUrl = data?.url || "https://demo.elementifywp.com/";
  const previewTitle = data?.title || "Template Preview";

  return (
    <div className="iframe-preview-container">
      {/* Viewport toggle buttons */}
      <div className="viewport-buttons">
        <button
          type="button"
          className={classNames("viewport-btn", {
            active: viewport === ViewportSize.mobile,
          })}
          onClick={() => setViewport(ViewportSize.mobile)}
          aria-pressed={viewport === ViewportSize.mobile}
        >
          Mobile
        </button>

        <button
          type="button"
          className={classNames("viewport-btn", {
            active: viewport === ViewportSize.tablet,
          })}
          onClick={() => setViewport(ViewportSize.tablet)}
          aria-pressed={viewport === ViewportSize.tablet}
        >
          Tablet
        </button>

        <button
          type="button"
          className={classNames("viewport-btn", {
            active: viewport === ViewportSize.desktop,
          })}
          onClick={() => setViewport(ViewportSize.desktop)}
          aria-pressed={viewport === ViewportSize.desktop}
        >
          Desktop
        </button>
      </div>

      {/* Iframe viewer */}
      <div
        className={classNames("iframe-wrapper", {
          "is-mobile": viewport === ViewportSize.mobile,
          "is-tablet": viewport === ViewportSize.tablet,
          "is-desktop": viewport === ViewportSize.desktop,
        })}
      >
        <ResponsiveIframeViewer
          src={previewUrl}
          title={previewTitle}
          size={viewport}
          // Consider adding onIframeError if the library supports it
          // Custom sizes for each viewport
          overrideViewportSizes={{
            [ViewportSize.desktop]: { width: "100%", height: "420px" },
            [ViewportSize.tablet]: { width: "768px", height: "420px" },
            [ViewportSize.mobile]: { width: "375px", height: "420px" },
          }}
          // Hide internal controls since we're using our own buttons
          enabledControls={[]} // Empty array hides all internal controls
          allowFullScreen
          loading="lazy"
          sandbox="allow-same-origin allow-scripts allow-popups allow-forms"
        />
      </div>
    </div>
  );
};

export default IframePreview;
