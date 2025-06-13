import express from "express";
import userProtect from "../middleware/authMiddleware.js";
import { SessionController } from "../controllers/sessionControllers.js";

const sessionRoutes = express.Router();

const sessionController = new SessionController();

sessionRoutes.post("/create", userProtect, async (request, response) => {
    await sessionController.createSession(request, response);
})

sessionRoutes.get("/my-sessions", userProtect, async (request, response) => {
    await sessionController.getMySessions(request, response);
})

sessionRoutes.get("/:id", userProtect, async (request, response) => {
    await sessionController.getSessionById(request, response);
})

sessionRoutes.delete("/:id", userProtect, async (request, response) => {
    await sessionController.deleteSession(request, response);
})

export default sessionRoutes;

