import { UserSchema } from "../schemas/user_schemas.js";
import bcrypt from "bcryptjs";
import userValidation from "../services/validations/user.validations.js";
import { ValidationError, ConflictError, UnauthorizedError, NotFoundError } from "../services/handlingErrors/appError.js";
import generateToken from "../services/authTokenService/auth.token.js";
import userUpdateValidations from "../services/validations/userUpdate.validations.js";
import userLoginValidation from "../services/validations/userLogin.validations.js";


export class User {
    async register(userData){
        const { error } = userValidation.validate(userData);
        if(error){
            throw new ValidationError(error.details[0].message);
        }
        const existingUser = await UserSchema.findOne({email: userData.email});
        if(existingUser){
            throw new ConflictError("User already exists with this email");
        }
        const hashedPassword = await bcrypt.hash(userData.password, 10);
        const user = await UserSchema.create({
            fullname: userData.fullname,
            email: userData.email,
            phoneNumber: userData.phoneNumber,
            password: hashedPassword,
            role: userData.role,
        });
        user.password = undefined;
        return user;
    }

    async login(userData){
        const { error } = userLoginValidation.validate(userData);
        if(error){
            throw new ValidationError(error.details[0].message);
        }
        const user = await UserSchema.findOne({ email: userData.email });
        if(!user){
            throw new UnauthorizedError("Incorrect email or password");
        }
        const isPasswordMatch = await bcrypt.compare(userData.password, user.password);
        if(!isPasswordMatch){
            throw new UnauthorizedError("Incorrect email or password");
        }
        if(userData.role !== user.role){
            throw new ConflictError("Account doesn't exist with the current role.");
        }
        const token = generateToken(user._id);
        const safeUser = {
            _id: user._id,
            fullname: user.fullname,
            email: user.email,
            phoneNumber: user.phoneNumber,
            role: user.role,
            profile: user.profile
        };
        return { token, user: safeUser}
    }

    async updateProfile(userId, data){
        const { fullname, email, phoneNumber, bio, skills } = data;  
        const { error } = userUpdateValidations.validate(data);
        if(error){
            throw new ValidationError(error.details[0].message);
        }
        const user = await UserSchema.findById(userId);
        if(!user){
            throw new NotFoundError("User not found");
        }
        let skillsArray;
        if(skills){
            skillsArray = skills.split(",");
        }
        if(fullname) user.fullname = fullname
        if(email) user.email = email
        if(phoneNumber) user.phoneNumber = phoneNumber
        if(bio) user.profile.bio = bio
        if(skills) user.profile.skills = skillsArray;
        await user.save();
        return {
            _id: user._id,
            fullname: user.fullname,
            email: user.email,
            phoneNumber: user.phoneNumber,
            role: user.role,
            profile: user.profile,
        }
    }
}

