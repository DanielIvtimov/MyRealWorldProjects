import React, { useContext, useState } from 'react'
import DashboardLayout from '../../components/layout/DashboardLayout'
import "./styles/CreatePoll.css"
import useUserAuth from "../../hooks/useUserAuth";
import { UserContext } from '../../context/UserContext';
import { POLL_TYPE } from "../../utils/data";
import OptionInput from '../../components/input/OptionInput';
import OptionImageSelector from '../../components/input/OptionImageSelector';
import uploadImage from '../../utils/uploadImage';
import axiosInstance from '../../utils/axiosinstance';
import { API_PATHS } from '../../utils/apiPaths';
import toast from "react-hot-toast";

const CreatePoll = () => {

  useUserAuth();

  const { user, onPollCreateOrDelete } = useContext(UserContext);

  const [pollData, setPollData] = useState({
    question: "",
    type: "",
    options: [],
    imageOptions: [],
    error: ""
  })

  const handleValueChange = (key, value) => {
    setPollData((prev) => ({
      ...prev, [key]: value,
    }))
  };

  const clearData = () => {
    setPollData({
      question: "",
      type: "",
      options: [],
      imageOptions: [],
      error: "",
    })
  }

  const updateImageAndGetLink = async (imageOptions) => {
    const optionPromises = imageOptions.map(async (imageOption) => {
      try{
        const imgUploadRes = await uploadImage(imageOption.file);
        return imgUploadRes.imageUrl || "";
      }catch(error){
       toast.error(`Error uploading image: ${imageOption.file.name}`);
       return "";  
      }
    })
    const optionArr = await Promise.all(optionPromises);
    return optionArr;
  }

  const getOptions = async () => {
    switch(pollData.type){
      case "single-choice":
        return pollData.options;
      case "image-based":
        return await updateImageAndGetLink(pollData.imageOptions)
      default: 
        return [];
    }
  }


  const handleCreatePoll = async () => {
    const { question, type, options, error, imageOptions } = pollData;
    if(!question || !type){
      console.log("CREATE:", {question, type, options, error});
      handleValueChange("error", "Question & Type are required"); 
      return;
    }
    if(type === "single-choice" && options.length < 2){
      handleValueChange("error", "Enter at two options");
      return;
    }
    if(type === "image-based" && imageOptions.length < 2){
      handleValueChange("error", "Enter at two options");
      return;
    }
    const optionData = await getOptions();
    try{
      const response = await axiosInstance.post(API_PATHS.POLLS.CRAETE, {
        question,
        type,
        options: optionData,
        creatorId: user._id,
      });
      if(response){
        toast.success("Poll Created Successfully");
        onPollCreateOrDelete();
        clearData();
      }
    }catch(error) {
      if(error.response && error.response.data.message){
        toast.error(error.response.data.message);
        handleValueChange("error", error.response.data.message);
      }else{
        handleValueChange("error", "Something went wrong. Please try agai");
      }
    }
  };

  return (
    <DashboardLayout activeMenu='create poll'> 
      <div className='create-poll-container'>
        <h2 className='create-poll-title'>Create Poll</h2>
        <div className='poll-question-container'>  
          <label className='poll-question-label'>QUESTION</label>
          <textarea placeholder="What's in your mind" className='poll-question-input' rows={4} value={pollData.question} onChange={({ target }) => handleValueChange("question", target.value)}></textarea>
        </div>
        <div className='poll-question-container'>
          <label className='poll-question-label'>POLL TYPE</label>
          <div className='poll-type-options-container'>
            {POLL_TYPE.map((item) => (
              <div key={item.value} className={`poll-option-chip ${pollData.type === item.value ? "selected" : ""}`} onClick={() => handleValueChange("type", item.value)}>{item.label}</div>
            ))}
          </div>
        </div>
        {pollData.type === "single-choice" && (
          <div className='poll-options-container'>
            <label className='poll-options-label'>OPTIONS</label> 
            <div className='poll-options-input-container'>
              <OptionInput optionList={pollData.options} setOptionList={(value) => {handleValueChange("options", value)}} />
            </div>
          </div>
        )}
        {pollData.type === "image-based" && (
          <div className='poll-options-container'>
            <label className='poll-options-label'>IMAGE OPTIONS</label>  
            <div className='poll-options-input-container'>
              <OptionImageSelector imageList={pollData.imageOptions} setImageList={(value) => {handleValueChange("imageOptions", value)}} />
            </div>
          </div>
        )}
        {pollData.error && (
          <p className='poll-error-message'>
            {pollData.error}
          </p>
        )}
        <button className='poll-create-btn' onClick={handleCreatePoll}>CREATE</button>
      </div>
    </DashboardLayout>
  )
}

export default CreatePoll