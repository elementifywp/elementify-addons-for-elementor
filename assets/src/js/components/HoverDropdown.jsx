import { useState, useRef } from '@wordpress/element';
import { Popover, Button, MenuGroup } from '@wordpress/components';

import {
  FiSliders,
} from "react-icons/fi";

const HoverDropdown = ({ children }) => {
  const [isVisible, setIsVisible] = useState(false);
  const timeoutRef = useRef(null);
  const buttonRef = useRef();

  const handleMouseEnter = () => {
    clearTimeout(timeoutRef.current);
    setIsVisible(true);
  };

  const handleMouseLeave = () => {
    timeoutRef.current = setTimeout(() => {
      setIsVisible(false);
    }, 300); // Small delay to prevent accidental closing
  };

  return (
    <div
      className="ele-dropdown-container"
      onMouseLeave={handleMouseLeave}
    >
      <Button
        ref={buttonRef}
        variant="secondary"
        onMouseEnter={handleMouseEnter}
        aria-expanded={isVisible}
        icon={<FiSliders />}
      >
        {isVisible ? 'Close Filter' : 'Open Filter'} 
      </Button>
      {isVisible && (
        <Popover
          anchorRef={buttonRef.current}
          placement="bottom-start"
          onMouseEnter={handleMouseEnter}
          onMouseLeave={handleMouseLeave}
          className="ele-dropdown-popover"
        >
          <MenuGroup>
              {children}
            </MenuGroup>
        </Popover>
      )}
    </div>
  );
};

export default HoverDropdown;