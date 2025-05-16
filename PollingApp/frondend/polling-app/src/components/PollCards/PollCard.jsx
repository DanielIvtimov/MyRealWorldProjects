import React, { useCallback, useContext, useState } from 'react'
import { UserContext } from '../../context/UserContext'
import { getPollBookmarked } from '../../utils/helper';
import "./styles/PollCard.css"
import UserProfileInfo from '../cards/UserProfileInfo';
import PollActions from './PollActions';
import PollContent from './PollContent';
import axiosInstance from '../../utils/axiosinstance';
import { API_PATHS } from '../../utils/apiPaths';
import toast from 'react-hot-toast';
import PollingResultContent from './PollingResultContent';

const PollCard = ({
    pollId,
    question,
    type,
    options,
    voters,
    responses,
    creatorProfileImg,
    creatorName,
    creatorUsername,
    userHasVoted,
    isMyPoll,
    isPollClosed,
    createdAt
}) => {

    const { user, onUserVoted, toggleBookmarkId, onPollCreateOrDelete } = useContext(UserContext);

    const [selectedOptionsIndex, setSelectedOptionIndex] = useState(-1);
    const [rating, setRating] = useState(0);
    const [userResponse, setUserResponse] = useState("");

    const [isVoteComplete, setIsVoteComplete] = useState(userHasVoted);

    const [pollResult, setPollResult] = useState({
        options,
        voters,
        responses,
    });

    const isPollBookmarked = getPollBookmarked(pollId, user.bookmarkedPolls || []);

    const [pollBookmarked, setPollBookmarked] = useState(isPollBookmarked);
    const [pollClosed, setPollClosed] = useState(isPollClosed || false);
    const [pollDeleted, setPollDeleted] = useState(false);

    const handleInput = (value) => {
        if(type === "rating") setRating(value);
        else if (type === "open-ended") setUserResponse(value);
        else setSelectedOptionIndex(value);
    }

    const getPostData = useCallback(() => {
        if(type === "open-ended"){
            return {responseText: userResponse, voterId: user._id};
        };
        if(type === "rating"){
            return {optionIndex: rating - 1, voterId: user._id};
        }
        return {optionIndex: selectedOptionsIndex, voterId: user._id}
    }, [type, userResponse, rating, selectedOptionsIndex, user])

    const getPollDetail = async () => {
        try{
           const response = await axiosInstance.get(API_PATHS.POLLS.GET_BY_ID(pollId));
           if(response.data){
            const pollDetails = response.data;
            setPollResult({
                options: pollDetails.options || [],
                voters: pollDetails.voters || 0,
                responses: pollDetails.response || [],
            });
           } 
        }catch(error){
            console.error(error.response?.data?.message || "Error submiting vote");
        }
    }

    const handleVoteSubmit = async () => {
        try{
           const response = await axiosInstance.post(API_PATHS.POLLS.VOTE(pollId), getPostData());
           getPollDetail();
           setIsVoteComplete(true);
           onUserVoted();
           toast.success("Vote submited successfully!"); 
        }catch(error){
         console.error(error.response?.data?.message || "Error submiting vote");   
        }
    }

    const toggleBookmark = async () => {
        try{
           const response = await axiosInstance.post(API_PATHS.POLLS.BOOKMARK(pollId));
            toggleBookmarkId(pollId);
            setPollBookmarked((prev) => !prev);
            toast.success(response.data.message);
        }catch(error){
            console.error(error.response?.data?.message || "Error bookmarking poll")
        }
    }

    const closePoll = async () => {
        try{
           const response = await axiosInstance.post(API_PATHS.POLLS.CLOSE(pollId));
           if(response.data){
            setPollClosed(true);
            toast.success(response.data?.message || "Poll closed Successfully");
           }
        }catch(error){
            toast.error("Something went wrong. Please try again.");
            console.log("Something went wrong. Please try again.", error);   
        }
    }

    const deletePoll = async () => {
        try{
           const response = await axiosInstance.delete(API_PATHS.POLLS.DELETE(pollId)); 
           if(response.data){
            setPollDeleted(true);
            onPollCreateOrDelete()
            toast.success(response.data?.message || "Poll deleted Successfully");
           }
        }catch(error){
            toast.error("Something went wrong. Please try again.");
            console.log("Something went wrong. Please try again.", error);
        }
    }

  return (
    !pollDeleted && (
        <div className="poll-card">
            <div className="flex-container">
                <UserProfileInfo imgUrl={creatorProfileImg} fullname={creatorName} username={creatorUsername} createdAt={createdAt} />
                <PollActions 
                    pollId={pollId}
                    isVoteComplete={isVoteComplete}
                    inputCaptured={!!(userResponse || selectedOptionsIndex >= 0 || rating)}
                    onVoteSubmit={handleVoteSubmit}
                    isBookmarked={pollBookmarked}
                    toggleBookmark={toggleBookmark}
                    isMyPoll={isMyPoll}
                    pollClosed={pollClosed}
                    onClosePoll={closePoll}
                    onDelete={deletePoll}
                />
            </div>
            <div className="poll-card-question-container">
                <p className="poll-card-question">{question}</p>
                <div className="poll-card-question-divider">
                    {isVoteComplete || isPollClosed ? (
                        <PollingResultContent 
                        type={type}
                        options={pollResult.options || []}
                        voters={pollResult.voters}
                        responses={pollResult.responses || []}
                        />
                    ) : (
                        <PollContent 
                        type={type}
                        options={options}
                        selectedOptionsIndex={selectedOptionsIndex}
                        onOptionSelect={handleInput}
                        rating={rating}
                        onRatingChange={handleInput}
                        userResponse={userResponse}
                        onResponseChange={handleInput}
                    />
                    )}
                </div>
            </div>
        </div>
    )
  )
}

export default PollCard