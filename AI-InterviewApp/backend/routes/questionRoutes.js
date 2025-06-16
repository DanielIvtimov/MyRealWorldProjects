import express, { request } from "express";
import userProtect from "../middleware/authMiddleware.js";
import { QuestionController } from "../controllers/questionControllers.js";

const questionRoutes = express.Router();

const questionController = new QuestionController();

questionRoutes.post("/add", userProtect, async(request, response) => {
    await questionController.addQuestionToSession(request, response);
});
questionRoutes.post("/:id/pin", userProtect, async(request, response) => {
    await questionController.togglePinQuestion(request, response);
});
questionRoutes.post("/:id/note", userProtect, async(request, response) => {
    await questionController.updateQuestionNote(request, response);
});

export default questionRoutes;