import Joi from "joi";

const userValidation = Joi.object({
    fullname: Joi.string().min(3).max(50).required(),
    email: Joi.string().email().required(),
    phoneNumber: Joi.number().min(1000000).max(999999999999999).required(),
    password: Joi.string().min(8).pattern(new RegExp('^(?=.*[a-zA-Z])(?=.*[0-9])')).required(),
    role: Joi.string().valid("student", "recruiter").required()
})

export default userValidation;