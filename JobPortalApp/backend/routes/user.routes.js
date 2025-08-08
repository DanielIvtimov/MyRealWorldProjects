import express from "express";
import { UserController } from "../controller/user.controller.js";
import isAuthenticated from "../middlewares/isAuthenticated.js";
import { singleUpload } from "../middlewares/multer.js";

const userRoutes = express.Router();

const userController = new UserController();

userRoutes.post("/register", singleUpload, async (request, response) => {
    await userController.register(request, response);
})
userRoutes.post("/login", async (request, response) => {
    await userController.login(request, response);
})
userRoutes.get("/logout", async (request, response) => {
    await userController.logout(request, response);
})
userRoutes.post("/profile/update", isAuthenticated, async(request, response) => {
    await userController.updateProfile(request, response);
})

export default userRoutes;