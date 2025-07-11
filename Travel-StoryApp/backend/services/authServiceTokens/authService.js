import jwt from "jsonwebtoken";

const generateToken = (userId) => {
    if(!process.env.ACCESS_TOKEN_SECRET){
        throw new Error("Missing ACCESS_TOKEN_SECRET environment varibale");
    }
    return jwt.sign({id: userId}, process.env.ACCESS_TOKEN_SECRET, {expiresIn: "72h"});
}

export default generateToken;   