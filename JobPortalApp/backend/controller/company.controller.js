import { Company } from "../model/company.model.js";

export class CompanyController {
    constructor(){
        this.companyModel = new Company();
    }

    async registerCompany(request, response){
        try{
           const userId = request.id;
           const { companyName } = request.body;
           const company = await this.companyModel.registerCompany(companyName, userId);
           return response.status(201).json({message: "Company register successfully", company, success: true});
        }catch(error){
            const statusCode = error.statusCode || 400;
            return response.status(statusCode).json({message: error.message || "Company registration failed", success: false});   
        }
    }

    async getCompany(request, response){
        try{
           const userId = request.id;
           const companies = await this.companyModel.getCompany(userId);
           return response.status(200).json({companies, success: true});
        }catch(error){
            const statusCode = error.statusCode || 400;
            return response.status(statusCode).json({message: "Could not retrieve companies", success: false});
        }
    }

    async getCompanyById(request, response){
        try{
           const companyId = request.params.id;
           const company = await this.companyModel.getCompanyById(companyId);
           return response.status(200).json({ company, success: true});
        }catch(error){
            const statusCode = error.statusCode || 400;
            return response.status(statusCode).json({message: "Could not retrieve company", success: false});
        }
    }

    async updateCompany(request, response){
        try{
            const file = request.file;
           const companyId = request.params.id;
           const company = await this.companyModel.updateCompany(companyId, request.body, file);
           return response.status(200).json({ message: "Company information updated.", company, success: true,}); 
        }catch(error){
            const statusCode = error.statusCode || 500;
            return response.status(statusCode).json({message: error.message || "Update failed", success: false});
        }
    }
}

