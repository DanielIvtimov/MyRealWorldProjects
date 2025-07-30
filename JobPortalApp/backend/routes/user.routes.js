import express from "express";
import { UserController } from "../controller/user.controller.js";

const userRoutes = express.Router();

const userController = new UserController();

userRoutes.post("/register", async (request, response) => {
    await userController.register(request, response);
})

export default userRoutes;