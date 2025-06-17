import dotenv from "dotenv";

import { GoogleGenAI } from "@google/genai";
import { conceptExplainPrompt, questionAnswerPrompt } from "../../utils/prompts.js";
// import conceptExplainPrompt from "../../utils/prompts.js";

dotenv.config();

const ai = new GoogleGenAI({apiKey: process.env.GEMINI_API_KEY});

const generateInterviewQuestions = async (request, response) => {
    try{
        const { role, experience, topicsToFocus, numberOfQuestions } = request.body; 
        if(!role || !experience || !topicsToFocus || !numberOfQuestions) {
            return response.status(400).json({success: false, message: "Missing required fields"});
        }
        const prompt = questionAnswerPrompt(role, experience, topicsToFocus, numberOfQuestions);
        const aiResponse = await ai.models.generateContent({
            model: "gemini-2.0-flash-lite",
            contents: prompt,
        });
        let rawText = aiResponse.text;
        const cleanedText = rawText
            .replace(/^```json\s*/, "")
            .replace(/```$/, "") 
            .trim();
        const data = JSON.parse(cleanedText);
        response.status(200).json(data);
    }catch(error){
      return response.status(500).json({message: "Failed to generate questions", error: error.message});  
    }
}

const generateConceptExplanation = async (request, response) => {
    try{
        const { question } = request.body;
        if(!question){
            return response.status(400).json({message: "Missing required fileds"});
        }
        const prompt = conceptExplainPrompt(question);
        const aiResponse = await ai.models.generateContent({
            model: "gemini-2.0-flash-lite",
            contents: prompt,
        });
        const rawText = aiResponse.text;
        const cleanedText = rawText
            .replace(/^```json\s*/, "")
            .replace(/```$/, "")
            .trim();
        const data = JSON.parse(cleanedText);
        return response.status(200).json(data); 
    }catch(error){
        return response.status(500).json({message: "Failed to generate questions", error: error.message});
    }
}

export default {generateInterviewQuestions, generateConceptExplanation};