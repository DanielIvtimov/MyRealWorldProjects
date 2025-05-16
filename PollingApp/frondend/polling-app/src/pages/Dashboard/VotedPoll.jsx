import React, { useEffect, useState } from 'react'
import DashboardLayout from '../../components/layout/DashboardLayout'
import useUserAuth from '../../hooks/useUserAuth'
import { useNavigate } from 'react-router-dom';
import "./styles/Home.css";
import "./styles/VotedPoll.css";
import axiosInstance from '../../utils/axiosinstance';
import { API_PATHS } from '../../utils/apiPaths';
import PollCard from '../../components/PollCards/PollCard';
import InfiniteScroll from "react-infinite-scroll-component";
import CREATE_ICON from "../../assets/images/my-poll-icon.png";
import EmptyCard from '../../components/cards/EmptyCard';

const PAGE_SIZE = 3;

const VotedPoll = () => {

  useUserAuth();  

  const navigate = useNavigate();

  const [votedPolls, setVotedPolls] = useState([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [loading, setLoading] = useState(false);

  const fetchAllPolls = async () => {
    if(loading) return;
    setLoading(true);
    try{
      const response = await axiosInstance.get(API_PATHS.POLLS.VOTED_POLLS, {
        params: {
          page,
          limit: PAGE_SIZE,
        }
      });
      if(response.data?.polls?.length > 0){
        setVotedPolls((prevPolls) => [...prevPolls, ...response.data.polls]);
        setHasMore(response.data.polls.length === PAGE_SIZE);
      } else {
        setHasMore(false);
      }
    }catch(error){
      console.log("Something went wrong. Please try again.", error);
    }finally{
      setLoading(false);
    }
  }

  const loadMorePolls = () => {
    setPage((prevPage) => prevPage + 1);
  }

  useEffect(() => {
      fetchAllPolls();
    return () => {};
  }, [page]);

  return (
    <DashboardLayout activeMenu="voted polls">
      <div className='header'>
        <h2 className='voted-polls-title'>Voted Polls</h2>
      </div>

      {votedPolls.length === 0 && !loading && (
          <EmptyCard
            imgSrc={CREATE_ICON}
            message="You have not voted on any polls yet! Start exploring and share your opinion by voting on polls now!"
            btnText="Explore"
            onClick={() => navigate("/dashboard")}
          />
        )}

      <InfiniteScroll
        dataLength={votedPolls.length}
        next={loadMorePolls}
        hasMore={hasMore}
        loader={<h4 className="info-text">Loading...</h4>}
        endMessage={<p className="info-text">No more polls to display</p>}
      >

        {votedPolls.map((poll) => (
          <PollCard 
          key={`dashboard_${poll._id}`}
          pollId={poll._id} 
          question={poll.question} 
          type={poll.type} 
          options={poll.options} 
          voters={poll.voters || 0} 
          responses={poll.responses || []} 
          creatorProfileImg={poll.creator.profileImageUrl || null} 
          creatorName={poll.creator.fullName} 
          creatorUsername={poll.creator.username}
          userHasVoted={poll.userHasVoted || false}
          isPollClosed={poll.closed || false}
          createdAt={poll.createdAt || false}
          isMyPoll
          />
        ))}
      </InfiniteScroll>
    </DashboardLayout>
  )
}

export default VotedPoll