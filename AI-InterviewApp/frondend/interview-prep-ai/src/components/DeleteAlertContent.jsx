import React from 'react'
import "./styles/DeleteAlertContent.css"

const DeleteAlertContent = ({content, onDelete}) => {
  return (
    <div className="delete-alert-container">
        <p className="delete-alert-message">{content}</p>
        <div className="delete-alert-actions">
            <button type="button" className="delete-alert-button" onClick={onDelete}>Delete</button>   
        </div>
    </div>
  )
}

export default DeleteAlertContent