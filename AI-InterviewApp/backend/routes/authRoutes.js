import express from "express";
import { UserController } from "../controllers/authControllers.js";

const userRouter = express.Router();

const userController = new UserController();

userRouter.post("/register", async () => {
    await userController.registerController();
});
userRouter.post("/login", async () => {
    await userController.loginUser();
})
userRouter.get("/profile", async () => {
    await userController.getUserProfile();
})

export default userRouter;