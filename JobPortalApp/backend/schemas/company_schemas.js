import mongoose from "mongoose";

const { Schema } = mongoose;

const companySchema = new Schema({
    name: {
        type: String,
        required: true,
        unique: true,
    },
    description: {
        type: String,
    },
    website: {
        type: String,
    },
    location: {
        type: String,
    },
    logo: {
        type: String,
    },
    userId: {
        type: mongoose.Schema.Types.ObjectId,
        ref: "User",
        required: true,
    }
}, {timestamps: true});

export const CompanySchema = mongoose.model("Company", companySchema);