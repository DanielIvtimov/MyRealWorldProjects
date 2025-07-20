import React from 'react'
import "./styles/ProfileInfo.css"
import { getInitials } from '../../utils/helper'

const ProfileInfo = ({userInfo, onLogout}) => {
  return (
    userInfo && (
        <div className="profile-info-container">
        <div className="profile-avatar">
            {getInitials(userInfo ? userInfo.fullName : "")}
        </div>
        <div className="">
            <p className="profile-name">{userInfo?.fullName || ""}</p>
            <button className="logout-button" onClick={onLogout}>
                Logout  
            </button>
        </div>
    </div>
    )
  )
}

export default ProfileInfo