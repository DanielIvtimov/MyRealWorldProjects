import React, { useState } from 'react'
import "./styles/OptionInput.css";
import { HiOutlineTrash } from 'react-icons/hi';
import { HiMiniPlus } from 'react-icons/hi2';

const OptionInput = ({optionList, setOptionList}) => {

    const [option, setOption] = useState("");

    const handleAddOption = () => {
        if(option.trim() && optionList.length < 4){ 
            setOptionList([...optionList, option.trim()]);
            setOption("");
        }
    };

    const handleDeleteOption = (index) => {
        const updateArr = optionList.filter((_, idx) => idx !== index)
        setOptionList(updateArr);
    }

  return (
    <div>
        {(optionList.map((item, index) => (
            <div key={item} className='option-input-container'>
                <p className='option-input-text'>{item}</p>
                <button onClick={() => {handleDeleteOption(index)}}><HiOutlineTrash className='option-input-delete-icon' /></button>
            </div>
        )))}
        {optionList.length < 4 && (
            <div className='option-input-wrapper'>
                <input type="text" placeholder="Enter Option" value={option} onChange={({ target }) => setOption(target.value)} className="option-input-field" />
                <button className='option-input-button' onClick={handleAddOption}><HiMiniPlus className="option-input-icon" />Add Option</button>  
            </div>
        )}
    </div>
  )
}

export default OptionInput