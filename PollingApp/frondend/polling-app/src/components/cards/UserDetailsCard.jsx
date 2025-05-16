import React from 'react'
import "./styles/UserDetailsCard.css"
import CharAvatar from './CharAvatar'

const StatsInfo = ({label, value}) => {
    return (
        <div className='stats-info'>
            <p className='stats-value'>{value}</p>
            <p className='stats-label'>{label}</p>
        </div>
    )
}

const UserDetailsCard = ({ profileImageUrl, fullname, username, totalPollsVotes, totalPollsCreated, totalPollsBookmarked }) => {
  return (
    <div className='user-card'>
        <div className='user-banner'>
            <div className='user-avatar-wrapper'>
                {profileImageUrl ? <img src={profileImageUrl || ""} alt='Profile Image' className='user-avatar' /> : <CharAvatar fullName={fullname} className="char-avatar" />}
            </div>
        </div>
        <div className='user-info'>
            <div className='user-info-text'>
                <h5 className='user-fullname'>{fullname}</h5>
                <span className='user-username'>@{username}</span>
            </div>
            <div className='stats-container'>
                <StatsInfo label="Polls Created" value={totalPollsCreated || 0} />
                <StatsInfo label="Polls Voted" value={totalPollsVotes || 0} />
                <StatsInfo label="Polls Bookmarked" value={totalPollsBookmarked || 0} />
            </div>
        </div>
    </div>
  )
}

export default UserDetailsCard