import { User } from "../model/user.model.js";

export class UserController {
    constructor(){
        this.userModel = new User();
    }

    async register(request, response){
        try{
           const user = await this.userModel.register(request.body);
           return response.status(201).json({ message: "User registered successfully", success: true, user}); 
        }catch(error){
            const statusCode = error.statusCode || 400;
            return response.status(statusCode).json({message: error.message || "Registration failed", success: false});
        }
    }

    async login(request, response){
        try{
           const { token, user } = await this.userModel.login(request.body);
           return response.status(200).cookie("token", token, {
            maxAge: 1 * 24 * 60 * 60 * 1000,
            httpOnly: true,
            sameSite: "strict",
           }).json({message: `Welcome back ${user.fullname}`, success: true, user, accessToken: token});
        }catch(error){
            const statusCode = error.statusCode || 400;
            return response.status(statusCode).json({message: error.message, success: false});
        }
    }

    async logout(request, response){
        try{
           return response.status(200).cookie("token", "", {maxAge: 0}).json({message: "Logged out Successfully", success: true}); 
        }catch(error){
         return response.status(500).json({message: "Interval Server Error", success: false,});   
        }
    }
}

