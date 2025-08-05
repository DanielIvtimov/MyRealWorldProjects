import { ApplicationSchema } from "../schemas/application_schemas.js";
import { JobSchema } from "../schemas/job_schemas.js";
import { ConflictError, NotFoundError, ValidationError } from "../services/handlingErrors/appError.js";


export class Application{
    async applyJob(userId, jobId){
        if(!jobId){
            throw new ValidationError("Job id is required");
        }
        const existingApplication = await ApplicationSchema.findOne({ job: jobId, applicant: userId});
        if(existingApplication){
            throw new ConflictError("You have already applied for this job");
        }
        const job = await JobSchema.findById(jobId);
        if(!job){
            throw new NotFoundError("Job not found");
        }
        const newApplication = await ApplicationSchema.create({
            job: jobId,
            applicant: userId,
        });
        job.applications.push(newApplication._id);
        await job.save();
        return newApplication;
    }

    async getAppliedJobs(userId){
        const application = await ApplicationSchema.find({ applicant: userId })
        .sort({ createdAt: - 1 })
        .populate({
            path: "job",
            options: { sort: { createdAt: - 1 }},
            populate: {
                path: "company",
                options: { sort: {createdAt: - 1 }}
            }
        });
        if(!application){
            throw new NotFoundError("No Applications");
        }
        return application;
    }

    async getApplicants(jobId){
        const job = await JobSchema.findById(jobId).populate({
            path: "applications",
            options: { sort: {createdAt: - 1}},
            populate: {
                path: "applicant",
            }
        });
        if(!job){
            throw new NotFoundError("Job not found.");
        }
        return job;
    }

    async updateStatus(applicationId, status){
        if(!status){
            throw new ValidationError("Status is required");
        }
        const application = await ApplicationSchema.findOne({ _id: applicationId });
        if(!application){
            throw new NotFoundError("Application not found");
        }
        application.status = status.toLowerCase();
        await application.save();
        return application;
    }
}