import React from 'react'
import moment from "moment";
import CharAvatar from './CharAvatar'
import "./styles/UserProfileInfo.css"

const UserProfileInfo = ({imgUrl, fullname, username, createdAt}) => {
  return (
    <div className="user-profile-info">
      {imgUrl ? (
        <img src={imgUrl} alt="" className='user-profile-img' />
      ) : (
        <CharAvatar fullname={fullname} className="char-avatar" />
      )}
      <div>
        <p className="user-profile-text">
          {fullname} <span className="user-profile-separator">•</span>
          <span className="user-profile-time">
            {" "}
            {createdAt && moment(createdAt).fromNow()}
          </span>
        </p>
        <span className="user-profile-username">
          @{username}
        </span>
      </div>
    </div>
  )
}

export default UserProfileInfo