import { usersMongoSchema } from "../schemas/user_schemas.js";
import bcrypt from "bcryptjs";
import generateToken from "../services/authServices.js";

export class User{
    async registerUser(name, email, password, profileImageUrl){
        const userExist = await usersMongoSchema.findOne({email});
        if(userExist){
            throw new Error("User already exists");
        }
        const salt = await bcrypt.genSalt(10);
        const hashedPassword = await bcrypt.hash(password, salt);
        const user = await usersMongoSchema.create({
            name,
            email,
            password: hashedPassword,
            profileImageUrl,
        });
        return user;
    };

    async loginUser(email, password){
        const user = await usersMongoSchema.findOne({email});
        if(!user){
            throw new Error("Invalid email or password");
        }
        const isMatch = await bcrypt.compare(password, user.password);
        if(!isMatch){
            throw new Error("Invalid email or password");
        }
        return {
            _id: user._id,
            name: user.name,
            email: user.email,
            profileImageUrl: user.profileImageUrl,
            token: generateToken(user._id)
        };
    };
    async getUserProfile(userId){    
        const user = await usersMongoSchema.findById(userId).select("-password");
        if(!user){
            throw new Error("User not found");
        }
        return user;
    };
}

