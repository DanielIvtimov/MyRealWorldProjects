import { CompanySchema } from "../schemas/company_schemas.js";
import { ConflictError, NotFoundError, ValidationError } from "../services/handlingErrors/appError.js";
import uploadFileToCloudinary from "../utils/uploadFileToCloudinary.js";

export class Company {
    async registerCompany(companyName, userId){
        if(!companyName){
            throw new ValidationError("Company name is required");
        }
        const existingCompany = await CompanySchema.findOne({ name: companyName });
        if(existingCompany){
            throw new ConflictError("You can't register same company");
        }
        const company = await CompanySchema.create({
            name: companyName,
            userId: userId
        });
        return company;
    }

    async getCompany(userId){
        const companies = await CompanySchema.find({ userId });
        if(!companies || companies.length === 0){
            throw new NotFoundError("No companies found for this user");
        }
        return companies;
    }

    async getCompanyById(companyId){
        const company = await CompanySchema.findById(companyId);
        if(!company){
            throw new NotFoundError("Company not found");
        }
        return company;
    }

    async updateCompany(companyId, data, file){
        const { name, description, website, location } = data;
        let cloudResponse = null;
        if(file){
            cloudResponse = await uploadFileToCloudinary(file);
        }
        const updateData = { name, description, website, location};
        if(cloudResponse && file){
            updateData.logo = cloudResponse.secure_url;
            updateData.logoOriginalName = file.originalname;
        }
        const company = await CompanySchema.findByIdAndUpdate(companyId, updateData, {new: true});
        if(!company){
            throw new NotFoundError("Company not found");
        }
        return company;
    }
}

