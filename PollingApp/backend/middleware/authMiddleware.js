const jwt = require("jsonwebtoken");
const User = require("../models/User");

exports.protect = async(request, response, next) => {
    let token = request.headers.authorization?.split(" ")[1];
    if(!token) return response.status(401).json({message: "Not authorized, no token"});
    try{
       const decoded = jwt.verify(token, process.env.JWT_SECRET);
       request.user = await User.findById(decoded.id).select("-password");
       next(); 
    }catch(error){
        response.status(401).json({meesage: "Not authorized, token failed"});
    }
}