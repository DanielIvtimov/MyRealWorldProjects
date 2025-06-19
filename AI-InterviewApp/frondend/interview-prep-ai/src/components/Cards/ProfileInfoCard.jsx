import React, { useContext } from 'react'
import { UserContext } from '../../context/userContext'
import { useNavigate } from 'react-router-dom';
import "../styles/ProfileInfoCard.css"

const ProfileInfoCard = () => {

    const {user, clearUser} = useContext(UserContext);
    const navigate = useNavigate();

    const handleLogout = () => {
        localStorage.clear();
        clearUser();
        navigate("/");
    }

  return (
    <div className="profile-info-row">
        <img src={user.profileImageUrl} alt="" className="profile-image" />
        <div>
            <div className="profile-name">
                {user.name || ""}
            </div>
            <button className="profile-logout-button" onClick={handleLogout}>Logout</button>
        </div>
    </div>
  )
}

export default ProfileInfoCard