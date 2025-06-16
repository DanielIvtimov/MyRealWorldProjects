import { Session } from "../models/sessionModels.js";


export class SessionController{
    constructor(){
        this.sessionModel = new Session();
    }

    async createSession(request, response){
        try{
           const { role, experience, topicsToFocus, description, questions } = request.body;
           const userId = request.user._id;
           const session = await this.sessionModel.createSession({
            userId,
            role,
            experience,
            topicsToFocus,
            description,
            questions,
           });
           return response.status(201).json({message: "Session created successfully",  session});
        }catch(error){
            console.error(error);
            return response.status(500).json({message: "Failed to create session", error: error.message});    
        }
    }

    async getMySessions(request, response){
        try{
           const userId = request.user._id;
           const sessions = await this.sessionModel.getMySessions(userId);
           return response.status(201).json(sessions); 
        }catch(error){
            return response.status(500).json({succcess: false, message: "Interval Server Error "});
        }
    }

    async getSessionById(request, response){
        try{
          const sessionId = request.params.id;
          const session = await this.sessionModel.getSessionById(sessionId);
          if(!session){
            return response.status(404).json({success: false, message: "Session not found"});
          }
          return response.status(200).json({success: true, session});
        }catch(error){
            return response.status(500).json({success: false, message: "Interval Server Error"});   
        }
    }

    async deleteSession(request, response){
        try{
           const sessionId = request.params.id;
           const userId = request.user._id;
           const result = await this.sessionModel.deleteSession(sessionId, userId);
           return response.status(200).json({succcess: true, message: result.message}); 
        }catch(error){
            if(error.message === "Session not found"){
                return response.status(404).json({succcess: false, message: error.message});
            }
            if(error.message === "Not authorized to delete this session"){
                return response.status(401).json({succcess: false, message: error.message});
            }
            return response.status(500).json({succcess: false, message: "Interval Server Error"});
        }
    }
}