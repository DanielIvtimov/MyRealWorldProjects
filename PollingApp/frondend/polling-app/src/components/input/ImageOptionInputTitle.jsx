import React from 'react'
import "./styles/ImageOptionInputTitle.css"

const ImageOptionInputTitle = ({isSelected, imgUrl, onSelect}) => {

    const getColors = () => {
        return isSelected ? "selected" : "";
    }

  return <button className={`image-option-button ${getColors()}`} onClick={onSelect}>
    <img src={imgUrl} alt="" className="image-option-img" />
  </button> 
}

export default ImageOptionInputTitle