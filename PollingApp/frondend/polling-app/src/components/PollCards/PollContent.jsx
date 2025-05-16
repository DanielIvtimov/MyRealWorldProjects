import React from 'react'
import OptionInputTitle from '../input/OptionInputTitle';
import Rating from '../input/styles/Rating';
import "./styles/PollContent.css"
import ImageOptionInputTitle from '../input/ImageOptionInputTitle';

const PollContent = ({
    type,
    options,
    selectedOptionsIndex,
    onOptionSelect,
    rating,
    onRatingChange,
    userResponse,
    onResponseChange
}) => {
  switch(type){
    case "single-choice":
    case "yes/no":
        return(
            <>
                {options.map((option, index) => (
                    <OptionInputTitle 
                        key={option._id}
                        isSelected={selectedOptionsIndex === index}
                        label={option.optionText || ""}
                        onSelect={() => onOptionSelect(index)}
                    />
                ))}
            </>
        );
    case "image-based":
        return (
            <div className='grid grid-cols-2 gap-4'>
                {options.map((option, index) => (
                    <ImageOptionInputTitle 
                        key={option._id}
                        isSelected={selectedOptionsIndex === index}
                        imgUrl={option.optionText || ""}
                        onSelect={() => onOptionSelect(index)}
                    /> 
                ))}
            </div>
        );
    case "rating":
        return <Rating value={rating} onChange={onRatingChange} />;
    case "open-ended":
        return (
            <div className='openEndedContainer'>
                <textarea placeholder='Your response' className='openEndedTextarea' rows={4} value={userResponse} onChange={({target}) => onResponseChange(target.value)} />
            </div>
        )

        default: 
            return null;  
  }
};

export default PollContent