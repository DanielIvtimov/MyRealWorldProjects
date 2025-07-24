import React from "react";
import "./styles/ViewTravelStory.css";
import { MdAdd, MdClose, MdDeleteOutline, MdUpdate } from "react-icons/md";
import moment from "moment";
import { GrMapLocation } from "react-icons/gr";

const ViewTravelStory = ({storyInfo,  onClose, onEditClick, onDeleteClick}) => {
  return (
    <div className="view-story-wrapper">
      <div className="view-story-header">
        <div>
            <div className="view-story-actions">
                <button className="view-story-submit-btn" onClick={onEditClick} >
                    <MdUpdate className="view-story-submit-icon" /> UPDATE STORY
                </button>
                <button className="view-story-submit-btn view-story-delete-btn" onClick={onDeleteClick} >    
                    <MdDeleteOutline className="view-story-submit-icon" />  Delete
                </button>
                <button className="view-story-close-btn" onClick={onClose}>
                    <MdClose className="view-story-close-icon" />
                </button>
            </div>
        </div>
      </div>
      <div>
        <div className="view-story-content">
            <h1 className="view-story-title">
                {storyInfo && storyInfo.title}
            </h1>
            <div className="view-story-meta">
                <span className="view-story-date">  
                    {storyInfo && moment(storyInfo.visitedDate).format("Do MMM YYYY")}
                </span>
                <div className="view-story-location">
                    <GrMapLocation className="view-story-location-icon" />       
                    {storyInfo && storyInfo.visitedLocation.map((item, index) => storyInfo.visitedLocation.length == index + 1 ? `${item}` : `${item}`)}
                </div>
            </div>

            <img
                src={storyInfo && storyInfo.imageUrl}
                alt="Selected"
                className="view-story-image"
            />

            <div className="view-story-spacer">
                <p className="view-story-description">{storyInfo.story}</p>
            </div>
        </div>
      </div>
    </div>
  );
};

export default ViewTravelStory;
