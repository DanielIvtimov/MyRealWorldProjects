import React from 'react'
import "./styles/EmptyCard.css"


const EmptyCard = ({imgSrc, message, btnText, onClick}) => {
  return (
    <div className="empty-card-container">
      <img src={imgSrc} alt="No notes" className='empty-card-image' />
      <p className='empty-card-message'>{message}</p>
      {btnText && (
        <button className='empty-card-button' onClick={onClick}>{btnText}</button>  
      )}
    </div>
  )
}

export default EmptyCard