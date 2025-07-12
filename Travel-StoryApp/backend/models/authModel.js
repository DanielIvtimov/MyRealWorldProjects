import { userMongoSchema } from "../schemas/user_schemas.js";
import bcrypt from "bcrypt"
import usersValidation from "../services/validations/users_validation.js";
import loginValidation from "../services/validations/users_login_validations.js"
import generateToken from "../services/authServiceTokens/authService.js";

export class User{
    async registerUser({fullName, email, password}){
        const { error, value } = usersValidation.validate({fullName, email, password});
        if(error){
            throw new Error(error.details[0].message);
        }
        const isUser = await userMongoSchema.findOne({email});
        if(isUser){
            throw new Error("User already exists");
        }
        const hashedPassword = await bcrypt.hash(password, 10);
        const user = new userMongoSchema({
            fullName,
            email,
            password: hashedPassword,
            createdOn: value.createdOn,
        });
        const savedUser = await user.save();
        return savedUser;
    }

    async loginUser({email, password}){
        const { error, value } = loginValidation.validate({email, password});
        if(error){
            throw new Error(error.details[0].message);
        }
        const user = await userMongoSchema.findOne({email});
        if(!user){
            throw new Error("User not found");
        }
        const isPassword = await bcrypt.compare(value.password, user.password);
        if(!isPassword){
            throw new Error("Invalid Credentials");
        }
        const accessToken = generateToken(user._id);
        return {
            fullName: user.fullName,
            email: user.email,
            accessToken,
        }
    }

    async getUserDetails(userId){
        const user = await userMongoSchema.findOne({_id: userId});
        if(!user){
            throw new Error("User not found");
        }
        return user;
    }
}