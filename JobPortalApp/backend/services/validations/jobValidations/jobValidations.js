import Joi from "joi";

const jobValidations = Joi.object({
    title: Joi.string().required(),
    description: Joi.string().required(),
    requirements: Joi.string().required(),
    salary: Joi.number().required(),
    location: Joi.string().required(),
    jobType: Joi.string().required(),
    experience: Joi.number().required(),
    position: Joi.number().required(),
    companyId: Joi.string().length(24).required(),
});

export default jobValidations;