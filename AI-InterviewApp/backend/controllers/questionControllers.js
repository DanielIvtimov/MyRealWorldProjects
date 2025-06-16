import { Question } from "../models/questionModels.js";

export class QuestionController{
    constructor(){
        this.questionModel = new Question();
    }

    async addQuestionToSession(request, response){
        try{
           const {sessionId, questions} = request.body;
           const createdQuestions = await this.questionModel.addQuestionToSession({sessionId, questions});
           return response.status(201).json(createdQuestions); 
        }catch(error){
            const status = error.message === "Session not found" || error.message === "Invalid input data" ? 400 : 500 
            return response.status(status).json({message: error.message || "Interval Server Error"});
        }
    }

    async togglePinQuestion(request, response){
        try{
           const question = await this.questionModel.togglePinQuestion(request.params.id);
           return response.status(200).json({success: true, question}); 
        }catch(error){
            const status = error.message === "Question not found" ? 404 : 500;
            return response.status(status).json({success: false, message: error.message || "Interval Server Error"});   
        }
    }

    async updateQuestionNote(request, response){
        try{
           const { note } = request.body;
           const question = await this.questionModel.updateQuestionNote(request.params.id, note);
           return response.status(200).json({success: true, question}); 
        }catch(error){
            const status = error.message === "Question not found" ? 404 : 500
            return response.status(status).json({success: false, message: error.message || "Interval Server Error"});
        }
    }
}