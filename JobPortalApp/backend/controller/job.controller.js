import { Job } from "../model/job.model.js";


export class JobController{
    constructor(){
        this.jobModel = new Job();
    }

    async postJob(request, response){
        try{
           const userId = request.id;
           const job = await this.jobModel.postJob(request.body, userId); 
           return response.status(201).json({message: "New job created successfully", job, success: true});
        }catch(error){
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({message: error.message ||"Failed to create job", success: false});
        }
    }

    async getAllJobs(request, response){
        try{
           const keyword = request.query.keyword || "";
           const jobs = await this.jobModel.getAllJobs(keyword);
           if(!jobs || jobs.length === 0){
            return response.status(404).json({message: "Job not found", success: false});
           }
           return response.status(200).json({jobs, success: true});
        }catch(error){
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({message: "Server error", success: false}); 
        }
    }

    async getJobById(request, response){
        try{
            const jobId = request.params.id;
           const job = await this.jobModel.getJobById(jobId);
           return response.status(200).json({ job, success: true}); 
        }catch(error){
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({message: error.message || "Server error", success: false});
        }
    }

    async getAdminJobs(request, response){
        try{
           const adminId = request.id;
           const jobs = await this.jobModel.getAdminJobs(adminId);
           return response.status(200).json({ jobs, success: true }); 
        }catch(error){
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({message: error.message || "Server error", success: false});
        }
    }
}