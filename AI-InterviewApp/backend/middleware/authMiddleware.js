import jwt from "jsonwebtoken";
import { usersMongoSchema } from "../schemas/user_schemas.js";

const userProtect = async (request, response, next) => {
    try{
        let token = request.headers.authorization;
        if(token && token.startsWith("Bearer")){
            token = token.split(" ")[1];
            const decoded = jwt.verify(token, process.env.JWT_SECRET);
            request.user = await usersMongoSchema.findById(decoded.id).select("-password");
            next();
        } else {
            response.status(401).json({message: "Not authorized, no token"});
        }
    }catch(error){
        response.status(401).json({message: "Token failed", error: error.message});
    }
} 

export default userProtect;