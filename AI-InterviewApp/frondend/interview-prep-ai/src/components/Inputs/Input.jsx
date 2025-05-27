import React, { useState } from "react";
import { FaRegEye, FaRegEyeSlash } from "react-icons/fa6";
import "../styles/Input.css"

const Input = ({ value, onChange, label, placeholder, type }) => {

  const [showPassword, setShowPassword] = useState(false);

  const togglePassword = () => {
    setShowPassword(!showPassword);
  };

  return <div>
    <label className="input-label">{label}</label>
    <div className="input-box">
        <input
         type={type == "password" ? (showPassword ? "text" : "password") : type}
         placeholder={placeholder}
         className="input-field"
         value={value}
         onChange={(e) => onChange(e)}
        />
        {type === "password" && (
            <>
                {showPassword ? (
                    <FaRegEye
                     size={22}
                     className="icon-clickable"
                     onClick={() => togglePassword()} 
                    />
                ) : (
                    <FaRegEyeSlash 
                    size={22}
                    className="icon-clickable-muted"
                    onClick={() => togglePassword()} 
                    />
                )}
            </>
        )}
    </div>
  </div>;
};

export default Input;
