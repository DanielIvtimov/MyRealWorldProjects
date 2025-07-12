import express, { request, response } from "express";
import { UserController } from "../controllers/authController.js";
import authenticateToken from "../utilities.js";

const userRoutes = express.Router();

const userController = new UserController();

userRoutes.post("/create-account", async (request, response) => {
    await userController.createUser(request, response);
});

userRoutes.post("/login-account", async (request, response) => {
    await userController.loginUser(request, response);
})

userRoutes.get("/get-user", authenticateToken, async (request, response) => {
    await userController.getUserDetails(request, response);
})

export default userRoutes;  