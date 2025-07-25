import React from 'react'
import "./styles/SearchBar.css"
import { FaMagnifyingGlass } from "react-icons/fa6";
import { IoMdClose } from "react-icons/io";

const SearchBar = ({ value, onChange, handleSearch, onClearSearch }) => {
  return (
    <div className="search-bar-container">
        <input 
         type="text"
         placeholder="Search Notes"
         className="search-bar-input"
         value={value}
         onChange={onChange}  
        />
        {
            value && <IoMdClose className="search-bar-clear-icon" onClick={onClearSearch} />    
        }
        <FaMagnifyingGlass className="search-bar-icon" onClick={handleSearch} />
    </div>
  )
}

export default SearchBar