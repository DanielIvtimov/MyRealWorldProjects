import React from "react";
import "./styles/RoleInfoHeader.css"

const RoleInfoHeader = ({ role, topicsToFocus, experience, questions, description, lastUpdated }) => {
  return (
    <div className="role-info-header">
      <div className="role-info-header-container">
        <div className="role-info-header-main">
          <div className="role-info-header-details">
            <div className="role-info-header-title-selection">
              <div className="role-info-header-text"> 
                <div>
                  <h2 className="role-info-header-role-title">{role}</h2>
                  <p className="role-info-header-topics">{topicsToFocus}</p>
                </div>
              </div>
            </div>
          </div>
          <div className="role-info-header-stats-wrapper">
            <div className="role-info-header-stats">
              <div className="role-info-header-experience">
                Experience: {experience} {experience === 1 ? "Year" : "Years"}
              </div>
              <div className="role-info-header-questions">{questions} Q&A</div>
              <div className="role-info-header-updated">Last Updated {lastUpdated}</div>
          </div>
          </div>
        </div>
      </div>
      <div className="role-info-header-spacers">
        <div className="role-info-header-spacer spacer-lime" />
        <div className="role-info-header-spacer spacer-teal" />
        <div className="role-info-header-spacer spacer-cyan" />
        <div className="role-info-header-spacer spacer-fuchsia" />
      </div>
    </div>
  );
};

export default RoleInfoHeader;
