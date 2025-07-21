import React from 'react'
import "./styles/TravelStoryCard.css"
import { GrMapLocation } from "react-icons/gr";
import { FaHeart } from "react-icons/fa";
import moment from "moment";

const TravelStoryCard = ({
    imgUrl,
    title,
    story,
    date,
    visitedLocation,
    isFavourite,
    onEdit,
    onClick,
    onFavouriteClick,
}) => {
  return (
    <div className="travel-story-card">
        <img src={imgUrl} alt={title} className="travel-story-image" onClick={onClick} /> 
        <button className="travel-story-favorite-button" onClick={onFavouriteClick}>
            <FaHeart className={`travel-story-favorite-icon ${isFavourite ? "active" : ""}`} />
        </button>
        <div className="travel-story-content" onClick={onClick}>
            <div className="travel-story-meta">
                <div className="travel-story-meta-spacer">
                    <h6 className="travel-story-title">{title}</h6>
                    <span className="travel-story-date">
                        {date ? moment(date).format("Do MMM YYYY") : "-"}
                    </span>
                </div>
            </div>
            <p className="travel-story-excerpt">{story?.slice(0, 60)}</p>
            <div className="travel-story-location-tag">
                <GrMapLocation className="travel-story-location-icon" />
                {visitedLocation.map((item, index) => visitedLocation.length === index + 1 ? `${item}` : `${item}, `)}
            </div>
        </div>  
    </div>
  )
}

export default TravelStoryCard