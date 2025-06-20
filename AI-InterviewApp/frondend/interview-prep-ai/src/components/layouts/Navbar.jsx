import React from 'react'
import ProfileInfoCard from '../Cards/ProfileInfoCard'
import { Link } from 'react-router-dom'
import "../styles/Navbar.css"

const Navbar = () => {
  return (
    <div className="navbar-container">
        <div className="navbar-inner-container">
            <Link to="/dashboard" className="navbar-link">
                <h2 className="navbar-title">
                    Interview Prep AI
                </h2>
            </Link>
            <ProfileInfoCard />
        </div>
    </div> 
  )
}

export default Navbar