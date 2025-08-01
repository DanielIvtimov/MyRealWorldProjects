import mongoose from "mongoose";

const { Schema } = mongoose;

const jobSchema = new Schema({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    requirements: [{
        type: String,
    }],
    salary: {
        type: Number,
        required: true,
    },
    location: {
        type: String,
        required: true,
    },
    jobType: {
        type: String,
        required: true,
    },
    position: {
        type: Number,
        required: true,
    },
    company: {
        type: mongoose.Schema.Types.ObjectId,
        ref: "Company",
        required: true,
    },
    created_by: {
        type: mongoose.Schema.Types.ObjectId,
        ref: "User",
        required: true,
    },
    applications: [
        {
            type: mongoose.Schema.Types.ObjectId,
            ref: "Application",
        }
    ]
}, {timestamps: true});

export const JobSchema = mongoose.model("Job", jobSchema);