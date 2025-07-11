import express from "express";
import cors from "cors";
import dotenv from "dotenv";
import connectDB from "./config/db.js";
import userRoutes from "./routes/authRouter.js";

dotenv.config();

const app = express();

app.use(express.json());

app.use(cors({
    origin: "*",
    methods: ["GET", "POST", "PUT", "DELETE", "PATCH"],
    allowedHeaders: ["Content-Type", "Authorization"],
}));

app.use("/api/auth", userRoutes);

const PORT = process.env.PORT || 8000;

const startServer = async () => {
    await connectDB();
    app.listen(PORT, "localhost", () => {
        console.log(`Server is up and running on port ${PORT}`);
    })
}

startServer();