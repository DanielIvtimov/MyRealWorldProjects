import React from 'react'
import "./styles/TreadingPolls.css"

const TreadingPolls = ({stats}) => {
  return (
    <div className="treading-container">
        <h6 className="treading-heading">Treading</h6>
        <div className="treading-statistics">
            {stats.map((item) => (
                <div className="treading-item" key={item.id}>
                    <p className="treading-label">{item.label.label}</p>
                    <span className="treading-count">{item.count}</span>
                </div>
            ))}
        </div>
    </div>
  )
}

export default TreadingPolls