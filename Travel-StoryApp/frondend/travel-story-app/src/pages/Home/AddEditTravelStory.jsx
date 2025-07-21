import React from 'react'
import "./styles/AddEditTravelStory.css"
import { MdAdd, MdClose, MdDeleteOutline, MdUpdate } from 'react-icons/md'

const AddEditTravelStory = ({ storyInfo, type, onClose, getAllTravelStories }) => {

    const handleAddOrUpdateClick = () => {};

  return (
    <div>
        <div className="add-edit-story-header">
            <h5 className="add-edit-story-title">
                {type === "add" ? "Add Story" : "Update Story"}
            </h5>
            <div>
                <div className="add-edit-story-actions">
                    {type === "add" ? (
                        <button className="add-edit-story-submit-btn" onClick={handleAddOrUpdateClick}>
                            <MdAdd className="add-edit-story-submit-icon" /> ADD STORY
                        </button>
                    ) : (
                        <>
                            <button className="add-edit-story-submit-btn" onClick={handleAddOrUpdateClick}>
                                <MdUpdate className="add-edit-story-submit-icon" /> UPDATE STORY
                            </button>

                            <button className="add-edit-story-submit-btn add-edit-story-delete-btn" onClick={onClose}>
                                <MdDeleteOutline className="add-edit-story-submit-icon" /> DELETE
                            </button>
                        </>
                    )}
                    <button className="add-edit-story-close-btn" onClick={onClose}>
                        <MdClose className="add-edit-story-close-icon" /> 
                    </button>
                </div>
            </div>
        </div>
    </div>
  )
}

export default AddEditTravelStory