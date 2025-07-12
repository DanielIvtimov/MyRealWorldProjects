import { User } from "../models/authModel.js";
import generateToken from "../services/authServiceTokens/authService.js";

export class UserController{
    constructor(){
        this.userModel = new User();
    }
    
    async createUser(request, response){
        try{
           const user = await this.userModel.registerUser(request.body);
           const accessToken = generateToken(user._id);
           return response.status(201).json({
            error: false,
            user: {
                fullName: user.fullName,
                email: user.email,
            },
            accessToken,
            message: "Registration Successful",
           });
        }catch(error){
            return response.status(400).json({
                error: true,
                message: error.message,
            });
        }
    }

    async loginUser(request, response){
        try{
           const userData = await this.userModel.loginUser(request.body);
           const { accessToken, ...user } = userData;
           return response.status(200).json({
            error: false,
            message: "Login Successful",
            user,
            accessToken
           }) 
        }catch(error){
            return response.status(400).json({error: true, message: error.message});
        }
    }

    async getUserDetails(request, response){
        try{
           const userId = request.user.id;
           const user = await this.userModel.getUserDetails(userId);
           return response.status(200).json({ user }); 
        }catch(error){
            return response.status(401).json({error: true, message: error.message || "Unauthorized"});
        }
    }
}

