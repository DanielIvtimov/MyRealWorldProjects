import React, { useEffect, useState } from 'react'
import Navbar from '../../components/Input/Navbar'
import { useNavigate } from "react-router-dom";
import axiosInstace from "../../utils/axiosInstace.js"
import "./styles/Home.css"
import TravelStoryCard from '../../components/Cards/TravelStoryCard.jsx';
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { MdAdd } from "react-icons/md";
import Modal from "react-modal"
import AddEditTravelStory from './AddEditTravelStory.jsx';
import ViewTravelStory from './ViewTravelStory.jsx';
import EmptyCard from '../../components/Cards/EmptyCard.jsx';
import EmptyImg from "../../assets/temp-imgs/add-story.svg"
import { DayPicker } from 'react-day-picker';
import moment from 'moment';
import FilterInfoTitle from '../../components/Cards/FilterInfoTitle.jsx';
import { getEmptyCardMessage } from '../../utils/helper.js';

const Home = () => {

  const [userInfo, setUserInfo] = useState(null);
  const [allStories, setAllStories] = useState([]);

  const [searchQuery, setSearchQuery] = useState("");
  const [filterType, setFilterType] = useState("");

  const [dateRange, setDateRange] = useState({form: null, to: null});

  const [openAddEditModal, setOpenAddEditModal] = useState({
    isShown: false,
    type: "add",
    data: null,
  })
  const [openViewModal, setOpenViewModal] = useState({
    isShown: false,
    data: null,
  });

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

  const handleEdit = (data) => {
    setOpenAddEditModal({ isShown: true, type: "edit", data: data});
  }

  const handleViewStory = (data) => {
    setOpenViewModal({ isShown: true, data });
  }

  const updateIsFavourite = async (storyData) => {
    const storyId = storyData._id;
    try {
      const response = await axiosInstace.put("/api/travelStory/update-is-favourite/" + storyId, {
        isFavourite: !storyData.isFavourite,
      });
      if(response.data && response.data.story){
        toast.success("Story Updated Successfully")
        if(filterType === "search" && searchQuery){
          onSearchStory(searchQuery);
        } else if (filterType === "date"){
          filterStoriesByDate(dateRange);
        } else {
          getAllTravelStories();
        }
      }
    }catch(error){
      console.log("An unexpected error occurred. Please try again");
    }
  }

  const deleteTravelStory = async (data) => {
    const storyId = data._id;
    try{
      const response = await axiosInstace.delete("/api/travelStory/delete-story/" + storyId); 
      if(response.data && !response.data.error){
        toast.error("Story Deleted Successfully");
        setOpenViewModal((prevDate) => ({...prevDate, isShown: false }));
        getAllTravelStories();
      } 
    }catch(error){
      console.log("An unexpected error occured. Please try again.");
    }
  }

  const onSearchStory = async (query) => {
    try{
      const response = await axiosInstace.get("/api/travelStory/search", {
        params: {
          query,
        },
      });
      if(response.data && response.data.stories){
        setFilterType("search");
        setAllStories(response.data.stories);
      }
    }catch(error){
      console.log("An unexpected error occured. Please try again");
    }
  }

  const handleClearSearch = () => {
    setFilterType("");
    getAllTravelStories();
  };

  const filterStoriesByDate = async (day) => {
    try{
      const startDate = day.from ? moment(day.from).valueOf() : null;
      const endDate = day.to ? moment(day.to).valueOf() : null
      if(startDate && endDate){
        const response = await axiosInstace.get("/api/travelStory/travel-stories/filter", {
          params: { startDate, endDate },
        });
        if(response.data && response.data.stories){
          setFilterType("date");
          setAllStories(response.data.stories);
        }
      }
    }catch(error){
      console.log("An unexpected error occured. Please try again");
    }
  };

  const handleDayClick = (day) => {
    setDateRange(day);
    filterStoriesByDate(day);
  };

  const resetFilter = () => {
    setDateRange({form: null, to: null});
    setFilterType("");
    getAllTravelStories();
  }

  useEffect(() => {
    getUserInfo();
    getAllTravelStories();
  }, [])

  return (
    <>
      <Navbar
        userInfo={userInfo} 
        searchQuery={searchQuery} 
        setSearchQuery={setSearchQuery} 
        onSearchNote={onSearchStory} 
        handleClearSearch={handleClearSearch}
       />

      <div className="home-container">
        <FilterInfoTitle
          filterType={filterType}
          filterDates={dateRange}
          onClear={() => {
            resetFilter();
          }}
        />
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
                    onClick={() => handleViewStory(item)}
                    onFavouriteClick = {() => updateIsFavourite(item)}
                    />  
                  )
                })}
              </div>
            ) : (
              <>
                <EmptyCard imgSrc={EmptyImg} message={getEmptyCardMessage(filterType)} />
              </>
            )}
          </div>
          <div className="home-side-panel">
            <div className="home-side-panel-box">
              <div className="home-side-panel-space">
                <DayPicker 
                  captionLayout="dropdown-buttons"
                  mode="range"
                  selected={dateRange}
                  onSelect={handleDayClick}
                  pagedNavigation 
                />
              </div>
            </div>
          </div>
        </div>
      </div>
          
      <Modal
        isOpen={openAddEditModal.isShown}
        onRequestClose={() => {}}
        style={{
          overlay: {
            backgroundColor: "rgba(0,0,0,0.2",
            zIndex: 999,
          },
        }}
        appElement={document.getElementById("root")}
        className="modal-container"
      >
        <AddEditTravelStory 
          type={openAddEditModal.type}
          storyInfo={openAddEditModal.data}
          onClose={() => {
            setOpenAddEditModal({ isShown: false, type: "add", data: null})
          }}
          getAllTravelStories={getAllTravelStories}
        /> 
        
      </Modal>

      <Modal
        isOpen={openViewModal.isShown}
        onRequestClose={() => {}}
        style={{
          overlay: {
            backgroundColor: "rgba(0,0,0,0.2",
            zIndex: 999,
          },
        }}
        appElement={document.getElementById("root")}
        className="modal-container"
      >
        <ViewTravelStory
          storyInfo={openViewModal.data || null}
          onClose={() => {
            setOpenViewModal((prevDate) => ({...prevDate, isShown: false, }));
          }}
          onEditClick={() => {
            setOpenViewModal((prevDate) => ({...prevDate, isShown: false }));
            handleEdit(openViewModal.data || null);
          }}
          onDeleteClick={() => {
            deleteTravelStory(openViewModal.data || null);
          }}   
        />
        
      </Modal>

      <button
        className="home-edit-story-button" 
        onClick={() => {
          setOpenAddEditModal({ isShown: true, type: "add", data: null});
        }}
      >
        <MdAdd className="home-edit-story-icon" />
      </button>
      <ToastContainer />
    </>
  )
}

export default Home