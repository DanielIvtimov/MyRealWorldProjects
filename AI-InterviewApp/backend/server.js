import express from "express";
import cors from "cors";
import path, { dirname } from "path";
import dotenv from "dotenv";
import { fileURLToPath } from "url";
import connectDB from "./config/db.js";
import authRoutes from "./routes/authRoutes.js";
import sessionRoutes from "./routes/sessionRoutes.js";
import questionRoutes from "./routes/questionRoutes.js";
import userProtect from "./middleware/authMiddleware.js";
import generateInterviewQuestionsAndExplanation from "./services/customHelper/generateInterviewQuestionsAndExplanation.js";

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

dotenv.config();

const app = express();

//Middleware to handle CORS 
app.use(
    cors({
        origin: "*",
        methods: ["GET", "POST", "PUT", "DELETE", "PATCH"],
        allowedHeaders: ["Content-Type", "Authorization"],
    })
);

//Middleware 
app.use(express.json());

//Routes Prefix
app.use("/api/auth", authRoutes);
app.use("/api/sessions", sessionRoutes);
app.use("/api/questions", questionRoutes);

app.use("/api/ai/generate-questions", userProtect, generateInterviewQuestionsAndExplanation.generateInterviewQuestions);
app.use("/api/ai/generate-explanation", userProtect, generateInterviewQuestionsAndExplanation.generateConceptExplanation);

//Server uploads folder
app.use("/uploads", express.static(path.join(__dirname, "uploads")));

const PORT = process.env.PORT || 5000;

const startServer = async () => {
    await connectDB();
    app.listen(PORT, "localhost", () => {
        console.log(`Server is up and running on port ${PORT}`);
    })
}

startServer();