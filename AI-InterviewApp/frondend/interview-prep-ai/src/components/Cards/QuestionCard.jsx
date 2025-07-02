import React, { useEffect, useRef, useState } from 'react'
import { LuChevronDown, LuPin, LuPinOff, LuSparkles } from "react-icons/lu"
import "../styles/QuestionCard.css"
import AIResponsePreview from '../../pages/InterviewPrep/components/AIResponsePreview';

const QuestionCard = ({ question, answer, onLearnMore, isPinned, onTogglePin}) => {

    const [isExpanded, setIsExpanded] = useState(false);
    const [height, setHeight] = useState(0);
    const contentRef = useRef(null);

    useEffect(() => {
        if(isExpanded){
            const contentHeight = contentRef.current.scrollHeight;
            setHeight(contentHeight + 10);
        } else {
            setHeight(0);
        }
    }, [isExpanded]);

    const toggleExpand = () => {
        setIsExpanded(!isExpanded);
    }

  return (
    <>
        <div className="question-card">
            <div className="question-card-header">
                <div className="question-title-wrapper">
                    <span className="question-label">Q</span>
                    <h3 className="question-text" onClick={toggleExpand}>{question}</h3>
                </div>
                <div className="question-actions">
                    <div className={`custom-toggle ${isExpanded ? "expanded" : "collapsed"}`}>
                        <button className="pin-toggle-button" onClick={onTogglePin}>
                            {isPinned ? (
                                <LuPinOff  className="icon " /> 
                            ) : (
                                <LuPin className="icon" />
                            )}
                        </button>
                        <button className="learn-more-button" onClick={() => {
                            setIsExpanded(true);
                            onLearnMore();
                        }}>
                            <LuSparkles className="icon" />
                            <span className="learn-more-text">Learn More</span>
                        </button>
                    </div>
                    <button className="expand-toggle-button" onClick={toggleExpand}>
                        <LuChevronDown 
                            size={20}
                            className={`icon-transform ${isExpanded ? "rotated" : ""}`}
                        />
                    </button>
                </div>
            </div>
            <div className="question-answer-container" style={{maxHeight: `${height}px`}}>
                <div ref={contentRef} className="answer-content">
                    <AIResponsePreview content={answer} />
                </div>
            </div>
        </div>
    </>
  )
}

export default QuestionCard