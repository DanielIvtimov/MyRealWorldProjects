import React from "react";
import "../styles/RoleInfoHeader.css";

const RoleInfoHeader = ({ role, topicsToFocus, experience, questions, description, lastUpdated }) => {
  return (
    <div className="role-info-header-container">
      <div className="container-custom">
        <div className="role-info-header-content">
          <div className="role-info-items">
            <div className="flex-info-items-flex-grow">
              <div className="role-info-header-row">
                <div>
                  <h2 className="role-info-title">{role}</h2>
                  <p className="role-info-subtitle">{topicsToFocus}</p>
                </div>
              </div>
            </div>
          </div>
          <div className="role-info-stats">
            <div className="experience-badge">
              Experience: {experience} {experience === 1 ? "Year" : "Years"}
            </div>
            <div className="question-badge">{questions} Q&A</div>
            <div className="last-updated-badge">Last Updated {lastUpdated}</div>
          </div>
        </div>
      </div>
      <div className="role-info-image-container">
        <div className="blob-lime" />
        <div className="blob-teal" />
        <div className="blob-cyan" />
        <div className="blob-fuchsia" />
      </div>
    </div>
  );
};

export default RoleInfoHeader;
