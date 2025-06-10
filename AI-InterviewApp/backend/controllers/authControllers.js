import { User } from "../models/authModels.js";
import generateToken from "../services/authServices.js";

export class UserController{
    constructor(){
        this.userModel = new User();
    }

    async registerUser(request, response){
        try{
           const {name, email, password, profileImageUrl} = request.body;
           const user = await this.userModel.registerUser(name, email, password, profileImageUrl);
           return response.status(200).json({
            _id: user._id,
            name: user.name,
            email: user.email,
            profileImageUrl: user.profileImageUrl,
            token: generateToken(user._id),
           });
        }catch(error){
            return response.status(500).json({message: "Interval server error", error: error.message});
        }
    };
    
    async loginUser(request, response){
        try{
           const {email, password} = request.body;
           const user = await this.userModel.loginUser(email, password);
           return response.status(200).json(user); 
        }catch(error){
           return response.status(401).json({message: "Server error", error: error.message});
        }
    };
    
    async getUserProfile(request, response){
        try{
           const user = await this.userModel.getUserProfile(request.user.id);
           return response.status(200).json(user); 
        }catch(error){
            return response.status(404).json({message: "User not found"});   
        }
    };
}

