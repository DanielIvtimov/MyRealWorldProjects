import express from "express";
import { CompanyController } from "../controller/company.controller.js";
import isAuthenticated from "../middlewares/isAuthenticated.js";
import { singleUpload } from "../middlewares/multer.js";

const companyRoutes = express.Router();

const companyController = new CompanyController();

companyRoutes.post("/register-company", isAuthenticated, async (request, response) => {
    await companyController.registerCompany(request, response);
})
companyRoutes.get("/get-company", isAuthenticated, async (request, response) => {
    await companyController.getCompany(request, response);
})
companyRoutes.get("/get-company/:id", isAuthenticated, async (request, response) => {
    await companyController.getCompanyById(request, response);
})
companyRoutes.put("/update-company/:id", isAuthenticated, singleUpload, async (request, response) => {
    await companyController.updateCompany(request, response);
})

export default companyRoutes;