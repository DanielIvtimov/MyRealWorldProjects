import React, { useState } from 'react'
import { useNavigate } from 'react-router-dom';
import Input from "../../components/Inputs/Input";
import "./styles/CreateSessionForm.css"
import SpinnerLoader from '../../components/Loader/SpinnerLoader';
import axiosInstace from '../../utils/axiosInstace';
import { API_PATHS } from '../../utils/apiPaths';

const CreateSessionForm = () => {

    const [formData, setFormData] = useState({
        role: "",
        experience: "",
        topicsToFocus: "",
        description: "",
    });
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);

    const navigate = useNavigate();

    const handleChange = (key, value) => {
        setFormData((prevData) => ({
            ...prevData, [key]: value,
        }));
    };

    const handleCreateSession = async (event) => {
        event.preventDefault();
        
        const { role, experience, topicsToFocus } = formData;
        if(!role || !experience || !topicsToFocus){
            setError("Please fill all the required fields");
            return;
        }
        setError("");
        setIsLoading(true);
        
        try{
           const aiResponse = await axiosInstace.post(API_PATHS.AI.GENERATE_QUESTIONS, {role, experience, topicsToFocus, numberOfQuestions: 10});
           const generatedQuestions = aiResponse.data.map(q => ({
            question: q.questions,
            answer: q.answer,
           }));
           const response = await axiosInstace.post(API_PATHS.SESSION.CREATE, {...formData, questions: generatedQuestions});
           if(response.data?.session?._id){
            navigate(`/interview-prep/${response.data?.session?._id}`);
           }
        }catch(error){
            if(error.response && error.response.data.message){
                setError(error.response.data.message);
            } else {
                setError("Something went wrong. Please try again");
            }
        } finally {
            setIsLoading(false);
        }
    }

  return (
    <div className="create-session-container">
        <h3 className="create-session-title">
            Start a New Interview Journey 
        </h3>
        <p className="create-session-subtitle">
            Fill out a few details and unlock your personalized set of 
            interview questions!
        </p>
        <form onSubmit={handleCreateSession} className="create-session-form">
            <Input
                value={formData.role}
                onChange={({target}) => handleChange("role", target.value)}
                label="Target Role"
                placeholder="(e.g., Frontend Developer, UI/UX Designer, etc.)"
                type="text"
            />
            <Input 
                value={formData.experience}
                onChange={({target}) => handleChange("experience", target.value)} 
                label="Years of Experience"
                placeholder="(e.g., 1 year, 3 years, 5+ years)"
                type="number"
            />
            <Input
                value={formData.topicsToFocus}
                onChange={({target}) => handleChange("topicsToFocus", target.value)}
                label="Topics to Focus On"
                placeholder="(Comma-separeted, e.g., React, Node.js, MongoDB)"
                type="text"
            />
            <Input 
                value={formData.description}
                onChange={({target}) => handleChange("description", target.value)}
                label="Description"
                placeholder="(Any specific goals or notes for this session)"
                type="text"
            />

            {error && <p className="error-message">{error}</p>}

            <button type="submit" className="creat-session-createBtn" disabled={isLoading}>
                {isLoading && <SpinnerLoader />} Create Session
            </button>
        </form>
    </div>
  )
}

export default CreateSessionForm