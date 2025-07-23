import React, { useState } from 'react'
import "./styles/TagInput.css"
import { MdAdd, MdClose } from 'react-icons/md'
import { GrMapLocation } from 'react-icons/gr';

const TagInput = ({ tags, setTags }) => {

    const [inputValue, setInputValue] = useState("");

    const addNewTag = () => {
        if(inputValue.trim() !== ""){
            setTags([...tags, inputValue.trim()]);
            setInputValue("");
        }
    };

    const handleInputChange = (event) => {
        setInputValue(event.target.value)
    }

    const handleKeyDown = (event) => {
        if(event.key === "Enter"){
            addNewTag();
        }
    }

    const handleRemoveTag = (tagToRemove) => {
        setTags(tags.filter((tag) => tag !== tagToRemove));
    };

  return (
    <div>
        {tags.length > 0 && (
            <div className="tag-list-container">
                {tags.map((tag, index) => (
                    <span key={index} className="tags-item">
                        <GrMapLocation className="tag-icon" /> {tag}
                        <button onClick={() => handleRemoveTag(tag)} className="tags-close-btn">
                            <MdClose />
                        </button>
                    </span>
                ))}
            </div>
        )}
        <div className="tag-input-container">
            <input type="text" value={inputValue} className="tag-input-field" placeholder="Add Location" onChange={handleInputChange} onKeyDown={handleKeyDown} />
            <button className="tag-input-add-button" onClick={addNewTag}>
                <MdAdd className="tag-input-add-icon" />
            </button>
        </div>
    </div>
  )
}

export default TagInput