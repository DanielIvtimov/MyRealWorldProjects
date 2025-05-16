import React from 'react'
import "./styles/AuthLayout.css";
import UI_ELEMENT from "../../assets/images/ui-element-img.png";
import CARD_1 from "../../assets/images/auth-card-1.png";
import CARD_2 from "../../assets/images/auth-card-2.png";
import CARD_3 from "../../assets/images/auth-card-3.png";

const AuthLayout = ({children}) => {

  return (
    <div className='auth-container'>
        <div className='auth-content'>
            <h2 className='app-title'>Polling App</h2>
            {children}
        </div>
        <div className='auth-image-section'>
            <img src={UI_ELEMENT} className='ui-element-right' />
            <img src={UI_ELEMENT} className='ui-element-left' />

            <img src={CARD_1} className='card-top'/>
            <img src={CARD_3} className='card-middle'/>
            <img src={CARD_2} className='card-bottom'/>
        </div>
    </div>
  )
}

export default AuthLayout