const User = require("../models/User");
const jwt = require("jsonwebtoken");
const bcrypt = require("bcryptjs");
const Poll = require("../models/Poll");

const generateToken = (id) => {
    return jwt.sign({ id }, process.env.JWT_SECRET, { expiresIn: "1h" });
};

exports.registerUser = async (request, response) => {
    const { fullName, username, email, password, profileImageUrl } = request.body;

    if (!fullName || !username || !email || !password) {
        return response.status(400).json({ message: "All fields are required" });
    }

    const usernameRegex = /^[a-zA-Z0-9-]+$/;
    if (!usernameRegex.test(username)) {
        return response.status(400).json({
            message: "Invalid username. Only alphanumeric characters and hyphens are allowed. No spaces are permitted",
        });
    }

    try {
        const existingUser = await User.findOne({ email });
        if (existingUser) {
            return response.status(400).json({ message: "Email already in use" });
        }

        const existingUsername = await User.findOne({ username });
        if (existingUsername) {
            return response.status(400).json({ message: "Username not available. Try another one" });
        }

        const user = await User.create({
            fullName,
            username,
            email,
            password,
            profileImageUrl,
        });

        response.status(201).json({
            id: user._id,
            user,
            token: generateToken(user._id),
        });
    } catch (error) {
        response.status(500).json({ message: "Error registering user", error: error.message });
    }
};

exports.loginUser = async(request, response) => {
    
    const {  email, password, } = request.body;
    
    if(!email || !password){
        return response.status(400).json({message: "All fields are required"});
    }

    try{
        const user = await User.findOne({ email });
        if(!user || !(await user.comparePassword(password))){
            return response.status(400).json({message: "Invalid credentials"});
        }
        const totalPollsCreated = await Poll.countDocuments({creator: user._id});
        const totalPollsVotes = await Poll.countDocuments({voters: user._id});
        const totalPollsBookmarked = user.bookmarkedPolls.length;
        response.status(200).json({id: user._id, user: {...user.toObject(), totalPollsCreated,  totalPollsVotes,  totalPollsBookmarked}, token: generateToken(user._id)});
    }catch(error){
        response.status(500).json({ message: "Error login", error: error.message });
    }

}

exports.getUserInfo = async(request, response) => {
    try{
       const user = await User.findById(request.user.id).select("-password");
       if(!user){
        return response.status(404).json({message: "User not found"});
       }
       const totalPollsCreated = await Poll.countDocuments({creator: user._id});
        const totalPollsVotes = await Poll.countDocuments({voters: user._id});
        const totalPollsBookmarked = user.bookmarkedPolls.length;
       const userInfo = {
        ...user.toObject(),
        totalPollsCreated,
        totalPollsVotes,
        totalPollsBookmarked,
       };
       response.status(200).json(userInfo);
    }catch(error){
        response.status(500).json({ message: "Error", error: error.message });
    }
}