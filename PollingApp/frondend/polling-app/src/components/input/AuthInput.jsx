import React, { useState } from 'react'
import { FaRegEye, FaRegEyeSlash } from "react-icons/fa6";
import "./styles/AuthInput.css";

const AuthInput = ({value, onChange, label, placeholder, type}) => {

  const [showPassword, setShowPassword] = useState(false);
  
  const toggleShowPassword = () => {
    setShowPassword(!showPassword);
  }

  return (
    <>
        <div>
            <label className='label-text'>{label}</label>
            <div className='input-box'>
                <input type={type == "password" ? showPassword ? "text" : "password" : "text"} placeholder={placeholder} className='input-field' value={value} onChange={(e) => onChange(e)} />
                {type === "password" && (
                  <>
                    {showPassword ? (
                    <FaRegEye size={22} className="eye-icon" onClick={() => toggleShowPassword()} />
                  ) : (
                    <FaRegEyeSlash size={22} className='eye-icon-disabled' onClick={() => toggleShowPassword()}/>
                  )} 
                  </>
                )}
            </div>
        </div>
    </>
  )
}

export default AuthInput

  