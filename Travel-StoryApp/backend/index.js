import express from "express";
import cors from "cors";
import dotenv from "dotenv";
import connectDB from "./config/db.js";
import userRoutes from "./routes/authRouter.js";
import travelStoryRoutes from "./routes/travelStoryRouter.js";
import upload from "./multer.js";
import { fileURLToPath } from "url";
import path from "path";

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();

app.use(express.json());

app.use(cors({
    origin: "*",
    methods: ["GET", "POST", "PUT", "DELETE", "PATCH"],
    allowedHeaders: ["Content-Type", "Authorization"],
}));

app.use("/api/auth", userRoutes);
app.use("/api/travelStory", travelStoryRoutes);

//Route to handle image upload
app.post("/image-upload", upload.single("image"), async(request, response) => {
    try{
       if(!request.file){
        return response.status(400).json({error: true, message: "No image uploaded"});
       }
       const imageUrl = `http://localhost:8000/uploads/${request.file.filename}`;
       response.status(201).json({ imageUrl });
    }catch(error){
     response.status(500).json({ error: true, message: error.message});   
    }
})

// Serve static file from the uploads and assests directory
app.use("/uploads", express.static(path.join(__dirname, "uploads")));
app.use("/assets", express.static(path.join(__dirname, "assets")));

const PORT = process.env.PORT || 8000;

const startServer = async () => {
    await connectDB();
    app.listen(PORT, "localhost", () => {
        console.log(`Server is up and running on port ${PORT}`);
    })
}

startServer();