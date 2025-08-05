import express from "express";
import { ApplicationController } from "../controller/application.controller.js";
import isAuthenticated from "../middlewares/isAuthenticated.js";

const applicationRoutes = express.Router();

const applicationController = new ApplicationController();

applicationRoutes.get("/apply/:id", isAuthenticated, async (request, response) => {
    applicationController.applyJob(request, response);
});
applicationRoutes.get("/get-appliedJobs", isAuthenticated, async (request, response) => {
    applicationController.getAppliedJobs(request, response);
});
applicationRoutes.get("/:id/applicants", isAuthenticated, async(request, response) => {
    applicationController.getApplicants(request, response);
});
applicationRoutes.post("/status/:id/update", isAuthenticated, async (request, response) => {
    applicationController.updateStatus(request, response);
});

export default applicationRoutes; 