import jwt from "jsonwebtoken";
import { ConflictError } from "../handlingErrors/appError.js";

const generateToken = (userId) => {
    if(!process.env.SECRET_KEY){
        throw new ConflictError("Missing SECRET_KEY environment variable");
    }
    return jwt.sign({ id: userId }, process.env.SECRET_KEY, { expiresIn: "1d" });
}

export default generateToken;