import jwt from "jsonwebtoken";

const isAuthenticated = async (request, response, next) => {
    try{
       const token = request.cookies.token;
       if(!token){
        return response.status(401).json({message: "User not authenticated", success: false,});
       } 
       const decode = jwt.verify(token, process.env.SECRET_KEY);
       if(!decode){
        return response.status(401).json({message: "Invalid token", success: false,})
       }
       request.id = decode.id;
       next();
    }catch(error){
        console.log(error);
    }
}

export default isAuthenticated;