import React, { useState } from 'react'
import "./styles/AddEditTravelStory.css"
import { MdAdd, MdClose, MdDeleteOutline, MdUpdate } from 'react-icons/md'
import DateSelector from '../../components/Input/DateSelector';
import ImageSelector from '../../components/Input/ImageSelector';
import TagInput from '../../components/Input/TagInput';
import axiosInstance from '../../utils/axiosInstace';
import moment from 'moment';
import { toast } from 'react-toastify';
import uploadImage from '../../utils/uploadImage';

const AddEditTravelStory = ({ storyInfo, type, onClose, getAllTravelStories }) => {

    const [title, setTitle] = useState(storyInfo?.title || "");
    const [storyImg, setStoryImg] = useState(storyInfo?.imageUrl || null);
    const [story, setStory] = useState(storyInfo?.story || "");
    const [visitedLocation, setVisitedLocation] = useState(storyInfo?.visitedLocation || []);
    const [visitedDate, setVisitedDate] = useState(storyInfo?.visitedDate ? new Date(storyInfo.visitedDate) : null);

    const [error, setError] = useState("");

    const addNewTravelStory = async () => {
        try{
           let imageUrl = "";
           if(storyImg){
            const imgUploadResponse = await uploadImage(storyImg);
            imageUrl = imgUploadResponse.imageUrl || "";
           } 
           const response = await axiosInstance.post("/api/travelStory/add-travel-story", {
            title, 
            story,
            imageUrl: imageUrl || "",
            visitedLocation,
            visitedDate: visitedDate ? visitedDate.valueOf() : Date.now(),
           });  
           if(response.data && response.data.story){
            toast.success("Story Added Successfully");
            getAllTravelStories();
            onClose();
           }
        } catch (error) {
            
        }
    };

    const updateTravelStory = async () => {

        const storyId = storyInfo._id;

        try{
           let imageUrl = "";
           let postData = {
            title, 
            story,
            imageUrl: storyInfo.imageUrl || "",
            visitedLocation,
            visitedDate: visitedDate ? visitedDate.valueOf() : Date.now(),
           }

           if(typeof storyImg === "object"){
            const imgUploadResponse = await uploadImage(storyImg);
            imageUrl = imgUploadResponse.imageUrl || "";
            postData = {...postData, imageUrl: imageUrl,};
           }

           const response = await axiosInstance.post("/api/travelStory/edit-story/" + storyId, postData);  
           if(response.data && response.data.story){
            toast.success("Story Updated Successfully");
            getAllTravelStories();
            onClose();
           }
        }catch(error){
            if(error.response && error.response.data && error.response.data.message){
                setError(error.response.data.message);
            } else {
                setError("An unexpected error occured. Please try again. ");
            }
        }
    };

    const handleAddOrUpdateClick = () => {
        console.log("Input Data:", {title, storyImg, story, visitedLocation, visitedDate});
        if(!title){
            setError("Please enter the title");
            return
        }
        if(!story){
            setError("Please enter the story");
            return;
        }
        setError("");
        if(type === "edit"){
            updateTravelStory();
        } else {
            addNewTravelStory();
        }
    };

    const handleDeleteStoryImg = async () => {
        try{
           const deleteImgResponse = await axiosInstance.delete("/delete-image", {
            params: {
                imageUrl: storyInfo.imageUrl,
            },
           });
           if(deleteImgResponse.data){
            const storyId = storyInfo._id;
            const postData = {
                title,
                story,
                visitedLocation,
                visitedDate: moment().valueOf(),
                imageUrl: "",
            };
            const response = await axiosInstance.post("/api/travelStory/edit-story/" + storyId, postData);
            if(response.data && response.data.story){
                toast.success("Image deleted and story updated successfully");
                setStoryImg(null);
                getAllTravelStories();
            } else {
                toast.error("Failed to update the story after deleting image");
            }
           } else {
            toast.error("Failed to delete the image on server");
           }
        }catch(error){
            console.error("Error deleting image:", error);
        }
    };

  return (
    <div className="add-edit-story-wrapper">
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
                        </>
                    )}
                    <button className="add-edit-story-close-btn" onClick={onClose}>
                        <MdClose className="add-edit-story-close-icon" /> 
                    </button>
                </div>
                {error && (<p className="error-message">{error}</p>)}
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