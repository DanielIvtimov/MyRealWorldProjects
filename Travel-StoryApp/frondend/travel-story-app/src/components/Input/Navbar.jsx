import React from 'react'
import LOGO from "../../assets/temp-imgs/travel-story-header-logo.png"
import "./styles/Navbar.css"
import ProfileInfo from '../Cards/ProfileInfo'
import { useNavigate } from 'react-router-dom'
import SearchBar from './SearchBar'

const Navbar = ({ userInfo, searchQuery, setSearchQuery, onSearchNote, handleClearSearch }) => {

    const navigate = useNavigate();
    const isToken = localStorage.getItem("token");

    const onLogout = () => {
        localStorage.clear();
        navigate("/login");
    }

    const handleSearch = () => {
      if(searchQuery){
        onSearchNote(searchQuery);
      }
    };

    const onClearSearch = () => {
      handleClearSearch();
      setSearchQuery("");
    };

  return (
    <div className="navbar-container">
        <img src={LOGO} alt="travel-story" className="logo-img"/>
        {isToken && (
          <>
            <SearchBar
              value={searchQuery}
              onChange={({target}) => {setSearchQuery(target.value)}}
              handleSearch={handleSearch}
              onClearSearch={onClearSearch}
            />
            <ProfileInfo userInfo={userInfo} onLogout={onLogout} />{" "}
          </>
        )}
    </div>
  )
}

export default Navbar