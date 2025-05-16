const moongose = require("mongoose");

const connectDB = async () => {
    try{
        await moongose.connect(process.env.MONGO_URI, {});
        console.log("MongoDB connected");
    }catch(error){
        console.error("Error connecting to MongoDB", error)
        process.exit(1);
    }
}

module.exports = connectDB;