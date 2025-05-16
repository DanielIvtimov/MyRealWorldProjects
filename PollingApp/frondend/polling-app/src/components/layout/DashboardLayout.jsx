import React, { useContext } from 'react'
import Navbar from './Navbar'
import SideMenu  from "./SideMenu";
import "./styles/DashboardLayout.css"
import UserDetailsCard from '../cards/UserDetailsCard';
import { UserContext } from '../../context/UserContext';
import TreadingPolls from './TreadingPolls';

const DashboardLayout = ({children, activeMenu, stats, showStats}) => {

  const { user } = useContext(UserContext);

  return (
    <div>
        <Navbar activeMenu={activeMenu} />
        {user && (
          <div className='dashboard-container'>
          <div className='hide-on-small'>
            <SideMenu activeMenu={activeMenu}/>
          </div>
          <div className='dashboard-content'>
            {children}
          </div>
          <div className='user-details-container'>
            <UserDetailsCard profileImageUrl={user && user.profileImageUrl} fullname={user && user.fullName} username={user && user.username} totalPollsVotes={user && user.totalPollsVotes} totalPollsCreated={user && user.totalPollsCreated} totalPollsBookmarked={user && user.totalPollsBookmarked}/>
            {showStats && stats?.length > 0 && <TreadingPolls stats={stats} />}
          </div>
        </div>
        )}
    </div>
  )
}

export default DashboardLayout