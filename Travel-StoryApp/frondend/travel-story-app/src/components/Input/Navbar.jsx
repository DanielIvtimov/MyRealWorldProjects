import React from 'react'
import LOGO from "../../assets/temp-imgs/travel-story-header-logo.png"
import "./styles/Navbar.css"
import ProfileInfo from '../Cards/ProfileInfo'
import { useNavigate } from 'react-router-dom'

const Navbar = ({userInfo}) => {

    const navigate = useNavigate();
    const isToken = localStorage.getItem("token");

    const onLogout = () => {
        localStorage.clear();
        navigate("/login");
    }

  return (
    <div className="navbar-container">
        <img src={LOGO} alt="travel-story" className="logo-img"/>
        {isToken && <ProfileInfo userInfo={userInfo} onLogout={onLogout} />}
    </div>
  )
}

export default Navbar