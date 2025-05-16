import React, { useState } from 'react'
import "./styles/HeaderWithFilter.css"
import {IoCloseOutline, IoFilterOutline} from "react-icons/io5";
import { POLL_TYPE } from '../../utils/data';

const HeaderWithFilter = ({title, filterType, setFilterType}) => {

    const [open, setOpen] = useState(false);

  return (
    <div>
        <div className='header-container'>  
            <h2 className='header-title'>{title}</h2>
            <button onClick={() => {
                if(filterType !== "") setFilterType("");
                setOpen(!open);
            }} className={`filter-button ${open ? "open" : ""}`}>{filterType !== "" ? (
                <>
                    <IoCloseOutline className="icon-large" />
                    Clear
                </>
            ) : (
                <>
                    <IoFilterOutline className="icon-large" />
                    Filter
                </>
            )}</button>
        </div>
        {open && (
            <div className="filter-options-container">
                {[{label: "All", value: ""}, ...POLL_TYPE].map((type) => (
                    <button key={type.value} className={`filter-option-button ${filterType == type.value ? "active" : ""}`} onClick={() => {setFilterType(type.value)}}>{type.label}</button>
                ))}
            </div>
        )}
    </div>
  )
}

export default HeaderWithFilter