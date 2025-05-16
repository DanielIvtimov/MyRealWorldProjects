import React, { useState } from 'react'
import "./styles/PollActions.css"
import { FaBookmark, FaRegBookmark } from 'react-icons/fa6'

const PollActions = ({
    pollId,
    isVoteComplete,
    inputCaptured,
    onVoteSubmit,
    isBookmarked,
    toggleBookmark,
    isMyPoll,
    pollClosed,
    onClosePoll,
    onDelete
}) => { 

    const [loading, setLoading] = useState(false);
    
    const handleVoteClick = async () => {
        setLoading(true);
        try{
           await onVoteSubmit();  
        }finally{
            setLoading(false);
        }
    };

  return (
    <div className="poll-actions-container">
        {(isVoteComplete || pollClosed) && (
            <div className="poll-status">
                {pollClosed ? "Closed" : "Voted"}
            </div>
        )}


        {isMyPoll && !pollClosed && (
            <button className="poll-close-button" onClick={onClosePoll} disabled={loading}>Close</button>
        )}

        {isMyPoll && (
            <button className="poll-delete-button" onClick={onDelete} disabled={loading}>Delete</button>
        )}

        <button className="bookmark-btn" onClick={toggleBookmark}>
            {isBookmarked ? (
                <FaBookmark className="text-primary" />
            ) : (
                <FaRegBookmark />
            )}
        </button>
        {inputCaptured && !isVoteComplete && (
            <button className="submit-btn" onClick={handleVoteClick} disabled={loading}>{loading ? "Submitting..." : "Submit"}</button>
        )}
    </div>
  )
}

export default PollActions