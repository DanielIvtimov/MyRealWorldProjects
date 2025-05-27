import React from 'react'
import "./styles/Modal.css"

const Modal = ({children, isOpen, onClose, title, hideHeader}) => {
  return (
    <div className="modal-overlay">
        {/* Modal Content */}
        <div className="modal-content">
            {/* Modal Header */}
            {!hideHeader && (
                <div className="modal-header">
                    <h3 className="modal-title">{title}</h3>
                </div>
            )}
            <button type="button" className="modal-close-button" onClick={onClose}>
                <svg 
                className="modal-close-icon" 
                aria-hidden="true" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 14 14"
                >
                    <path
                    stroke="currentColor"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6"
                    />
                </svg>
            </button>
            {/* Modal Body (Scrollable) */}
            <div className="modal-body">
                {children}
            </div>  
        </div>
    </div>
  )
}

export default Modal