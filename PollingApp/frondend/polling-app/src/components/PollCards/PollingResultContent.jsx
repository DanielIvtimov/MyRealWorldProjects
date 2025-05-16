import React from 'react'
import "./styles/PollingResultContent.css"
import CharAvatar from '../cards/CharAvatar';
import moment from 'moment';

const PollOptionVoteResult = ({label, optionVotes, totalVotes}) => {
    const progress = totalVotes > 0 ? Math.round((optionVotes / totalVotes) * 100) : 0;
    return (
        <div className='poll-option-container'>
            <div className="poll-option-progress" style={{width: `${progress}`}}>
                <span className='poll-option-label'>
                    {label}
                    <span className='poll-option-percentage'>{progress}%</span>
                </span>
            </div>
        </div>
    ) 
}   

const ImagePollResult = ({imgUrl, optionVotes, totalVotes}) => {
    return (
        <div>
            <div className='poll-image-container'>
                <img src={imgUrl} alt="" className='poll-image' />
            </div>
            <PollOptionVoteResult optionVotes={optionVotes} totalVotes={totalVotes} /> 
        </div>
    )
}

const OpenEndedPollResponses = ({profileImgUrl, userFullName, response, createdAt}) => {
    return <div className='poll-response-container'>
        <div className='poll-response-content'>
            {profileImgUrl ? (
                <img src={profileImgUrl} alt="" className="poll-response-avatar" />
            ) : (
                <CharAvatar fullName={userFullName} className="poll-response-char-avatar" />
            )}
            <p className='poll-response-username'>
                {userFullName}{" "}
                <span className='poll-response-dot'>•</span>
                <span className='poll-response-time'>{createdAt}</span>
            </p>
        </div>
        <p className='poll-response-text'>{response}</p>
    </div>
}

const PollingResultContent = ({type, options, voters, responses}) => {

    const totalVotes = typeof voters === "number" ? voters : (Array.isArray(voters) ? voters.length : 0);

    switch(type){
        case "single-choice":
        case "yes/no":
        case "rating":
            return (
                <>
                    {options.map((option, index) => (
                        <PollOptionVoteResult 
                        key={option._id}
                        label={`${option.optionText} ${type === "rating" ? "Star" : ""}`}
                        optionVotes={option.votes || 0}
                        totalVotes={totalVotes}
                        />
                    ))}
                </>
            );
        case "image-based":
            return (
                <div className='image-poll-grid'>
                    {options.map((option, index) => (
                        <ImagePollResult 
                        key={option._id}
                        imgUrl={option.optionText || ""}
                        optionVotes={option.votes}
                        totalVotes={totalVotes}
                        />
                    ))}
                </div>
            );
        case "open-ended":
            return responses.map((response) => {
                return (
                    <OpenEndedPollResponses 
                    key={response._id}
                    profileImgUrl={response.voterId?.profileImageUrl}
                    userFullName={response.voterId?.fullName || ""}
                    response={response.responseText || ""}
                    createdAt={response.createdAt ? moment(response.createdAt).fromNow() : ""}
                    />
                );
            });
        default:
            return null;
    }; 
    

  return (
    <div>PollingResultContent</div>
  )
}

export default PollingResultContent