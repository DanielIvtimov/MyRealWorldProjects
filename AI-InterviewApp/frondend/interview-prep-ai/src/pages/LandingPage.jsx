import React, { useState } from 'react';  
import HERO_IMG from '../../src/assets/hero-img.png';
import { API_FEATURES } from "../utils/data";
import { useNavigate } from 'react-router-dom';
import "./styles/LadingPage.css"

const LandingPage = () => {

  const navigate = useNavigate();

  const [openAuthModel, setOpenAuthModel] = useState(false);
  const [currentPage, setCurrentPage] = useState("login");

  const handleCTA = () => {};

  return (
    <>
      <div className="landing-container">
      <div className="blur-box">

      </div>
      <div className="container-custom">
        {/* Header */}
        <header className="header-custom">
          <div className="text-heading">
            Interview Prep AI
          </div>
          <button className="btn-login" onClick={() => setOpenAuthModel(true)}>Login / Sign Up</button>
        </header>
        {/* Hero Content */}
        <div className="hero-flex-container">
          <div className="hero-left-column">
            <div className="ai-powered-wrapper">
              <div className="ai-powered-badge">
                AI Powered
              </div>
            </div>
            <h1 className="hero-title">
              Ace Interviews with <br />
              <span className="highlighted-text-shine">
                AI-Powered
              </span>{" "}
              Learning
            </h1>
          </div>
          <div className="hero-right-column">
            <p className="hero-description">
              Get role-specific questions, expand answers when you need them, dive deeper into concepts, and organize everything your way.
              From prepartion to mastery — your ultimate interview toolkit is here.
            </p>
            <button className="btn-get-started" onClick={handleCTA}>Get Started</button>
          </div>
        </div>
      </div>
    </div>
    </>
  )
}

export default LandingPage