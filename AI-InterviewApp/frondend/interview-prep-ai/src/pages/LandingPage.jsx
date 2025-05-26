import React, { useState } from "react";
import HERO_IMG from "../../src/assets/hero-img.png";
import { API_FEATURES } from "../utils/data";
import { useNavigate } from "react-router-dom";
import "./styles/LadingPage.css";
import { LuSparkles } from "react-icons/lu";

const LandingPage = () => {
  const navigate = useNavigate();

  const [openAuthModel, setOpenAuthModel] = useState(false);
  const [currentPage, setCurrentPage] = useState("login");

  const handleCTA = () => {};

  return (
    <>
      <div className="landing-container">
        <div className="blur-box"></div>
        <div className="container-custom">
          {/* Header */}
          <header className="header-custom">
            <div className="text-heading">Interview Prep AI</div>
            <button
              className="btn-login"
              onClick={() => setOpenAuthModel(true)}
            >
              Login / Sign Up
            </button>
          </header>
          {/* Hero Content */}
          <div className="hero-flex-container">
            <div className="hero-left-column">
              <div className="ai-powered-wrapper">
                <div className="ai-powered-badge">
                  <LuSparkles /> AI Powered
                </div>
              </div>
              <h1 className="hero-title">
                Ace Interviews with <br />
                <span className="highlighted-text-shine">AI-Powered</span>{" "}
                Learning
              </h1>
            </div>
            <div className="hero-right-column">
              <p className="hero-description">
                Get role-specific questions, expand answers when you need them,
                dive deeper into concepts, and organize everything your way.
                From prepartion to mastery — your ultimate interview toolkit is
                here.
              </p>
              <button className="btn-get-started" onClick={handleCTA}>
                Get Started
              </button>
            </div>
          </div>
        </div>
      </div>

      <div className="hero-image-wrapper">
        <div>
          <section className="hero-image-section">
            <img src={HERO_IMG} alt="Hero Image" className="hero-image" />
          </section>
        </div>
      </div>

      <div className="features-section">
        <div className="features-container">
          <section className="features-section-part">
            <h2 className="features-title">Features That Make You Shine</h2>
            <div className="features-list">
              <div className="features-grid">
                {API_FEATURES.slice(0, 3).map((feature) => (
                  <div key={feature.id} className="features-card">
                    <h3 className="features-title">{feature.title}</h3>
                    <p className="features-description">{feature.description}</p>
                  </div>
                ))}
              </div>
              <div className="features-grid-partTwo">
                {API_FEATURES.slice(3).map((feature) => (
                  <div key={feature.id} className="features-card-partTwo">
                    <h3 className="features-title-partTwo">{feature.title}</h3>
                    <p className="features-description-partTwo">{feature.description}</p>
                  </div>
                ))}
              </div>
            </div>
          </section>
        </div>
        <div className="features-footer">Made with ❤️... Happy Coding</div>
      </div>
    </>
  );
};

export default LandingPage;
