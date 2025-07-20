import React, { useState } from 'react'
import "./styles/PasswordInput.css"
import { FaRegEye, FaRegEyeSlash } from "react-icons/fa6";

const PasswordInput = ({ value, onChange, placeholder}) => {

    const [isShowPassword, setIsShowPassword] = useState(false);

    const toggleShowPassword = () => {
        setIsShowPassword(!isShowPassword);
    }

  return (
    <div className="password-input-wrapper">
        <input value={value} onChange={onChange} placeholder={placeholder || "Password"} className="password-input-field" type={isShowPassword ? "text" : "password"} /> 
        {isShowPassword ? (
            <FaRegEye size={22} className="password-toggle-icon" onClick={() => toggleShowPassword()}/>
        ) : (
            <FaRegEyeSlash size={22} className="password-toggle-icon-slash" onClick={() => toggleShowPassword()}/>
        )}
    </div>
  )
}

export default PasswordInput