import { JobSchema } from "../schemas/job_schemas.js";
import { NotFoundError, ValidationError } from "../services/handlingErrors/appError.js";
import jobValidations from "../services/validations/jobValidations/jobValidations.js";

export class Job {
  async postJob(data, userId) {
    const { title, description, requirements, salary, location, jobType, experience, position, companyId} = data;
    const { error } = jobValidations.validate(data);
    if (error) {
      throw new ValidationError(error.details[0].message);
    }
    const job = await JobSchema.create({
      title,
      description,
      requirements: requirements.split(","),
      salary: Number(salary),
      location,
      jobType,
      experienceLevel: experience,
      position,
      company: companyId,
      created_by: userId,
    });
    return job;
  }

  async getAllJobs(keyword = ""){
    const query = {
        $or: [
            {title: { $regex: keyword, $options: "i" }},
            {description: { $regex: keyword, $options: "i" }},
        ]
    };
    const jobs = await JobSchema.find(query).populate({path: "company"}).sort({ createdAt: - 1});
    return jobs;
  }

  async getJobById(jobId){
    const job = await JobSchema.findById(jobId).populate({
      path: "applications",
      populate: {path: "applicant", select: "_id fullname email"}
    })
    if(!job){
        throw new NotFoundError("Job not found.");
    }
    return job;
  }

  async getAdminJobs(adminId){
    const jobs = await JobSchema.find({ created_by: adminId }).populate({
      path: "company",
      createdAt: -1
    })
    if(!jobs || jobs.length === 0){
        throw new NotFoundError("Job not found");
    }
    return jobs;
  }
}


