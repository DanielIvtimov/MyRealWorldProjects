import mongoose from "mongoose";

const { Schema } = mongoose;

const sessionSchema = new Schema({
    user: {
        type: mongoose.Schema.Types.ObjectId, ref: "User"
    },
    role: {
        type: String,
        required: true,
    },
    experience: {
        type: String,
        required: true,
    },
    topicsToFocus: {
        type: mongoose.Schema.Types.ObjectId, ref: "Question",
    },
}, {
    timestamps: true,
});

export const sessionMongoSchema = mongoose.model("Session", sessionSchema);