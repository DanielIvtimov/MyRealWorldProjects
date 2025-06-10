import express from "express";
import { UserController } from "../controllers/authControllers.js";
import userProtect from "../middleware/authMiddleware.js";
import upload from "../middleware/uploadMiddleware.js";

const userRouter = express.Router();

const userController = new UserController();

userRouter.post("/register", async (request, response) => {
    await userController.registerUser(request, response);
});
userRouter.post("/login", async (request, response) => {
    await userController.loginUser(request, response);
})
userRouter.get("/profile", userProtect, async (request, response) => {
    await userController.getUserProfile(request, response);
})
userRouter.post("/upload-image", upload.single("image"), async (request, response) => {
    if(!request.file){
        return response.status(400).json({message: "No file upload"});
    }
    const imageUrl = `${request.protocol}://${request.get("host")}/uploads${request.file.filename}`;
    return response.status(200).json({ imageUrl });
})

export default userRouter; 