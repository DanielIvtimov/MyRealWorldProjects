import React from 'react'
import "./styles/OptionInputTitle.css"
import {MdRadioButtonChecked, MdRadioButtonUnchecked} from "react-icons/md";

const OptionInputTitle = ({
    isSelected,
    label,
    onSelect
}) => {

    
    const getColors = () => {
        if(isSelected){
            return "selectedOption"
        }
        return "unselectedOption"
    } 

  return (
    <div>
        <button className={`optionButton ${getColors()}`} onClick={onSelect}>
            {isSelected ? (
                <MdRadioButtonChecked className="radioIconSelected" />
            ) : (
                <MdRadioButtonUnchecked className="radioIconUnselected" />
            )}
            <span className="optionLabel">{label}</span>
        </button>
    </div>
  )
}

export default OptionInputTitle