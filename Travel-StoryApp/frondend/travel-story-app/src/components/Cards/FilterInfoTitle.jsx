import React from 'react'
import "./styles/FilterInfoTitle.css"
import moment from 'moment'
import { MdOutlineClose } from 'react-icons/md'

const FilterInfoTitle = ({ filterType, filterDates, onClear }) => {

    const DateRangeChip = ({ date }) => {
        const startDate = date?.from ? moment(date?.from).format("Do MMM YYYY") : "N/A";
        const endDate = date?.to ? moment(date?.to).format("Do MMM YYYY") : "N/A";
        return(
            <div className="date-range-chip">
                <p className="date-range-text">
                    {startDate} - {endDate}
                </p>
                <button onClick={onClear} className="date-range-close-button">
                    <MdOutlineClose />
                </button>
            </div>
        )
    }

  return (
    filterType && (
        <div className="filter-info-title-container">
        {filterType === "search" ? (
            <h3 className="filter-info-title-heading">Search Results</h3>
        ) : (
            <div className="filter-info-flex-container">
                <h3 className="filter-info-subheading">Travel Stories from</h3>
                <DateRangeChip date={filterDates} />
            </div>
        )}
    </div>
    )   
  )
}

export default FilterInfoTitle