import mongoose from "mongoose";

const { Schema } = mongoose;

const UserSchema = new Schema({
    fullName: {
        type: String,
        required: true
    },
    email: {
        type: String,
        required: true,
        unique: true,
    },
    password: {
        type: String,
        required: true,
    },
    createdOn: {
        type: Date,
        default: Date.now,  
    },
})

export const userMongoSchema = mongoose.model("User", UserSchema);