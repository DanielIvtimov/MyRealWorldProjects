import express, { request } from "express";
import { UserController } from "../controllers/authController.js";

const userRoutes = express.Router();

const userController = new UserController();

userRoutes.post("/create-account", async (request, response) => {
    await userController.createUser(request, response);
});

userRoutes.post("/login-account", async (request, response) => {
    await userController.loginUser(request, response);
})

export default userRoutes;