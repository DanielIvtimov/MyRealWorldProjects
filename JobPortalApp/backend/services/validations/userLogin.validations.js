import Joi from "joi";

const userLoginValidation = Joi.object({
    email: Joi.string().email().required(),
    password: Joi.string().min(8).pattern(new RegExp('^(?=.*[a-zA-Z])(?=.*[0-9])')).required(),
    role: Joi.string().valid("student", "recruiter").required(),
})

export default userLoginValidation;