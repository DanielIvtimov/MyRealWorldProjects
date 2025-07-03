import React from 'react';
import { LuX } from 'react-icons/lu';
import "./styles/Drawer.css"

const Drawer = ({ isOpen, onClose, title, children }) => {
  return (
    <div className={`drawer-container ${isOpen ? "drawer-open" : "drawer-closed"}`}
        tabIndex="-1"
        aria-labelledby="drawer-right-label"
        >
            <div className="drawer-header">
                <h5 id="drawer-right-label" className="drawer-title">
                    {title}
                </h5>
                <button type="button" onClick={onClose} className="drawer-close-button">
                    <LuX className="drawer-close-icon" />
                </button>
            </div>
            <div className="drawer-body">{children}</div>
        </div>
  )
}

export default Drawer