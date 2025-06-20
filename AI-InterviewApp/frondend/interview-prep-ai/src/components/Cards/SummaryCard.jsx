import React from 'react'
import "../styles/SummaryCard.css"
import { LuTrash2 } from 'react-icons/lu';
import { getInitials }  from "../../utils/helper"

const SummaryCard = ({
    key,
    colors,
    role,
    topicsToFocus,
    experience,
    questions,
    description,
    lastUpdated,
    onSelect,
    onDelete
}) => {
  return (
    <div className="summary-card" onClick={onSelect}>
        <div className="summary-card-header" style={{background: colors.bgcolor,}}>
            <div className="summary-card-flex-start">
                <div className="summary-card-avatar">
                    <span className="summary-card-avatar-text">
                        {getInitials(role)}
                    </span>
                </div>
                <div className="summary-card-flex-grow">
                    <div className="summary-card-info-header">
                        <div>
                            <h2 className="summary-card-role">{role}</h2>
                            <p className="summary-card-topics">{topicsToFocus}</p>
                        </div>
                    </div>
                </div>
            </div>
            <button className="summary-card-delete-btn" onClick={(e) => {e.stopPropagation(); onDelete();}}>
                <LuTrash2 />
            </button>
        </div>
        <div className="summary-card-content-padding">
            <div className="summary-card-info-row">
                <div className="summary-card-experience-badge">
                    Experience: {experience} {experience === 1 ? "Year" : "Years"}    
                </div>
                <div className="summary-card-qa-badge">
                    {questions} Q&A
                </div>
                <div className="summary-card-last-updated-badge">
                    Last Updated: {lastUpdated}
                </div>
            </div>
            <p className="summary-card-description">
                {description}
            </p>
        </div>
    </div> 
  )
}

export default SummaryCard