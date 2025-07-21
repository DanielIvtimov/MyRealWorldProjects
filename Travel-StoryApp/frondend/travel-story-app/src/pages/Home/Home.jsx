import React, { useEffect, useState } from 'react'
import Navbar from '../../components/Input/Navbar'
import { useNavigate } from "react-router-dom";
import axiosInstace from "../../utils/axiosInstace.js"
import "./styles/Home.css"
import TravelStoryCard from '../../components/Cards/TravelStoryCard.jsx';

const Home = () => {

  const [userInfo, setUserInfo] = useState(null);
  const [allStories, setAllStories] = useState([]);

  const navigate = useNavigate()

  const getUserInfo = async () => {
    try{
      const response = await axiosInstace.get("/api/auth/get-user");
      if(response.data && response.data.user){
        setUserInfo(response.data.user);
      }
    }catch(error){
      if(error.response.status === 401){
        localStorage.clear();
        navigate("/login");
      }
    }
  }

  const getAllTravelStories = async () => {
    try{
      const response = await axiosInstace.get("/api/travelStory/get-all-stories");
      if(response.data && response.data.stories){
        setAllStories(response.data.stories);
      }
    }catch(error){
      console.log("An unexpected error occurred. Please try again");
    }
  }

  const handleEdit = (data) => {}

  const handleViewStory = (data) => {}

  const updateIsFavourite = async (storyData) => {}

  useEffect(() => {
    getUserInfo();
    getAllTravelStories();
  }, [])

  return (
    <>
      <Navbar userInfo={userInfo} />

      <div className="home-container">
        <div className="home-content-row">
          <div className="home-main-panel">
            {allStories.length > 0 ? (
              <div className="travel-stories-grid">
                {allStories.map((item) => {
                  return (
                    <TravelStoryCard 
                    key={item._id}
                    imgUrl={item.imageUrl}
                    title={item.title}
                    story={item.story}
                    date={item.visitedDate}
                    visitedLocation={item.visitedLocation}
                    isFavourite={item.isFavourite}
                    onEdit={() => handleEdit(item)}
                    onClick={() => handleViewStory(item)}
                    onFavouriteClick = {() => updateIsFavourite(item)}
                    />  
                  )
                })}
              </div>
            ) : (
              <>
                Empty Card here
              </>
            )}
          </div>
          <div className="home-side-panel"></div>
        </div>
      </div>
    </>
  )
}

export default Home