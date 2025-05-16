import React, { useContext } from 'react'
import { SIDE_MENU_DATA } from "../../utils/data";
import "./styles/SideMenu.css"
import { useNavigate } from "react-router-dom";
import { UserContext } from '../../context/UserContext';
 
const SideMenu = ({activeMenu}) => {

    const { clearUser } = useContext(UserContext);

    const navigate = useNavigate();

    const handleClick = (route) => {
        if(route === "logout"){
            handleLogout();
            return
        }
        navigate(route);
    }

    const handleLogout = () => {
        localStorage.clear();
        clearUser();
        navigate("/login");
    }

  return (
    <div className='side-menu-container'>
        {SIDE_MENU_DATA.map((item) => (
            <button key={item.id} className={`menu-button ${activeMenu == item.label ? "active" : ""}`} onClick={() => handleClick(item.path)}><item.icon className='menu-icon' /> {item.label}</button>
        ))}
    </div>
  )
}

export default SideMenu