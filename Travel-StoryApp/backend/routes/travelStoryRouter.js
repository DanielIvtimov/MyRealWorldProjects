import express, { request } from "express";
import { TravelStoryController } from "../controllers/travelStoryController.js";
import authenticateToken from "../utilities.js";

const travelStoryRoutes = express.Router();

const travelStoryController = new TravelStoryController();

travelStoryRoutes.post("/add-travel-story", authenticateToken, async (request, response) => {
    await travelStoryController.addTravelStory(request, response);
});

travelStoryRoutes.get("/get-all-stories", authenticateToken, async (request, response) => {
    await travelStoryController.getAllStories(request, response);
})

travelStoryRoutes.post("/edit-story/:id", authenticateToken, async (request, response) => {
    await travelStoryController.editTravelStory(request, response);
})

travelStoryRoutes.delete("/delete-story/:id", authenticateToken, async (request, response) => {
    await travelStoryController.deleteTravelStory(request, response);
})

export default travelStoryRoutes;