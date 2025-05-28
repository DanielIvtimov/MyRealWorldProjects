import mongoose from "mongoose";

const { Schema } = mongoose;

const questionSchema = new Schema({
    session: {
        type: mongoose.Schema.Types.ObjectId, ref: "Session"
    },
    question: String,
    answer: String,
    note: String,
    isPinned: {
        type: Boolean, 
        default: false,
    }
},{
    timestamps: true,
});

export const questionMongoSchema = mongoose.model("Question", questionSchema);