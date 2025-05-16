import React, { useState } from 'react'
import "./styles/Navbar.css";
import SideMenu from './SideMenu';
import { HiOutlineMenu, HiOutlineX } from "react-icons/hi"

const Navbar = ({activeMenu}) => {

  const [openSideMenu, setOpenSideMenu] = useState(false);

  return (
    <div className="navbar-container">
      <button className='menu-toggle-button' onClick={() => {setOpenSideMenu(!openSideMenu)}}>{openSideMenu ? <HiOutlineX className='menu-toggle-icon' /> : <HiOutlineMenu className='menu-toggle-icon'/>}</button>
      <h2 className="navbar-title">Polling App</h2>
      {openSideMenu && (
        <div className='small-screen-side-menu'>
          <SideMenu activeMenu={activeMenu} />
        </div>
      )}
    </div>
    
  )
}

export default Navbar