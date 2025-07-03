import React from 'react';
import "../styles/SkeletonLoader.css"

const SkeletonLoader = () => {
  return (
    <div role="status" className="skeleton-loader">
      <div className="skeleton-header" />

      <div className="skeleton-section">
        <div className="skeleton-line" />
        <div className="skeleton-line" />
        <div className="skeleton-line" />
      </div>

      <div className="skeleton-card">
        <div className="skeleton-subline" />
        <div className="skeleton-subline" />
      </div>

      <div role="status" className="skeleton-footer">
        <div className="skeleton-title" />
        <div className="skeleton-section">
          <div className="skeleton-line" />
          <div className="skeleton-line" />
          <div className="skeleton-line" />
        </div>
      </div>
    </div>
  );
};

export default SkeletonLoader;