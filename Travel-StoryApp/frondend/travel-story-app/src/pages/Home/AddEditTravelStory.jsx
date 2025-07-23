import React, { useState } from 'react'
import "./styles/AddEditTravelStory.css"
import { MdAdd, MdClose, MdDeleteOutline, MdUpdate } from 'react-icons/md'
import DateSelector from '../../components/Input/DateSelector';
import ImageSelector from '../../components/Input/ImageSelector';
import TagInput from '../../components/Input/TagInput';

const AddEditTravelStory = ({ storyInfo, type, onClose, getAllTravelStories }) => {

    const [title, setTitle] = useState("");
    const [storyImg, setStoryImg] = useState(null);
    const [story, setStory] = useState("");
    const [visitedLocation, setVisitedLocation] = useState([]);
    const [visitedDate, setVisitedDate] = useState(null);

    const [error, setError] = useState("");

    const handleAddOrUpdateClick = () => {
        console.log("Input Data:", {title, storyImg, story, visitedLocation, visitedDate})
    };

    const handleDeleteStoryImg = () => {};

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

                            {/* <button className="add-edit-story-submit-btn add-edit-story-delete-btn" onClick={onClose}>
                                <MdDeleteOutline className="add-edit-story-submit-icon" /> DELETE
                            </button> */}
                        </>
                    )}
                    <button className="add-edit-story-close-btn" onClick={onClose}>
                        <MdClose className="add-edit-story-close-icon" /> 
                    </button>
                </div>
            </div>
        </div>

        <div>
            <div className="add-edit-story-content">
                <label className="story-title-label">TITLE</label>
                <input type="text" className="story-title-input" placeholder="A Day at the Great Wall" value={title} onChange={({target}) => setTitle(target.value)} />
                <div className="add-edit-story-date-wrapper">
                    <DateSelector date={visitedDate} setDate={setVisitedDate} />
                </div>
                <ImageSelector 
                    image={storyImg}
                    setImage={setStoryImg}
                    handleDeleteImg={handleDeleteStoryImg}
                />
                <div className="story-extra-inputs">
                    <label className="story-location-label">STORY</label>
                    <textarea 
                        type="text"
                        className="story-textarea"
                        placeholder="Your Story"
                        rows={10}
                        value={story}
                        onChange={({target}) => setStory(target.value)}
                    ></textarea>
                </div>
                <div className="vertical-gap">
                    <label className="story-help-text"></label>
                    <TagInput tags={visitedLocation} setTags={setVisitedLocation} />
                </div>
            </div>  
        </div>
    </div>
  )
}

export default AddEditTravelStory