import React, { useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'  
import moment from 'moment'
import { AnimatePresence, motion } from "framer-motion";
import { LuCircle, LuCircleAlert, LuListCollapse } from 'react-icons/lu';  
import SpinnerLoader from '../../components/Loader/SpinnerLoader';
import { toast } from "react-hot-toast";
import DashboardLayout from '../../components/layouts/DashboardLayout';
import RoleInfoHeader from './components/RoleInfoHeader';
import axiosInstace from '../../utils/axiosInstace';
import { API_PATHS } from '../../utils/apiPaths';
import "./styles/InterviewPrep.css"
import QuestionCard from '../../components/Cards/QuestionCard';
import AIResponsePreview from './components/AIResponsePreview';
import Drawer from '../../components/Drawer';
import SkeletonLoader from '../../components/Loader/SkeletonLoader';

const InterviewPrep = () => {

  const { sessionId } = useParams();

  const [sessionData, setSessionData] = useState(null);
  const [errorMsg, setErrorMsg] = useState("");

  const [openLeanMoreDrawer, setOpenLeanMoreDrawer] = useState(false);
  const [explanation, setExplanation] = useState(null);

  const [isLoading, setIsLoading] = useState(false);
  const [isUpdateLoader, setIsUpdateLoader] = useState(false);

  const fetchSessionDetailedById = async () => {
    try{
      const response = await axiosInstace.get(API_PATHS.SESSION.GET_ONE(sessionId));
      if(response.data && response.data.session){
        const sortedQuestions = response.data.session.questions?.slice().sort((a, b) => {
          return (b.isPinned === true) - (a.isPinned === true);
        }) || [];
        setSessionData({...response.data.session, questions: sortedQuestions});
      }
    }catch(error){
      console.error("Error:", error);
    }
  }

  const generateConceptExplanation = async (question) => {
    try{
      setErrorMsg("");
      setExplanation(null);
      setIsLoading(true);
      setOpenLeanMoreDrawer(true);
      const response = await axiosInstace.post(API_PATHS.AI.GENERATE_EXPLANATION, {question});
      if(response.data){
        setExplanation(response.data);
      }

    }catch(error){
      setExplanation(null);
      setErrorMsg("Failed to generate explanation, Try again later");
      console.error("Error", error);
    } finally {
      setIsLoading(false);
    }
  }

  const toggleQuestionPinStatus = async (questionId) => {
    try{
      const response = await axiosInstace.post(API_PATHS.QUESTION.PIN(questionId));
      console.log(response);
      if(response.data && response.data.question){
        fetchSessionDetailedById();
      }
    }catch(error){
      console.error("Error", error);
    }
  }

  const uploadMoreQuestions = async () => {};

  useEffect(() => {
    if(sessionId){
      fetchSessionDetailedById();
    }
    return () => {};
  }, [])
 
  return (
    <DashboardLayout>
      <RoleInfoHeader
        role={sessionData?.role || ""}
        topicsToFocus={sessionData?.topicsToFocus || ""}
        experience={sessionData?.experience || "-"}
        questions={sessionData?.questions?.length || "-"}
        description={sessionData?.description || ""}
        lastUpdated={sessionData?.updatedAt ? moment(sessionData.updatedAt).format("Do MMM YYYY"): ""}
      />
      <div className="interview-section">
        <h2 className="interview-title">Interview Q & A</h2>
        <div className="questions-wrapper">
          <div className={`content-area ${openLeanMoreDrawer ? "with-drawer" : "no-drawer"}`}>
            <AnimatePresence>
              {sessionData?.questions?.map((data, index) => {
                return (
                  <motion.div 
                    key={data._id || index}
                    initial={{opacity: 0, y: -20 }}
                    animate={{opacity: 1, y: 0 }}
                    exit={{opacity: 0, scale: 0.95 }}
                    transition={{
                      duration: 0.4,
                      type: "spring",
                      stiffness: 100,
                      delay: index * 0.1,
                      damping: 15,
                    }}
                    layout
                    layoutId={`question-${data._id || index}`}
                  >
                    <>
                      <QuestionCard 
                        question={data?.question}
                        answer={data?.answer}
                        onLearnMore={() => 
                          generateConceptExplanation(data.question)
                        }
                        isPinned={data?.isPinned}
                        onTogglePin={() => toggleQuestionPinStatus(data._id)}
                      />
                    </>
                  </motion.div>
                );
              })}
            </AnimatePresence>
          </div>
        </div>
        <div>
          <Drawer
            isOpen={openLeanMoreDrawer}
            onClose={() => setOpenLeanMoreDrawer(false)}
            title={!isLoading && explanation?.title}
          >
            {errorMsg && (
              <p className="drawer-error-message">
                <LuCircleAlert className="drawer-error-icon" /> {errorMsg}
              </p>
            )}
            {isLoading && <SkeletonLoader />}
            {!isLoading && explanation && (
              <AIResponsePreview content={explanation?.explanation} />
            )}
          </Drawer>
        </div>
      </div>
    </DashboardLayout>
  )
}

export default InterviewPrep