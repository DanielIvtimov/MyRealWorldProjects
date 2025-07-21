import React, { useState } from 'react'
import { DayPicker } from "react-day-picker";
import moment from "moment";
import { MdClose, MdOutlineDateRange } from 'react-icons/md';
import "./styles/DateSelector.css"

const DateSelector = ({ date, setDate }) => {

    const [openDatePicker, setOpenDatePicker] = useState(false)

  return (
    <div>
        <button className="date-selector-btn" onClick={() => {
            setOpenDatePicker(true);
        }}>
            <MdOutlineDateRange  className="calendar-icon" />
            {date ? moment(date).format("Do MMM YYYY") : moment().format("Do MMM YYYY")}
        </button>
        {openDatePicker  && <div className="date-picker-container">
            <button className="date-picker-close-btn" onClick={() => {
                setOpenDatePicker(false);
            }}>
                <MdClose className="date-selector-close-icon" />
            </button>
            <DayPicker 
                captionLayout="dropdown-buttons"
                mode="single"
                selected={date}
                onSelect={setDate}
                pagedNavigation
            />
        </div>}
    </div>
  )
}

export default DateSelector