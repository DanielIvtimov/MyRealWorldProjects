import React, { useState } from 'react'
import "./Rating.css"
import { HiMiniStar } from 'react-icons/hi2'

const Rating = ({maxStars = 5, value = 0, onChange, readOnly = false}) => {

    const [hoverValue, setHoverValue] = useState(0);

    const handleClick = (rating) => {
        if(!readOnly && onChange){
            onChange(rating);
        }
    };

    const handleMouseEnter = (rating) => {
        if(!readOnly){
            setHoverValue(rating);  
        }
    };

    const handleMouseLeave = () => {
        if(!readOnly){
            setHoverValue(0);
        }
    };

  return (
    <div className={`ratingWrapper ${readOnly ? "readOnly" : "interactive"}`}>
        {[...Array(maxStars)].map((_, index) => {
            const starValue = index + 1;
            return (
                <span key={index} className={`ratingIcon ${starValue <= (hoverValue || value) ? "filled" : "empty"}`} onClick={() => handleClick(starValue)} onMouseEnter={() => handleMouseEnter(starValue)} onMouseLeave={handleMouseLeave}>
                    <HiMiniStar />
                </span>
            );
        })}
    </div>
  )
}

export default Rating