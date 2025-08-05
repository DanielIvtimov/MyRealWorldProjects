import epxress from "express";
import { JobController } from "../controller/job.controller.js";
import isAuthenticated from "../middlewares/isAuthenticated.js";

const jobRoutes = epxress.Router();

const jobController = new JobController();

jobRoutes.post("/post", isAuthenticated, async (request, response) => {
    jobController.postJob(request, response);
});
jobRoutes.get("/get-jobs", isAuthenticated, async (request, response) => {
    jobController.getAllJobs(request, response);
})
jobRoutes.get("/get-job/:id", isAuthenticated, async (request, response) => {
    jobController.getJobById(request, response);
})
jobRoutes.get("/getAdminJobs", isAuthenticated, async (request, response) => {
    jobController.getAdminJobs(request, response);
})

export default jobRoutes;

