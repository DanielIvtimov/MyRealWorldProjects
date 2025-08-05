import { Application } from "../model/application.model.js";


export class ApplicationController{
    constructor(){
        this.applicationModel = new Application();
    }

    async applyJob(request, response){
        try{
           const userId = request.id;
           const jobId = request.params.id;
           const application = await this.applicationModel.applyJob(userId, jobId);
           return response.status(200).json({message: "Application submited successfully", success: true}); 
        }catch(error){
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({message: error.message || "Failed to apply for job", success: false});
        }
    }

    async getAppliedJobs(request, response){
        try{
           const userId = request.id;
           const application = await this.applicationModel.getAppliedJobs(userId);
           return response.status(200).json({ application, success: true}); 
        }catch(error){
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({message: "Could not get applications", success: false});
        }
    }

    async getApplicants(request, response){
        try{
           const jobId = request.params.id;
           const job = await this.applicationModel.getApplicants(jobId);
           return response.status(200).json({ job, success: true }); 
        }catch(error){
            console.error("Error is:", error);
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({ message: "Could not get applicants", success: false});
        }
    }

    async updateStatus(request, response){
        try{
           const applicationId = request.params.id;
           const { status } = request.body;
           await this.applicationModel.updateStatus(applicationId, status);
           return response.status(200).json({ message: "Status updated successfully", success: true});
        }catch(error){
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({message: "Failed to update status", success: false});   
        }
    }
}

